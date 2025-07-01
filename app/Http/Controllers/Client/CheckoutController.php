<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BookDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // Hiển thị form thanh toán
    public function showForm()
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->with(['items' => function ($query) {
                        $query->where('deleted', false);
                    }, 'items.bookDetail'])
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        return view('client.checkout.form', compact('cart'));
    }

    // Xử lý thanh toán
    public function processOrder(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        // Lấy giỏ hàng
        $cart = Cart::where('user_id', Auth::id())
                    ->with(['items' => function ($query) {
                        $query->where('deleted', false);
                    }, 'items.bookDetail'])
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        try {
            DB::beginTransaction();

            $amount = 0;

            // Kiểm tra tồn kho và tính tổng
            foreach ($cart->items as $item) {
                $detail = BookDetail::findOrFail($item->book_detail_id);
                // Kiểm tra số lượng tồn kho
                if ($detail->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$detail->book->name} chỉ còn {$detail->quantity} cuốn. Bạn chọn {$item->quantity}.");
                }

                $price = $detail->promotion_price ?? $detail->price;
                $amount += $price * $item->quantity;
            }

            $ship_amount = 30000;
            $total_amount = $amount + $ship_amount;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'shipping_address' => $request->shipping_address,
                'phone_number' => $request->phone_number,
                'amount' => $amount,
                'total_amount' => $total_amount,
                'final_amount' => $total_amount,
                'ship_amount' => $ship_amount,
                'change_amount' => 0,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'order_date' => Carbon::now(),
            ]);

            // Thêm sản phẩm vào order_items và trừ tồn kho
            foreach ($cart->items as $item) {
                $detail = BookDetail::findOrFail($item->book_detail_id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'ebook_variant_id' => $item->book_detail_id,
                    'price' => $detail->price,
                    'promotion_price' => $detail->promotion_price,
                    'quantity' => $item->quantity,
                ]);

                // Trừ tồn kho
                $detail->decrement('quantity', $item->quantity);

                // Đánh dấu sản phẩm đã được thanh toán
                $item->deleted = true;
                $item->save();
            }

            DB::commit();

            return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
}
