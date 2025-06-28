<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 📋 Danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('keyword')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        $orders = $query->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // 📄 Chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['user', 'items.bookDetail.book'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // 🛠 Cập nhật trạng thái đơn
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->completed_date = now();
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    // 🗑 Xoá mềm đơn hàng
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        // Sửa tên route chính xác theo group admin
        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã xoá đơn hàng!');
    }
}
