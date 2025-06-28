<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Order;
use App\Models\OrderItem;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo người dùng
        $users = User::factory(5)->create();

        // Tạo sách và biến thể sách
        $books = Book::factory(5)->create();

        $bookDetails = collect();
        foreach ($books as $book) {
            $details = BookDetail::factory(rand(1, 3))->create([
                'book_id' => $book->id,
            ]);
            $bookDetails = $bookDetails->merge($details);
        }

        // Tạo đơn hàng
        foreach ($users as $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => fake()->name(),
                'shipping_address' => fake()->address(),
                'phone_number' => fake()->phoneNumber(),
                'final_amount' => 120000,
                'status' => 'PAID',
                'payment_method' => 'COD',
                'order_date' => now(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'ebook_variant_id' => $bookDetails->random()->id,
                'quantity' => 2,
                'price' => 60000,
                'promotion_price' => 50000,
            ]);
        }
    }
}
