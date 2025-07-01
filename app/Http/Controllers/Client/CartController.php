<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\BookDetail;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with([
                'items' => function ($query) {
                    $query->where('deleted', false);
                },
                'items.bookDetail'
            ])
            ->first();

        return view('client.cart.index', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_detail_id' => 'required|exists:book_details,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('cart.index')->with('error', 'Dữ liệu không hợp lệ');
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['created_date' => now()]
        );

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('book_detail_id', $request->book_detail_id)
            ->where('deleted', false)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'book_detail_id' => $request->book_detail_id,
                'quantity' => $request->quantity,
                'deleted' => false,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // Cập nhật số lượng sản phẩm
    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartItem::where('id', $id)
            ->whereHas('cart', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->firstOrFail();

        if ($request->query('action') === 'increase') {
            $cartItem->increment('quantity');
        } elseif ($request->query('action') === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        }

        $cartItem->touch(); // cập nhật updated_at
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    // Xóa một sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        $cartItem = CartItem::where('id', $id)
            ->whereHas('cart', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $cartItem->update(['deleted' => true]);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with(['items' => function ($q) {
                $q->where('deleted', false);
            }])
            ->first();

        if ($cart) {
            foreach ($cart->items as $item) {
                $item->update(['deleted' => true]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
