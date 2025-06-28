<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BookDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class CounterSaleController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $orders = Order::with([
                'items.bookDetail' => fn($q) => $q->withTrashed(),
                'items.bookDetail.book' => fn($q) => $q->withoutGlobalScopes()
            ])
            ->where('payment_method', 'COUNTER')
            ->when($status !== null, fn($q) => $q->where('status', $status))
            ->when($status === null, fn($q) => $q->where('status', 'PENDING'))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.counter.index', compact('orders', 'status'));
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'customer_name' => 'Khách lẻ',
            'shipping_address' => 'Tại quầy',
            'phone_number' => '0000000000',
            'final_amount' => 0,
            'status' => 'PENDING',
            'payment_method' => 'COUNTER',
            'order_date' => now(),
        ]);

        return redirect()->route('admin.counter.index')
            ->with('success', "Đã tạo đơn hàng tại quầy #$order->id");
    }

    public function show(Order $order)
    {
        $order->load([
            'items.bookDetail' => fn($q) => $q->withTrashed(),
            'items.bookDetail.book' => fn($q) => $q->withoutGlobalScopes()
        ]);

        return view('admin.counter.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        return view('admin.counter.receipt', compact('order'));
    }

    public function exportPdf(Order $order)
    {
        $pdf = Pdf::loadView('admin.counter.receipt', compact('order'));
        return $pdf->download('hoadon_#' . $order->id . '.pdf');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:PENDING,PAID,COMPLETED,CANCELLED,REFUNDED',
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Cập nhật trạng thái đơn thành công!');
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($request->has('ebook_variant_id')) {
            $request->validate([
                'ebook_variant_id' => 'required|exists:book_details,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $bookIds = [$request->ebook_variant_id];
            $quantities = [$request->ebook_variant_id => $request->quantity];
        } else {
            $request->validate([
                'products' => 'required|array',
                'products.*' => 'exists:book_details,id',
                'quantities' => 'required|array',
            ]);

            $bookIds = $request->products;
            $quantities = $request->quantities;
        }

        $order = Order::findOrFail($request->order_id);

        foreach ($bookIds as $bookId) {
            $qty = (int) ($quantities[$bookId] ?? 1);
            $bookDetail = BookDetail::withTrashed()->findOrFail($bookId);

            if ($bookDetail->quantity < $qty) {
                return back()->with('error', "Sản phẩm [ID: $bookId] không đủ tồn kho!");
            }

            $existing = OrderItem::withTrashed()
                ->where('order_id', $request->order_id)
                ->where('ebook_variant_id', $bookId)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $qty);
                if ($existing->trashed()) {
                    $existing->restore();
                }
            } else {
                OrderItem::create([
                    'order_id' => $request->order_id,
                    'ebook_variant_id' => $bookId,
                    'quantity' => $qty,
                    'price' => $bookDetail->price,
                    'promotion_price' => $bookDetail->promotion_price > 0 ? $bookDetail->promotion_price : null,
                ]);
            }

            $bookDetail->decrement('quantity', $qty);
        }

        $this->recalculateOrderTotal($request->order_id);

        return back()->with('success', 'Thêm sản phẩm thành công!');
    }

    public function updateItem(Request $request, OrderItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $newQty = $request->quantity;
        $diff = $newQty - $item->quantity;
        $bookDetail = $item->bookDetail;

        if ($diff > 0 && $bookDetail->quantity < $diff) {
            return back()->with('error', 'Không đủ kho để tăng số lượng!');
        }

        $item->update(['quantity' => $newQty]);

        if ($diff > 0) {
            $bookDetail->decrement('quantity', $diff);
        } elseif ($diff < 0) {
            $bookDetail->increment('quantity', abs($diff));
        }

        $this->recalculateOrderTotal($item->order_id);

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function deleteItem(OrderItem $item)
    {
        $item->delete();
        $item->bookDetail->increment('quantity', $item->quantity);
        $this->recalculateOrderTotal($item->order_id);

        return back()->with('success', 'Đã xoá sản phẩm khỏi đơn');
    }

    public function checkout(Request $request, Order $order)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:255',
        ]);

        $order->update([
            'status' => 'PAID',
            'completed_date' => now(),
            'change_amount' => $request->amount_paid - $order->final_amount,
            'customer_name' => $request->customer_name ?: $order->customer_name,
            'phone_number' => $request->phone_number ?: $order->phone_number,
            'shipping_address' => $request->shipping_address ?: $order->shipping_address,
        ]);

        return redirect()->route('admin.counter.receipt', $order->id)
            ->with('success', "Đã thanh toán đơn hàng #$order->id");
    }

    private function recalculateOrderTotal($orderId)
    {
        $order = Order::findOrFail($orderId);

        $total = $order->items()
            ->withTrashed()
            ->get()
            ->sum(function ($item) {
                $price = $item->promotion_price ?? $item->price ?? 0;
                return $price * $item->quantity;
            });

        $order->update(['final_amount' => $total]);
    }
}
