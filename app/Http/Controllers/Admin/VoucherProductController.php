<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Voucher;

class VoucherProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $voucherFilter = $request->get('voucher');

        $products = Book::with('vouchers') // 🟢 Dùng Book thay vì Product
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->when($voucherFilter, function ($query, $voucherId) {
                $query->whereHas('vouchers', fn($q) => $q->where('vouchers.id', $voucherId));
            })
            ->paginate(10);

        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->latest()
            ->get();

        return view('admin.voucher-products.index', compact('products', 'vouchers'));
    }

    public function attach(Request $request)
    {
        $book = Book::findOrFail($request->input('product_id'));
        $voucherId = $request->input('voucher_id');

        $book->vouchers()->sync([$voucherId]); // 🟢 Gán voucher cho book
        return back()->with('success', '✅ Đã gán mã thành công (thay thế mã cũ nếu có)!');
    }

    public function detach(Request $request)
    {
        $book = Book::findOrFail($request->input('product_id'));
        $voucherId = $request->input('voucher_id');

        $book->vouchers()->detach($voucherId); // 🟢 Gỡ voucher khỏi book
        return back()->with('success', '🗑 Đã gỡ mã khỏi sản phẩm!');
    }
}
