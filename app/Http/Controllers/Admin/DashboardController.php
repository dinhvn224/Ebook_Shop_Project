<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BookDetail;

class DashboardController extends Controller
{
    public function index()
    {
        // 📈 1. Doanh thu theo tháng
        $monthlyRevenue = Order::selectRaw('MONTH(order_date) as month, SUM(final_amount) as total')
            ->whereYear('order_date', now()->year)
            ->where('status', 'PAID')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $revenueData = array_replace(array_fill(1, 12, 0), $monthlyRevenue);

        // 📦 2. Đơn hàng theo trạng thái
        $orderStatuses = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 🔥 3. Top 5 sản phẩm bán chạy
        $topProductsRaw = OrderItem::select('ebook_variant_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('ebook_variant_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topProductNames = [];
        $topProductQuantities = [];

        foreach ($topProductsRaw as $item) {
            $bookDetail = BookDetail::with('book')->find($item->ebook_variant_id);
            if ($bookDetail && $bookDetail->book) {
                $topProductNames[] = $bookDetail->book->name;
                $topProductQuantities[] = $item->total_sold;
            }
        }

        // ⚠️ 4. Sản phẩm tồn kho thấp
        $lowStock = BookDetail::with('book')
            ->where('quantity', '<', 10)
            ->orderBy('quantity')
            ->get();

        $lowStockNames = $lowStock->map(function ($b) {
            return optional($b->book)->name . ' (' . $b->language . ')';
        });

        $lowStockQuantities = $lowStock->pluck('quantity');

        // 👉 Truyền về view
        return view('admin.dashboard', compact(
            'revenueData',
            'orderStatuses',
            'topProductNames',
            'topProductQuantities',
            'lowStockNames',
            'lowStockQuantities'
        ));
    }
}
