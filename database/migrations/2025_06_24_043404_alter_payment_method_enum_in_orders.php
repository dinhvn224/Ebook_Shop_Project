<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm giá trị 'COUNTER' vào enum
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('CASH', 'COD', 'QR_PAY', 'COUNTER', 'BANK')");
    }

    public function down(): void
    {
        // Rollback về enum ban đầu nếu cần
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('CASH', 'COD', 'QR_PAY', 'COUNTER')");
    }
};
