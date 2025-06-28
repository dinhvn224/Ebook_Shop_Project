<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('shipping_address');
            $table->string('phone_number', 20);
            $table->decimal('final_amount', 10, 2);
            $table->enum('status', ['PENDING', 'PAID', 'COMPLETED', 'CANCELLED', 'REFUNDED']);
            $table->enum('payment_method', ['CASH', 'COD', 'QR_PAY', 'COUNTER']);
            $table->timestamp('order_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
