<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // 5 mã giảm tiền cố định: 10k, 20k,... 50k
        for ($i = 1; $i <= 5; $i++) {
            Voucher::create([
                'code' => 'FIX' . str_pad($i, 2, '0', STR_PAD_LEFT), // FIX01 → FIX05
                'type' => 'fixed',
                'value' => $i * 10000,
                'usage_limit' => 100,
                'start_at' => $now->subDays(1),
                'expires_at' => $now->addDays(15),
                'is_active' => true,
            ]);
        }

        // 5 mã giảm phần trăm: SALE5 → SALE25 (giảm đến 30k)
        for ($i = 1; $i <= 5; $i++) {
            $percent = $i * 5;
            Voucher::create([
                'code' => 'SALE' . $percent,
                'type' => 'percent',
                'value' => $percent,
                'max_discount' => 30000,
                'usage_limit' => null,
                'start_at' => $now->subDays(2),
                'expires_at' => $now->addDays(20),
                'is_active' => true,
            ]);
        }
    }
}
