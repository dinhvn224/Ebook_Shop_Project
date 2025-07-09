<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Book; // Đảm bảo model Book đã được import
use App\Models\Author; // Đảm bảo model Author đã được import
use App\Models\Category; // Đảm bảo model Category đã được import
use App\Models\Publisher; // Đảm bảo model Publisher đã được import
use App\Models\BookDetail; // Đảm bảo model BookDetail đã được import

class ChatBotController extends Controller
{
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

            // Phân tích ý định của người dùng
            $intent = $this->analyzeIntent($message);

            // 1. Nếu là chat tự do (hi, hello, xin chào...)
            // Hoặc nếu ý định là 'recommendation' và không có search_terms cụ thể (câu hỏi chung chung)
            // Hoặc không nhận diện được ý định nào, gửi thẳng đến AI
            if ($intent['type'] === 'general_chat' || empty($intent['type'])) {
                $aiResult = $this->callGeminiApi($message); // Không cần sessionId khi không duy trì ngữ cảnh
                return response()->json([
                    'success' => true,
                    'source' => 'ai',
                    'message' => $aiResult['message'],
                    'books' => [],
                    'sessionId' => $sessionId,
                    'timestamp' => $timestamp,
                ]);
            }

            // 2. Nếu là câu hỏi về sách có thể tìm trong DB (tên, tác giả, NXB, giá, thể loại), ưu tiên DB
            $dbResult = $this->searchInDatabase($intent);

            // Nếu tìm thấy trong database, trả về kết quả từ DB
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

            // --- NEW FUNCTIONALITY: Tóm tắt sách từ DB hoặc hỏi AI ---
            if ($intent['type'] === 'summarize_book' && !empty($intent['raw'])) {
                $bookName = $intent['raw'];
                // Sử dụng 'with' để tải eager loading 'details' nếu bạn cần các thông tin khác của BookDetail ở đây
                $book = Book::where('name', 'like', "%{$bookName}%")->first();

                if ($book && !empty($book->description)) {
                    // Cung cấp mô tả sách cho Gemini để tóm tắt
                    $summaryPrompt = "Tóm tắt cuốn sách có tên '{$book->name}' với nội dung sau: \"{$book->description}\". Nếu nội dung này quá ngắn hoặc không đủ chi tiết, hãy sử dụng kiến thức chung của bạn để cung cấp thêm thông tin hữu ích về sách.";
                    $geminiResponse = $this->callGeminiApi($summaryPrompt);
                    return response()->json([
                        'success' => $geminiResponse['success'],
                        'source' => 'ai_summary',
                        'message' => $geminiResponse['message'],
                        'books' => [], // Không trả về sách ở đây vì đã có thông tin tóm tắt
                        'sessionId' => $sessionId,
                        'timestamp' => $timestamp,
                    ]);
                } else {
                    // Nếu không tìm thấy sách trong DB hoặc sách không có mô tả,
                    // chuyển yêu cầu gốc đến Gemini để nó xử lý bằng kiến thức chung
                    $geminiResponse = $this->callGeminiApi($message);
                     return response()->json([
                        'success' => $geminiResponse['success'],
                        'source' => 'ai',
                        'message' => $geminiResponse['message'],
                        'books' => [],
                        'sessionId' => $sessionId,
                        'timestamp' => $timestamp,
                    ]);
                }
            }
            // --- END NEW FUNCTIONALITY ---


            // 3. Nếu không có trong DB và là yêu cầu "gợi ý/tư vấn" (recommendation)
            $aiResult = ['success' => false, 'message' => '']; // Khởi tạo giá trị mặc định

            if ($intent['type'] === 'recommendation') {
                // Lấy 3 cuốn sách ngẫu nhiên
                $contextualBooks = Book::with(['author', 'category', 'publisher', 'details'])
                                       ->inRandomOrder()
                                       ->limit(3) // Lấy đúng 3 cuốn sách ngẫu nhiên
                                       ->get();

                $responseMessage = '';
                if ($contextualBooks->isNotEmpty()) {
                    // Sử dụng phương thức formatBooks để định dạng kết quả gợi ý
                    $responseMessage = $this->formatBooks($contextualBooks, 'recommendation');
                    $responseMessage .= "\nBạn có muốn tôi cung cấp thêm thông tin về các sách này hoặc gợi ý dựa trên sở thích cụ thể không?";
                } else {
                    $responseMessage = "Xin lỗi, hiện tại tôi không thể tìm thấy sách nào để gợi ý ngẫu nhiên.";
                }

                return response()->json([
                    'success' => true,
                    'source' => 'database_random_recommendation',
                    'message' => $responseMessage, // Đã được định dạng bởi formatBooks
                    'books' => $contextualBooks->map(fn($b) => [
                        'id' => $b->id,
                        'name' => $b->name,
                        'author' => optional($b->author)->name,
                        'category' => optional($b->category)->name,
                        'publisher' => optional($b->publisher)->name,
                        'price' => optional($b->details->first())->price,
                        'promotion_price' => optional($b->details->first())->promotion_price,
                        'quantity' => optional($b->details->first())->quantity,
                        'description' => $b->description,
                        'url' => url('/product/' . $b->id), // Thay đổi URL chi tiết sản phẩm
                    ])->toArray(),
                    'sessionId' => $sessionId,
                    'timestamp' => $timestamp,
                ]);

            } else {
                // Các trường hợp còn lại (intent không phải recommendation và không tìm thấy trong DB,
                // và cũng không phải summarize_book đã được xử lý),
                // gửi tin nhắn gốc đến Gemini như một fallback chung
                $aiResult = $this->callGeminiApi($message); // Không cần sessionId
            }

            // Trả về kết quả từ AI nếu có
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

            // 4. Không tìm thấy ở đâu cả
            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, shop hiện không có thông tin hoặc gợi ý phù hợp với yêu cầu của bạn.',
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

    /**
     * Phân tích ý định của người dùng từ tin nhắn.
     * @param string $message
     * @return array
     */
    private function analyzeIntent($message)
    {
        $text = mb_strtolower($message);

        $intent = [
            'type' => '',
            'keywords' => [],
            'price' => null,
            'search_terms' => [],
            'raw' => '',
        ];

        // Ưu tiên nhận diện các câu chào hỏi chung chung
        $greetings = ['hi', 'hello', 'chào', 'xin chào', 'hey', 'alo', 'bạn khỏe không'];
        foreach ($greetings as $greet) {
            if (str_contains($text, $greet)) {
                $intent['type'] = 'general_chat';
                break;
            }
        }

        if ($intent['type'] === 'general_chat') {
            return $intent;
        }

        // NEW: Nhận diện ý định tóm tắt sách
        // Sử dụng regex để lấy tên sách sau "tóm tắt sách", "nội dung chính sách", "thông tin về sách"
        // Regex đã được điều chỉnh để linh hoạt hơn với từ "cuốn" hoặc không có từ nào sau động từ
        if (preg_match('/(tóm tắt|nội dung chính|thông tin về)\s*(cuốn|sách)?\s*(.+)/u', $text, $matches)) {
            $intent['type'] = 'summarize_book';
            $bookNameCandidate = trim($matches[3]); // Lấy phần sau "sách" hoặc "cuốn"
            $intent['raw'] = $bookNameCandidate; // Giữ nguyên tên sách để tìm kiếm chính xác
            // Vẫn lọc stop words cho search_terms nếu muốn dùng cho tìm kiếm linh hoạt hơn
            // nhưng ở đây ta dùng raw để tìm kiếm chính xác tên sách
            $intent['search_terms'] = $this->extractSearchTerms($bookNameCandidate);
            return $intent; // Trả về ngay nếu ý định này được phát hiện
        }


        // Nhận diện các ý định tìm kiếm cụ thể (ưu tiên)
        if (str_contains($text, 'tác giả')) {
            $intent['type'] = 'author_search';
        } else if (str_contains($text, 'nhà xuất bản') || str_contains($text, 'nxb') || str_contains($text, 'publisher')) {
            $intent['type'] = 'publisher_search';
        } else {
            // Mapping các ý định khác
            $mapping = [
                'book_search' => ['sách', 'cuốn', 'book', 'novel'],
                'category_search' => ['thể loại', 'genre', 'loại'],
                'promotion_inquiry' => ['khuyến mãi', 'giảm giá', 'sale', 'khuyến mại'],
                'recommendation' => ['gợi ý', 'nên đọc', 'tư vấn', 'sách hay', 'tìm sách phù hợp', 'random sách'], // Thêm 'random sách'
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

        // Trích xuất các từ khóa tìm kiếm chính
        $intent['search_terms'] = $this->extractSearchTerms($text);
        // Tạo raw từ search_terms đã lọc để dùng cho tìm kiếm full phrase
        $intent['raw'] = implode(' ', $intent['search_terms']);

        // Nếu không nhận diện được intent cụ thể nào khác và có search terms, mặc định là tìm sách
        if (empty($intent['type']) && !empty($intent['search_terms'])) {
            $intent['type'] = 'book_search';
        }

        // Kiểm tra xem các search_terms có thể là tên tác giả không nếu intent là book_search ban đầu
        if (
            $intent['type'] === 'book_search' &&
            count($intent['search_terms']) >= 1 // Có thể là tên tác giả 1 từ
        ) {
            $authorNameCandidate = implode(' ', $intent['search_terms']);
            $authorExists = Author::where('name', 'like', "%{$authorNameCandidate}%")->exists();

            if ($authorExists) {
                $intent['type'] = 'author_search';
            }
        }

        return $intent;
    }

    /**
     * Trích xuất các từ khóa tìm kiếm từ tin nhắn bằng cách loại bỏ stop words.
     * @param string $text
     * @return array
     */
    private function extractSearchTerms($text)
    {
        $stopWords = [
            'tôi', 'muốn', 'tìm', 'kiếm', 'cuốn', 'của', 'có', 'không',
            'và', 'hay', 'hoặc', 'nào', 'gì', 'ai', 'xin', 'hãy', 'cho',
            'thông', 'tin', 'về', 'tên', 'là', 'tác', 'giả', 'k', 'đồng','đ',
            'nhà', 'xuất', 'bản', 'nxb', 'publisher', 'thể', 'loại', 'genre', 'loại',
            'khuyến', 'mãi', 'giảm', 'giá', 'sale', 'gợi', 'ý', 'nên', 'đọc', 'tư', 'vấn',
            'dưới', 'nhỏ', 'hơn', 'ít', 'trên', 'lớn', 'cao', 'random' // Thêm 'random' vào stop words
        ];

        // Tách từ và loại bỏ dấu câu
        $words = preg_split('/[^\p{L}\p{N}]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        // Lọc stop words
        $terms = array_diff($words, $stopWords);

        // Lọc các từ có độ dài nhỏ hơn 2 ký tự (sau khi loại bỏ stop words)
        return array_values(array_filter($terms, fn($t) => mb_strlen($t) > 1));
    }

    /**
     * Tìm kiếm sách trong cơ sở dữ liệu dựa trên ý định.
     * @param array $intent
     * @return array
     */
    private function searchInDatabase($intent)
    {
        try {
            $terms = $intent['search_terms'];
            $raw = $intent['raw'] ?? '';

            // Nếu không có từ khóa tìm kiếm và không phải là các intent đặc biệt về giá/khuyến mãi
            // và không phải là recommendation (vì recommendation sẽ có logic riêng)
            // và không phải summarize_book (vì summarize_book sẽ được xử lý riêng)
            if (empty($terms) && !in_array($intent['type'], ['price_below', 'price_above', 'promotion_inquiry'])) {
                return ['found' => false];
            }
            // Nếu là summarize_book, searchInDatabase sẽ không xử lý nó ở đây, mà webhook sẽ xử lý
            if ($intent['type'] === 'summarize_book') {
                return ['found' => false];
            }


            $query = Book::with(['author', 'category', 'publisher', 'details']);
            $books = collect(); // Khởi tạo collection rỗng

            switch ($intent['type']) {
                case 'author_search':
                    $authorName = implode(' ', $terms);
                    $books = $query->whereHas('author', fn($q) =>
                        $q->where('name', 'like', "%$authorName%")
                    )->limit(3)->get();

                    if ($books->isEmpty() && !empty($terms)) {
                        $books = $query->whereHas('author', function ($q) use ($terms) {
                            $q->where(function ($subQ) use ($terms) {
                                foreach ($terms as $term) {
                                    $subQ->orWhere('name', 'like', "%$term%");
                                }
                            });
                        })->limit(3)->get();
                    }
                    break;

                case 'publisher_search':
                    $publisherName = implode(' ', $terms);
                    $books = $query->whereHas('publisher', fn($q) =>
                        $q->where('name', 'like', "%$publisherName%")
                    )->limit(3)->get();

                    if ($books->isEmpty() && !empty($terms)) {
                        $books = $query->whereHas('publisher', function ($q) use ($terms) {
                            $q->where(function ($subQ) use ($terms) {
                                foreach ($terms as $term) {
                                    $subQ->orWhere('name', 'like', "%$term%");
                                }
                            });
                        })->limit(3)->get();
                    }
                    break;

                case 'book_search':
                case 'category_search': // Category_search cũng sẽ tìm theo tên sách hoặc thể loại

                    // 1. Ưu tiên tìm theo full phrase nếu có raw message
                    if (!empty($raw)) {
                        $books = $query->where('name', 'like', "%$raw%")
                                       ->orWhere('description', 'like', "%$raw%")
                                       ->limit(3)->get();
                    }

                    // 2. Fallback nếu không có hoặc tìm theo từng từ khóa
                    if ($books->isEmpty() && !empty($terms)) {
                        $query = Book::with(['author', 'category', 'publisher', 'details']); // Reset query

                        $query->where(function ($q) use ($terms) {
                            $q->where(function ($subQ) use ($terms) {
                                foreach ($terms as $term) {
                                    $subQ->orWhere('name', 'like', "%$term%")
                                         ->orWhere('description', 'like', "%$term%");
                                }
                            });

                            // Thêm tìm kiếm theo category nếu có
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
                    if (is_null($intent['price'])) return ['found' => false];
                    $books = $query->whereHas('details', fn($d) =>
                        $d->where('price', '<=', $intent['price'])
                    )->limit(3)->get();
                    if ($books->isEmpty()) {
                        return [
                            'found' => true, // vẫn coi là tìm thấy ý định, nhưng không có kết quả
                            'message' => 'Chưa có sách nào có giá dưới ' . number_format($intent['price']) . ' đ.',
                            'books' => [],
                        ];
                    }
                    break;

                case 'price_above':
                    if (is_null($intent['price'])) return ['found' => false];
                    $books = $query->whereHas('details', fn($d) =>
                        $d->where('price', '>=', $intent['price'])
                    )->limit(3)->get();
                    if ($books->isEmpty()) {
                        return [
                            'found' => true, // vẫn coi là tìm thấy ý định, nhưng không có kết quả
                            'message' => 'Chưa có sách nào có giá trên ' . number_format($intent['price']) . ' đ.',
                            'books' => [],
                        ];
                    }
                    break;

                case 'promotion_inquiry':
                    $books = $query->whereHas('details', fn($d) =>
                        $d->whereNotNull('promotion_price')->where('promotion_price', '>', 0)
                    )->limit(3)->get();
                    if ($books->isEmpty()) {
                        return [
                            'found' => true, // vẫn coi là tìm thấy ý định, nhưng không có kết quả
                            'message' => 'Hiện tại chưa có sách nào đang được khuyến mãi.',
                            'books' => [],
                        ];
                    }
                    break;

                default:
                    $books = collect(); // Không tìm thấy ý định cụ thể nào để tìm trong DB
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
                    'url' => url('/product/' . $b->id), // Thay đổi URL chi tiết sản phẩm ở đây
                ])->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('DB search error', ['error' => $e->getMessage()]);
            return ['found' => false];
        }
    }

    /**
     * Định dạng danh sách sách thành tin nhắn cho người dùng.
     * @param \Illuminate\Database\Eloquent\Collection $books
     * @param string $type
     * @return string
     */
    private function formatBooks($books, $type)
    {
        $titles = [
            'book_search' => '🔍 Tôi tìm thấy các sách:',
            'recommendation' => '✨ Dưới đây là 3 cuốn sách ngẫu nhiên có sẵn trong cửa hàng của tôi mà bạn có thể tham khảo:',
            'category_search' => '📂 Sách theo thể loại:',
            'price_below' => '💰 Sách có giá dưới mức bạn yêu cầu:',
            'price_above' => '💰 Sách có giá trên mức bạn yêu cầu:',
            'promotion_inquiry' => '🎉 Sách đang khuyến mãi:',
            'publisher_search' => '🏢 Sách theo nhà xuất bản:',
            'author_search' => '✍️ Sách theo tác giả:',
            'summarize_book' => '📖 Tóm tắt sách:',
        ];
        $msg = ($titles[$type] ?? '📚 Kết quả tìm kiếm:') . "\n\n";

        if ($books->isEmpty()) {
            return "Xin lỗi, không tìm thấy sách nào theo yêu cầu của bạn.";
        }

        foreach ($books as $b) {
            $d = $b->details->first();
            $msg .= "📖 **{$b->name}**\n";
            $msg .= "✍️ Tác giả: " . optional($b->author)->name . "\n";
            $msg .= "📂 Thể loại: " . optional($b->category)->name . "\n";
            $msg .= "🏢 NXB: " . optional($b->publisher)->name . "\n";
            if ($d) {
                $msg .= "💰 Giá: " . number_format($d->price) . " đ\n";
                if ($d->promotion_price && $d->promotion_price < $d->price) {
                    $msg .= "🎯 KM: " . number_format($d->promotion_price) . " đ\n";
                }
                $msg .= "📦 Tồn kho: {$d->quantity}\n";
            }
            // Thêm URL chi tiết sách nếu có
            $msg .= "🔗 Chi tiết: " . url('/product/' . $b->id) . "\n";
            $msg .= "\n";
        }
        return $msg;
    }

    /**
     * Gọi API Gemini để lấy phản hồi AI.
     * @param string $message Tin nhắn gửi đến AI.
     * @return array Kết quả phản hồi từ AI.
     */
    private function callGeminiApi($message)
    {
        try {
            $apiKey = env('GEMINI_API_KEY');
            $model = env('GEMINI_MODEL', 'gemini-pro');
            $maxTokens = env('GEMINI_MAX_TOKENS', 1000);
            $temperature = env('GEMINI_TEMPERATURE', 0.7);

            if (empty($apiKey)) {
                Log::error('Gemini API Key is not set in .env');
                return ['success' => false, 'message' => 'Lỗi: Khóa API Gemini chưa được cấu hình.'];
            }

            // Xây dựng nội dung cho API Gemini
            $contents = [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $message]
                    ]
                ]
            ];

            $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => (float) $temperature,
                    'maxOutputTokens' => (int) $maxTokens,
                ],
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'message_sent' => $message
                ]);
                return ['success' => false, 'message' => 'Xin lỗi, không thể kết nối với AI Gemini. Vui lòng thử lại sau.'];
            }

            $data = $response->json();

            Log::info('Gemini response debug', [
                'full_response' => $data,
            ]);

            $text = '';
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                Log::warning('Gemini response format unexpected', ['response' => $data]);
                $text = 'Xin lỗi, tôi không thể trả lời câu hỏi này lúc này.';
            }

            return ['success' => true, 'message' => trim($text)];

        } catch (\Exception $e) {
            Log::error('Gemini API exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'message_sent' => $message
            ]);
            return ['success' => false, 'message' => 'Xin lỗi, tôi không thể xử lý yêu cầu này.'];
        }
    }
}
