<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class ChatBotController extends Controller
{
    private $n8nUrl = 'http://localhost:5678/webhook/chatbot';

    public function webhook(Request $request)
    {
        try {
            $message = trim($request->input('message'));
            $sessionId = $request->input('sessionId') ?? uniqid('session_');
            $timestamp = now();

            Log::info('Chatbot webhook received', [
                'message' => $message,
                'sessionId' => $sessionId,
            ]);

            $intent = $this->analyzeIntent($message);

            // 1. Nếu là chat tự do (hi, hello, xin chào...)
            if ($intent['type'] === 'general_chat') {
                $aiResult = $this->sendToN8n($message, $sessionId);
                return response()->json([
                    'success' => true,
                    'source' => 'ai',
                    'message' => $aiResult['message'],
                    'books' => [],
                    'sessionId' => $sessionId,
                    'timestamp' => $timestamp,
                ]);
            }

            // 2. Nếu là câu hỏi về sách, ưu tiên DB
            $dbResult = $this->searchInDatabase($intent);
            if ($dbResult['found']) {
                return response()->json([
                    'success' => true,
                    'source' => 'database',
                    'message' => $dbResult['message'],
                    'books' => $dbResult['books'],
                    'sessionId' => $sessionId,
                    'timestamp' => $timestamp,
                ]);
            }

            // 3. Nếu không có trong DB, hỏi n8n (AI)
            $aiResult = $this->sendToN8n($message, $sessionId);
            if (!empty($aiResult['message'])) {
                return response()->json([
                    'success' => true,
                    'source' => 'ai',
                    'message' => $aiResult['message'],
                    'books' => [],
                    'sessionId' => $sessionId,
                    'timestamp' => $timestamp,
                ]);
            }

            // 4. Không có ở đâu cả
            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, shop hiện không có thông tin về sách này.',
                'books' => [],
                'sessionId' => $sessionId,
                'timestamp' => $timestamp,
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại.',
            ], 500);
        }
    }

private function analyzeIntent($message)
{
    $text = mb_strtolower($message);

    $intent = [
        'type' => '',
        'keywords' => [],
        'price' => null,
    ];

    // Ưu tiên nhận diện các câu chào
    $greetings = ['hi', 'hello', 'chào', 'xin chào', 'hey'];
    foreach ($greetings as $greet) {
        if (str_contains($text, $greet)) {
            $intent['type'] = 'general_chat';
            break;
        }
    }

    if ($intent['type'] === 'general_chat') {
        $intent['search_terms'] = [];
        $intent['raw'] = '';
        return $intent;
    }

    // Ưu tiên nhận diện intent tác giả
    if (str_contains($text, 'tác giả')) {
        $intent['type'] = 'author_search';
    } else if (str_contains($text, 'nhà xuất bản') || str_contains($text, 'nxb') || str_contains($text, 'publisher')) {
        $intent['type'] = 'publisher_search';
    } else {
        // Mapping intent khác
        $mapping = [
            'book_search' => ['sách', 'cuốn', 'book', 'novel'],
            'category_search' => ['thể loại', 'genre', 'loại'],
            'promotion_inquiry' => ['khuyến mãi', 'giảm giá', 'sale'],
            'recommendation' => ['gợi ý', 'nên đọc', 'tư vấn'],
        ];
        foreach ($mapping as $type => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($text, $kw)) {
                    $intent['type'] = $type;
                    $intent['keywords'][] = $kw;
                }
            }
        }
    }

    // Lọc giá
    if (preg_match('/(dưới|nhỏ hơn|ít hơn)\s*(\d+)/u', $text, $m)) {
        $intent['type'] = 'price_below';
        $intent['price'] = (int) $m[2];
    } elseif (preg_match('/(trên|lớn hơn|cao hơn)\s*(\d+)/u', $text, $m)) {
        $intent['type'] = 'price_above';
        $intent['price'] = (int) $m[2];
    }

    $intent['search_terms'] = $this->extractSearchTerms($text);

    // Tạo lại raw từ search_terms đã lọc
    $intent['raw'] = implode(' ', $intent['search_terms']);

    // Nếu không nhận diện được intent, mặc định là tìm sách
    if (empty($intent['type'])) {
        $intent['type'] = 'book_search';
    }

    if (
        $intent['type'] === 'book_search' &&
        count($intent['search_terms']) >= 2 // tránh nhận sai
    ) {
        $authorQuery = \App\Models\Author::query();
        foreach ($intent['search_terms'] as $term) {
            $authorQuery->where('name', 'like', "%$term%");
        }
        if ($authorQuery->exists()) {
            $intent['type'] = 'author_search';
        }
    }

    return $intent;
}

private function extractSearchTerms($text)
{
    $stopWords = [
        'tôi', 'muốn', 'tìm', 'kiếm', 'sách', 'cuốn', 'của', 'có', 'không',
        'và', 'hay', 'hoặc', 'nào', 'gì', 'ai', 'xin', 'hãy', 'cho',
        'thông', 'tin', 'về', 'tên', 'là', 'tác', 'giả', 'k', 'đồng','đ', 'của'
    ];

    $words = preg_split('/\s+/u', $text);
    $terms = array_diff($words, $stopWords);

    return array_values(array_filter($terms, fn($t) => mb_strlen($t) > 2));
}


private function searchInDatabase($intent)
{
    try {
        $terms = $intent['search_terms'];
        $raw = $intent['raw'] ?? '';

        if (empty($terms) && !in_array($intent['type'], ['price_below', 'price_above', 'promotion_inquiry'])) {
            return ['found' => false];
        }

        $query = Book::with(['author', 'category', 'publisher', 'details']);

        switch ($intent['type']) {
            case 'author_search':
                $authorName = implode(' ', $terms);

                $books = $query->whereHas('author', fn($q) =>
                    $q->where('name', 'like', "%$authorName%")
                )->limit(3)->get();

                if ($books->isEmpty()) {
                    $query = Book::with(['author', 'category', 'publisher', 'details']);
                    $books = $query->whereHas('author', function ($q) use ($terms) {
                        $q->where(function ($subQ) use ($terms) {
                            foreach ($terms as $term) {
                                $subQ->orWhere('name', 'like', "%$term%");
                            }
                        });
                    })->limit(3)->get();
                }

                return [
                    'found' => $books->isNotEmpty(),
                    'message' => $this->formatBooks($books, $intent['type']),
                    'books' => $books->map(fn($b) => [
                        'id' => $b->id,
                        'name' => $b->name,
                        'author' => optional($b->author)->name,
                        'category' => optional($b->category)->name,
                        'publisher' => optional($b->publisher)->name,
                        'price' => optional($b->details->first())->price,
                        'promotion_price' => optional($b->details->first())->promotion_price,
                        'quantity' => optional($b->details->first())->quantity,
                        'description' => $b->description,
                    ])->toArray(),
                ];

            case 'publisher_search':
                $publisherName = implode(' ', $terms);
                $books = $query->whereHas('publisher', fn($q) =>
                    $q->where('name', 'like', "%$publisherName%")
                )->limit(3)->get();
                if ($books->isEmpty()) {
                    $query = Book::with(['author', 'category', 'publisher', 'details']);
                    $books = $query->whereHas('publisher', function ($q) use ($terms) {
                        $q->where(function ($subQ) use ($terms) {
                            foreach ($terms as $term) {
                                $subQ->orWhere('name', 'like', "%$term%");
                            }
                        });
                    })->limit(3)->get();
                }
                return [
                    'found' => $books->isNotEmpty(),
                    'message' => $this->formatBooks($books, $intent['type']),
                    'books' => $books->map(fn($b) => [
                        'id' => $b->id,
                        'name' => $b->name,
                        'author' => optional($b->author)->name,
                        'category' => optional($b->category)->name,
                        'publisher' => optional($b->publisher)->name,
                        'price' => optional($b->details->first())->price,
                        'promotion_price' => optional($b->details->first())->promotion_price,
                        'quantity' => optional($b->details->first())->quantity,
                        'description' => $b->description,
                    ])->toArray(),
                ];

            case 'book_search':
            case 'recommendation':
            case 'category_search':
                // 1. Ưu tiên tìm theo full phrase
                $books = $query->where('name', 'like', "%$raw%")
                    ->limit(3)->get();

                // 2. Fallback nếu không có
                if ($books->isEmpty()) {
                    $query = Book::with(['author', 'category', 'publisher', 'details']);

                    $query->where(function ($q) use ($terms) {
                        $q->where(function ($subQ) use ($terms) {
                            foreach ($terms as $term) {
                                $subQ->orWhere('name', 'like', "%$term%")
                                     ->orWhere('description', 'like', "%$term%");
                            }
                        });

                        $q->orWhereHas('category', function ($c) use ($terms) {
                            foreach ($terms as $term) {
                                $c->orWhere('name', 'like', "%$term%");
                            }
                        });
                    });

                    $books = $query->limit(3)->get();
                }

                break;

            case 'price_below':
                $query->whereHas('details', fn($d) =>
                    $d->where('price', '<=', $intent['price'])
                );
                $books = $query->limit(3)->get();
                if ($books->isEmpty()) {
                    return [
                        'found' => true,
                        'message' => 'Chưa có sách nào dưới giá mà bạn đang tìm.',
                        'books' => [],
                    ];
                }
                break;

            case 'price_above':
                $query->whereHas('details', fn($d) =>
                    $d->where('price', '>=', $intent['price'])
                );
                $books = $query->limit(3)->get();
                if ($books->isEmpty()) {
                    return [
                        'found' => true,
                        'message' => 'Chưa có sách nào trên giá mà bạn đang tìm.',
                        'books' => [],
                    ];
                }
                break;

            case 'promotion_inquiry':
                $query->whereHas('details', fn($d) =>
                    $d->whereNotNull('promotion_price')
                );
                $books = $query->limit(3)->get();
                break;

            default:
                $books = collect(); // Fallback chống lỗi
        }

        if ($books->isEmpty()) {
            return ['found' => false];
        }

        return [
            'found' => true,
            'message' => $this->formatBooks($books, $intent['type']),
            'books' => $books->map(fn($b) => [
                'id' => $b->id,
                'name' => $b->name,
                'author' => optional($b->author)->name,
                'category' => optional($b->category)->name,
                'publisher' => optional($b->publisher)->name,
                'price' => optional($b->details->first())->price,
                'promotion_price' => optional($b->details->first())->promotion_price,
                'quantity' => optional($b->details->first())->quantity,
                'description' => $b->description,
            ])->toArray(),
        ];
    } catch (\Exception $e) {
        Log::error('DB search error', ['error' => $e->getMessage()]);
        return ['found' => false];
    }
}


    private function formatBooks($books, $type)
    {
        $titles = [
            'book_search' => '🔍 Tôi tìm thấy các sách:',
            'recommendation' => '✨ Gợi ý sách hay:',
            'category_search' => '📂 Sách theo thể loại:',
            'price_below' => '💰 Sách có giá dưới mức bạn yêu cầu:',
            'price_above' => '💰 Sách có giá trên mức bạn yêu cầu:',
            'promotion_inquiry' => '🎉 Sách đang khuyến mãi:',
            'publisher_search' => '🏢 Sách theo nhà xuất bản:',
        ];
        $msg = $titles[$type] ?? '📚 Kết quả tìm kiếm:' . "\n\n";
        foreach ($books as $b) {
            $d = $b->details->first();
            $msg .= "📖 **{$b->name}**\n";
            $msg .= "✍️ Tác giả: " . optional($b->author)->name . "\n";
            $msg .= "📂 Thể loại: " . optional($b->category)->name . "\n";
            $msg .= "🏢 NXB: " . optional($b->publisher)->name . "\n";
            if ($d) {
                $msg .= "💰 Giá: " . number_format($d->price) . " đ\n";
                if ($d->promotion_price) {
                    $msg .= "🎯 KM: " . number_format($d->promotion_price) . " đ\n";
                }
                $msg .= "📦 Tồn kho: {$d->quantity}\n";
            }
            $msg .= "\n";
        }
        return $msg;
    }

    private function sendToN8n($message, $sessionId)
    {
        try {
            $response = Http::timeout(30)->post($this->n8nUrl, [
                'message' => $message,
                'sessionId' => $sessionId,
                'timestamp' => now()->toISOString(),
            ]);

            if (!$response->successful()) {
                Log::error('n8n error', ['status' => $response->status(), 'body' => $response->body()]);
                return ['success' => false, 'message' => 'Không thể kết nối AI.'];
            }

            $data = $response->json();

            // Log để debug
            Log::info('n8n response debug', [
                'full_response' => $data,
                'response_keys' => array_keys($data ?? [])
            ]);

            // Sửa lỗi: Kiểm tra cấu trúc response từ n8n
            $text = '';

            // Trường hợp 1: Response trực tiếp là string
            if (is_string($data)) {
                $text = $data;
            }
            // Trường hợp 2: Response có cấu trúc object
            elseif (is_array($data)) {
                // Ưu tiên các key thường dùng
                $possibleKeys = ['output', 'message', 'response', 'text', 'content', 'answer'];

                foreach ($possibleKeys as $key) {
                    if (isset($data[$key]) && !empty(trim($data[$key]))) {
                        $text = $data[$key];
                        break;
                    }
                }

                // Nếu vẫn không có, thử lấy giá trị đầu tiên không rỗng
                if (empty($text)) {
                    foreach ($data as $key => $value) {
                        if (is_string($value) && !empty(trim($value))) {
                            $text = $value;
                            break;
                        }
                    }
                }
            }

            // Nếu vẫn không có text, trả về message mặc định
            if (empty($text)) {
                Log::warning('n8n response empty', ['response' => $data]);
                $text = 'Xin lỗi, tôi không thể trả lời câu hỏi này lúc này.';
            }

            return ['success' => true, 'message' => trim($text)];

        } catch (\Exception $e) {
            Log::error('n8n exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => 'Xin lỗi, tôi không thể xử lý câu hỏi này.'];
        }
    }
}
