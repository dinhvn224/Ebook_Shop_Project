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
                ->with(['items.bookDetail'])
                ->first();

    // Luôn tạo collection $cartItems, dù $cart có null hay không
    $cartItems = $cart
        ? $cart->items->where('deleted', false)
        : collect();

    // Truyền cả $cart và $cartItems vào view
    return view('client.cart.index', compact('cart', 'cartItems'));
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

    // Chuyển hướng tới giỏ hàng với thông báo thành công
    return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
}


    // Cập nhật số lượng sản phẩm
    public function updateQuantity(Request $request, $id)
    {
        // Tìm sản phẩm trong giỏ hàng
        $cartItem = CartItem::findOrFail($id);

        // Kiểm tra hành động tăng hoặc giảm số lượng
        if ($request->query('action') == 'increase') {
            $cartItem->quantity += 1;
        } elseif ($request->query('action') == 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
        }

        // Cập nhật thời gian sửa đổi
        $cartItem->updated_at = now();
        $cartItem->save();

        // Quay lại trang giỏ hàng với thông báo thành công
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    // Xóa một sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        // Tìm sản phẩm cần xóa
        $cartItem = CartItem::findOrFail($id);
        // Đánh dấu sản phẩm là đã xóa
        $cartItem->deleted = true;
        $cartItem->save();

        // Quay lại trang giỏ hàng với thông báo thành công
        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', Auth::id())->first();

        if ($cart) {
            // Duyệt qua tất cả các sản phẩm trong giỏ và đánh dấu là đã xóa
            foreach ($cart->items as $item) {
                $item->deleted = true;
                $item->save();
            }
        }

        // Quay lại trang giỏ hàng với thông báo thành công
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
