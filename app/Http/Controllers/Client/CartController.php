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
        $cart = Cart::where('user_id', Auth::id())->with(['items.bookDetail'])->first();
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
        return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ'], 400);
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
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        CartItem::create([
            'cart_id' => $cart->id,
            'book_detail_id' => $request->book_detail_id,
            'quantity' => $request->quantity,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted' => false,
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ hàng!']);
}


    // Cập nhật số lượng sản phẩm
    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        if ($request->query('action') == 'increase') {
            $cartItem->quantity += 1;
        } elseif ($request->query('action') == 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
        }

        $cartItem->updated_at = now();
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    // Xóa một sản phẩm
    public function removeFromCart($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->deleted = true;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            foreach ($cart->items as $item) {
                $item->deleted = true;
                $item->save();
            }
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
