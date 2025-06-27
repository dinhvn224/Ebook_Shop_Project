<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DashboardTestSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo user admin duy nhất
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'phone_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'birth_date' => fake()->date(),
                'avatar_url' => fake()->imageUrl(),
                'role' => 'ADMIN',
                'is_active' => true,
            ]
        );

        // Tạo 10 sách và mỗi sách có 1 BookDetail
        $books = Book::factory(10)->create();

        foreach ($books as $book) {
            BookDetail::factory()->create([
                'book_id' => $book->id,
                'language' => fake()->randomElement(['Tiếng Việt', 'English']),
                'quantity' => rand(1, 20),
                'price' => rand(80000, 200000),
                'promotion_price' => rand(50000, 150000),
                'is_active' => true,
            ]);
        }

        // Tạo 50 đơn hàng trải đều trong 6 tháng
        for ($i = 0; $i < 50; $i++) {
            $status = fake()->randomElement(['PENDING', 'PAID', 'CANCELLED', 'COMPLETED']);
            $date = now()->subMonths(rand(0, 5))->subDays(rand(0, 28));

            $order = Order::create([
                'user_id' => $admin->id,
                'customer_name' => fake()->name(),
                'shipping_address' => fake()->address(),
                'phone_number' => fake()->phoneNumber(),
                'final_amount' => 0,
                'change_amount' => 0,
                'status' => $status,
                'payment_method' => fake()->randomElement(['CASH', 'COD', 'QR_PAY']),
                'order_date' => $date,
                'created_at' => $date,
                'updated_at' => $date,
                'voucher_id' => null,
                'voucher_discount' => 0
            ]);

            $total = 0;
            $itemsCount = rand(1, 3);

            for ($j = 0; $j < $itemsCount; $j++) {
                $variant = BookDetail::inRandomOrder()->first();
                $qty = rand(1, 3);
                $price = $variant->promotion_price ?? $variant->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'ebook_variant_id' => $variant->id,
                    'price' => $variant->price,
                    'promotion_price' => $variant->promotion_price,
                    'quantity' => $qty,
                ]);

                $total += $price * $qty;

                $variant->decrement('quantity', $qty);
            }

            $order->update([
                'final_amount' => $total,
                'change_amount' => fake()->numberBetween(0, 10000),
            ]);
        }
    }
}
