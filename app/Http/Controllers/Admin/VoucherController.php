<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Tìm kiếm theo mã
        if ($search = $request->query('search')) {
            $query->where('code', 'like', "%{$search}%");
        }

        // Lọc theo trạng thái
        if ($status = $request->query('status')) {
            $now = now();
            if ($status === 'active') {
                $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                    });
            } elseif ($status === 'expired') {
                $query->where('expires_at', '<', $now);
            }
        }

        // Lọc theo thời gian hết hạn
        if ($expiry = $request->query('expiry')) {
            if ($expiry === 'soon') {
                $query->whereBetween('expires_at', [now(), now()->addDays(7)]);
            } elseif ($expiry === 'no_expiry') {
                $query->whereNull('expires_at');
            }
        }

        // Load sản phẩm và phân trang
        $vouchers = $query->with('products')->latest()->paginate(10);

        return view('admin.vouchers.index', compact('vouchers'));
    }


public function create()
{
    $allProducts = \App\Models\Product::all();
    return view('admin.vouchers.create', compact('allProducts'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'code' => 'required|string|unique:vouchers,code',
        'type' => 'required|in:percent,fixed',
        'value' => 'required|numeric|min:1',
        'max_discount' => 'nullable|numeric|min:0',
        'usage_limit' => 'nullable|numeric|min:1',
        'start_at' => 'nullable|date',
        'expires_at' => 'nullable|date|after_or_equal:start_at',
        'is_active' => 'nullable|boolean',
        'product_ids' => 'nullable|array',
        'product_ids.*' => 'exists:products,id',
    ]);

    $data['is_active'] = $request->has('is_active');

    $voucher = \App\Models\Voucher::create($data);

    // Gán sản phẩm được chọn
    $voucher->products()->sync($request->input('product_ids', []));

    return redirect()->route('admin.vouchers.index')->with('success', '✅ Đã tạo voucher thành công!');
}


    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:1',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|numeric|min:1',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean'
        ]);

        $data['is_active'] = $request->has('is_active');

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', '✏️ Cập nhật voucher thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return back()->with('success', '🗑 Voucher đã được xoá tạm thời!');
    }
}
