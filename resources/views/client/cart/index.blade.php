@extends('client.layouts.app')

@section('content')
    <section
        style="min-height: 85vh; padding: 24px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%); position: relative; overflow: hidden;">
        <!-- Background 3D elements -->
        <div class="bg-3d-elements">
            <div class="floating-cube cube-1"></div>
            <div class="floating-cube cube-2"></div>
            <div class="floating-cube cube-3"></div>
        </div>

        <h2 class="cart-title">Giỏ hàng của bạn</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($cart && $cart->items->where('deleted', false)->count())
            <div class="cart-container">
                <div class="cart-header">
                    <div class="header-3d">
                        <span class="header-text">Sản phẩm trong giỏ</span>
                        <div class="header-glow"></div>
                    </div>
                </div>

                <div class="cart-table-wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr class="table-header-3d">
                                <th class="th-3d">#</th>
                                <th class="th-3d">Sản phẩm</th>
                                <th class="th-3d">Giá</th>
                                <th class="th-3d">Số lượng</th>
                                <th class="th-3d">Thành tiền</th>
                                <th class="th-3d">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1;
                            $total = 0; @endphp
                            @foreach ($cart->items->where('deleted', false) as $item)
                                @php
                                    $subtotal = $item->bookDetail->price * $item->quantity;
                                    $total += $subtotal;
                                @endphp
                                <tr class="cart-row-3d">
                                    <td class="td-3d">
                                        <span class="index-badge">{{ $index++ }}</span>
                                    </td>
                                    <td class="td-3d product-info">
                            <div class="product-card">
                                @php
                                    $book = $item->bookDetail->book ?? null;
                                    $mainImage = optional($book->images)->firstWhere('is_main', true)
                                        ?? optional($book->images)->where('deleted', 0)->first();
                                    $imageUrl = 'client/img/products/noimage.png';
                                    if ($mainImage && !empty($mainImage->url)) {
                                        $imageUrl = 'storage/' . $mainImage->url;
                                    }
                                @endphp

                                <div class="product-image-3d">
                                    <img src="{{ asset($imageUrl) }}" alt="Product Image">
                                    <div class="image-overlay"></div>
                                </div>

                                <a href="{{ route('product.show', $book->id ?? 0) }}" target="_blank" class="product-name-3d">
                                    {{ $book->name ?? '[Không xác định]' }}
                                </a>
                            </div>

                                    </td>
                                    <td class="td-3d price-3d">
                                        <span class="price-tag">{{ number_format($item->bookDetail->price, 0, ',', '.') }} ₫</span>
                                    </td>
                                    <td class="td-3d">
                                        <div class="quantity-controls-3d">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                class="quantity-form">
                                                @csrf
                                                <button type="submit"
                                                    formaction="{{ route('cart.update', $item->id) }}?action=decrease"
                                                    class="btn-3d btn-decrease">
                                                    <span>−</span>
                                                </button>
                                                <div class="quantity-display">
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                        max="50" readonly class="quantity-input-3d">
                                                </div>
                                                <button type="submit"
                                                    formaction="{{ route('cart.update', $item->id) }}?action=increase"
                                                    class="btn-3d btn-increase">
                                                    <span>+</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="td-3d subtotal-3d">
                                        <span class="subtotal-amount">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                                    </td>
                                    <td class="td-3d">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-3d btn-delete">
                                                <i class="fa fa-trash"></i>
                                                <span>Xóa</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary-3d">
                    <div class="total-display">
                        <div class="total-card">
                            <h3 class="total-label">Tổng cộng</h3>
                            <div class="total-amount">{{ number_format($total, 0, ',', '.') }} ₫</div>
                            <div class="total-glow"></div>
                        </div>
                    </div>
                    <div class="cart-actions-3d">
                        {{-- Nút thanh toán với link --}}
                        @if($cart->items->isNotEmpty())
                            <a href="{{ route('checkout.form') }}" class="btn-3d btn-checkout">
                                <span>Thanh toán</span>
                                <div class="btn-shine"></div>
                            </a>
                        @else
                            <a href="javascript:void(0);" class="btn-3d btn-checkout" style="pointer-events: none; opacity: 0.6;">
                                <span>Thanh toán</span>
                                <div class="btn-shine"></div>
                            </a>
                        @endif

                        {{-- Nút xóa hết --}}
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-3d btn-clear">
                                <span>Xóa hết</span>
                                <div class="btn-shine"></div>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @else
            <div class="empty-cart-3d">
                <div class="empty-icon">🛒</div>
                <p>Giỏ hàng của bạn đang trống</p>
            </div>
        @endif
    </section>

    <style>
        * {
            box-sizing: border-box;
        }

        /* Background 3D Elements */
        .bg-3d-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-cube {
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            animation: float 6s ease-in-out infinite;
            transform-style: preserve-3d;
        }

        .cube-1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
            transform: perspective(1000px) rotateX(45deg) rotateY(45deg);
        }

        .cube-2 {
            top: 60%;
            right: 15%;
            animation-delay: -2s;
            transform: perspective(1000px) rotateX(-30deg) rotateY(60deg);
        }

        .cube-3 {
            bottom: 20%;
            left: 70%;
            animation-delay: -4s;
            transform: perspective(1000px) rotateX(60deg) rotateY(-45deg);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) perspective(1000px) rotateX(45deg) rotateY(45deg);
            }

            50% {
                transform: translateY(-20px) perspective(1000px) rotateX(45deg) rotateY(45deg);
            }
        }

        /* Title Styling */
        .cart-title {
            margin: 0 0 40px 0;
            text-align: center;
            font-size: 48px;
            font-weight: 700;
            background: linear-gradient(45deg, #374151, #1f2937);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: perspective(500px) rotateX(10deg);
            position: relative;
            z-index: 10;
        }

        /* Main Cart Container */
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            transform: perspective(1000px) rotateX(5deg);
            transition: all 0.5s ease;
            position: relative;
            z-index: 5;
        }

        .cart-container:hover {
            transform: perspective(1000px) rotateX(0deg) translateY(-10px);
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        /* Cart Header */
        .cart-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .header-3d {
            position: relative;
            display: inline-block;
            transform: perspective(500px) rotateX(20deg);
        }

        .header-text {
            font-size: 28px;
            font-weight: 600;
            color: #374151;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .header-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 20px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.2), transparent);
            border-radius: 10px;
            filter: blur(10px);
            opacity: 0.7;
        }

        /* Table Wrapper */
        .cart-table-wrapper {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        /* Table Styling */
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
        }

        .table-header-3d {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            position: relative;
            overflow: hidden;
        }

        .table-header-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .th-3d {
            padding: 20px 15px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        /* Table Rows */
        .cart-row-3d {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            position: relative;
        }

        .cart-row-3d:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateZ(10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .td-3d {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            vertical-align: middle;
            position: relative;
        }

        /* Index Badge */
        .index-badge {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(238, 90, 36, 0.2);
            transform: perspective(100px) rotateX(15deg);
        }

        /* Product Info */
        .product-card {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image-3d {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            transform: perspective(200px) rotateY(15deg);
            transition: all 0.3s ease;
        }

        .product-image-3d:hover {
            transform: perspective(200px) rotateY(0deg) scale(1.1);
        }

        .product-image-3d img {
            width: 100%;
            max-width: 80px;
            height: auto;
            aspect-ratio: 3/4;
            object-fit: contain;
            background: #fff;
            border-radius: 8px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.08), rgba(124, 58, 237, 0.08));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-image-3d:hover .image-overlay {
            opacity: 1;
        }

        .product-name-3d {
            text-decoration: none;
            color: #4f46e5;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
        }

        .product-name-3d:hover {
            color: #7c3aed;
            text-shadow: 0 2px 4px rgba(124, 58, 237, 0.2);
            transform: translateY(-2px);
        }

        /* Price Styling */
        .price-tag,
        .subtotal-amount {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.2);
            transform: perspective(100px) rotateX(10deg);
            transition: all 0.3s ease;
        }

        .price-tag:hover,
        .subtotal-amount:hover {
            transform: perspective(100px) rotateX(0deg) scale(1.05);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.25);
        }

        /* Quantity Controls */
        .quantity-controls-3d {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .quantity-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-3d {
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transform: perspective(200px) rotateX(15deg);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-3d:hover {
            transform: perspective(200px) rotateX(0deg) translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-3d:active {
            transform: perspective(200px) rotateX(0deg) translateY(-1px);
        }

        .btn-decrease,
        .btn-increase {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .btn-decrease {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-increase {
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
        }

        .quantity-display {
            position: relative;
        }

        .quantity-input-3d {
            width: 60px;
            height: 40px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            font-weight: 600;
            font-size: 16px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            transform: perspective(100px) rotateX(10deg);
        }

        /* Delete Button */
        .btn-delete {
            padding: 10px 16px;
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Cart Summary */
        .cart-summary-3d {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            padding-top: 30px;
            border-top: 2px solid rgba(102, 126, 234, 0.2);
        }

        .total-display {
            flex: 1;
        }

        .total-card {
            position: relative;
            background: linear-gradient(135deg, #1f2937, #374151);
            padding: 25px 35px;
            border-radius: 20px;
            color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            transform: perspective(500px) rotateX(10deg);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .total-card:hover {
            transform: perspective(500px) rotateX(0deg) scale(1.05);
        }

        .total-label {
            font-size: 18px;
            margin: 0 0 10px 0;
            opacity: 0.8;
        }

        .total-amount {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .total-glow {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.2), transparent);
            animation: glow 2s infinite;
        }

        @keyframes glow {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Action Buttons */
        .cart-actions-3d {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-checkout,
        .btn-clear {
            padding: 15px 30px;
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }

        .btn-checkout {
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-clear {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .btn-3d:hover .btn-shine {
            left: 100%;
        }

        /* Empty Cart */
        .empty-cart-3d {
            text-align: center;
            color: #374151;
            max-width: 400px;
            margin: 100px auto;
            padding: 50px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            transform: perspective(500px) rotateX(10deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
            display: block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-20px);
            }

            60% {
                transform: translateY(-10px);
            }
        }

        .empty-cart-3d p {
            font-size: 24px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Alert Success */
        .alert-success {
            padding: 15px 25px;
            background: linear-gradient(45deg, #10b981, #059669);
            border: none;
            border-radius: 12px;
            color: white;
            text-align: center;
            margin: 0 auto 30px;
            max-width: 500px;
            font-weight: 600;
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.2);
            transform: perspective(200px) rotateX(10deg);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: perspective(200px) rotateX(10deg) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: perspective(200px) rotateX(10deg) translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-container {
                padding: 20px;
                transform: none;
            }

            .cart-title {
                font-size: 32px;
            }

            .cart-summary-3d {
                flex-direction: column;
                align-items: stretch;
            }

            .cart-actions-3d {
                justify-content: center;
            }

            .product-card {
                flex-direction: column;
                text-align: center;
            }

            .quantity-controls-3d {
                transform: scale(0.9);
            }

            .btn-3d {
                transform: none;
            }

            .btn-3d:hover {
                transform: translateY(-2px);
            }
        }
    </style>
@endsection
