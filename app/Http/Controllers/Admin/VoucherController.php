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
                        $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                    });
            } elseif ($status === 'expired') {
                $query->where('end_date', '<', $now);
            }
        }

        // Lọc theo thời gian hết hạn
        if ($expiry = $request->query('expiry')) {
            if ($expiry === 'soon') {
                $query->whereBetween('end_date', [now(), now()->addDays(7)]);
            } elseif ($expiry === 'no_expiry') {
                $query->whereNull('end_date');
            }
        }

        // Load sách và phân trang
        $vouchers = $query->with('books')->latest()->paginate(10);

        return view('admin.vouchers.index', compact('vouchers'));
    }


    public function create()
    {
        $allBooks = \App\Models\Book::all();
        return view('admin.vouchers.create', compact('allBooks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'discount_type' => 'required|in:PERCENT,FIXED',
            'discount_value' => 'required|numeric|min:1',
            'max_uses' => 'nullable|numeric|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
            'book_ids' => 'nullable|array',
            'book_ids.*' => 'exists:books,id',
        ]);

        $data['is_active'] = $request->has('is_active');

        $voucher = \App\Models\Voucher::create($data);

        // Gán sách được chọn
        $voucher->books()->sync($request->input('book_ids', []));

        return redirect()->route('admin.vouchers.index')->with('success', '✅ Đã tạo voucher thành công!');
    }


    public function edit(Voucher $voucher)
    {
        $allBooks = \App\Models\Book::all();
        return view('admin.vouchers.edit', compact('voucher', 'allBooks'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
            'discount_type' => 'required|in:PERCENT,FIXED',
            'discount_value' => 'required|numeric|min:1',
            'max_uses' => 'nullable|numeric|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
            'book_ids' => 'nullable|array',
            'book_ids.*' => 'exists:books,id',
        ]);

        $data['is_active'] = $request->has('is_active');

        $voucher->update($data);

        // Cập nhật sách liên kết
        $voucher->books()->sync($request->input('book_ids', []));

        return redirect()->route('admin.vouchers.index')->with('success', '✏️ Cập nhật voucher thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return back()->with('success', '🗑 Voucher đã được xoá tạm thời!');
    }
}
