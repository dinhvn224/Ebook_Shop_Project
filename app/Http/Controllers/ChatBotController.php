<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;

class ChatBotController extends Controller
{
    /**
     * Webhook endpoint để nhận request từ frontend
     */
    public function webhook(Request $request)
    {
        try {
            $message = $request->input('message');
            $sessionId = $request->input('sessionId');
            $timestamp = $request->input('timestamp');

            Log::info('Chatbot webhook received', [
                'message' => $message,
                'sessionId' => $sessionId,
                'timestamp' => $timestamp
            ]);

            // Tìm kiếm sách trong database
            $books = $this->searchBooks($message);

            if (!empty($books)) {
                // Nếu tìm thấy sách trong database
                $response = [
                    'success' => true,
                    'source' => 'database',
                    'message' => $this->formatBooks($books),
                    'books' => $books,
                    'sessionId' => $sessionId,
                    'timestamp' => now()
                ];
            } else {
                // Nếu không tìm thấy, trả về để gọi AI
                $response = [
                    'success' => true,
                    'source' => 'ai',
                    'message' => 'Tôi sẽ tìm kiếm thông tin cho bạn...',
                    'books' => [],
                    'sessionId' => $sessionId,
                    'timestamp' => now()
                ];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Chatbot webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm kiếm sách theo regex patterns
     */
    private function searchBooks($message)
    {
        $message = strtolower(trim($message));

        // Các pattern tìm kiếm
        $patterns = [
            'sách' => '/sách|book/i',
            'tác giả' => '/tác giả|author|tác giả là/i',
            'thể loại' => '/thể loại|category|danh mục/i',
            'giá' => '/giá|price|bao nhiêu tiền/i',
            'mô tả' => '/mô tả|description|nội dung/i',
            'nhà xuất bản' => '/nhà xuất bản|publisher/i'
        ];

        $query = Book::with(['author', 'publisher', 'category', 'details'])
            ->where('deleted', false);

        // Tìm kiếm theo tên sách
        if (preg_match('/sách.*?([a-zA-ZÀ-ỹ\s]+)/i', $message, $matches)) {
            $bookName = trim($matches[1]);
            $query->where('name', 'LIKE', "%{$bookName}%");
        }

        // Tìm kiếm theo tác giả
        if (preg_match('/tác giả.*?([a-zA-ZÀ-ỹ\s]+)/i', $message, $matches)) {
            $authorName = trim($matches[1]);
            $query->whereHas('author', function($q) use ($authorName) {
                $q->where('name', 'LIKE', "%{$authorName}%");
            });
        }

        // Tìm kiếm theo thể loại
        if (preg_match('/thể loại.*?([a-zA-ZÀ-ỹ\s]+)/i', $message, $matches)) {
            $categoryName = trim($matches[1]);
            $query->whereHas('category', function($q) use ($categoryName) {
                $q->where('name', 'LIKE', "%{$categoryName}%");
            });
        }

        // Tìm kiếm theo nhà xuất bản
        if (preg_match('/nhà xuất bản.*?([a-zA-ZÀ-ỹ\s]+)/i', $message, $matches)) {
            $publisherName = trim($matches[1]);
            $query->whereHas('publisher', function($q) use ($publisherName) {
                $q->where('name', 'LIKE', "%{$publisherName}%");
            });
        }

        // Tìm kiếm theo giá
        if (preg_match('/giá.*?(\d+)/i', $message, $matches)) {
            $price = $matches[1];
            $query->whereHas('details', function($q) use ($price) {
                $q->where('price', '<=', $price);
            });
        }

        // Nếu không có pattern cụ thể, tìm kiếm tổng quát
        if (!preg_match('/sách|tác giả|thể loại|giá|mô tả|nhà xuất bản/i', $message)) {
            $query->where(function($q) use ($message) {
                $q->where('name', 'LIKE', "%{$message}%")
                  ->orWhere('description', 'LIKE', "%{$message}%")
                  ->orWhereHas('author', function($subQ) use ($message) {
                      $subQ->where('name', 'LIKE', "%{$message}%");
                  })
                  ->orWhereHas('category', function($subQ) use ($message) {
                      $subQ->where('name', 'LIKE', "%{$message}%");
                  });
            });
        }

        return $query->limit(5)->get();
    }

    /**
     * Format kết quả sách
     */
    private function formatBooks($books)
    {
        if ($books->isEmpty()) {
            return "Xin lỗi, tôi không tìm thấy sách nào phù hợp với yêu cầu của bạn.";
        }

        $response = "Tôi tìm thấy " . $books->count() . " sách phù hợp:\n\n";

        foreach ($books as $book) {
            $response .= "📚 **{$book->name}**\n";

            if ($book->author) {
                $response .= "✍️ Tác giả: {$book->author->name}\n";
            }

            if ($book->category) {
                $response .= "📂 Thể loại: {$book->category->name}\n";
            }

            if ($book->publisher) {
                $response .= "🏢 Nhà xuất bản: {$book->publisher->name}\n";
            }

            if ($book->details->isNotEmpty()) {
                $detail = $book->details->first();
                $response .= "💰 Giá: " . number_format($detail->price) . " VNĐ\n";

                if ($detail->promotion_price) {
                    $response .= "🎯 Giá khuyến mãi: " . number_format($detail->promotion_price) . " VNĐ\n";
                }

                if ($detail->quantity > 0) {
                    $response .= "📦 Còn lại: {$detail->quantity} cuốn\n";
                } else {
                    $response .= "❌ Hết hàng\n";
                }
            }

            if ($book->description) {
                $description = substr($book->description, 0, 100);
                $response .= "📝 Mô tả: {$description}...\n";
            }

            $response .= "\n---\n\n";
        }

        return $response;
    }
}
