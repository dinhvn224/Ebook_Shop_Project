<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Voucher;

class VoucherProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $voucherFilter = $request->get('voucher');

        $products = \App\Models\Product::with('vouchers')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->when($voucherFilter, function ($query, $voucherId) {
                $query->whereHas('vouchers', fn($q) => $q->where('vouchers.id', $voucherId));
            })
            ->paginate(10);

        $vouchers = \App\Models\Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->latest()->get();

        return view('admin.voucher-products.index', compact('products', 'vouchers'));
    }


    public function attach(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $voucherId = $request->input('voucher_id');

        // Chỉ gán 1 mã duy nhất
        $product->vouchers()->sync([$voucherId]);

        return back()->with('success', '✅ Đã gán mã thành công (thay thế mã cũ nếu có)!');
    }
    public function detach(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $voucherId = $request->input('voucher_id');
        $product->vouchers()->detach($voucherId);

        return back()->with('success', '🗑 Đã gỡ mã khỏi sản phẩm!');
    }
}
