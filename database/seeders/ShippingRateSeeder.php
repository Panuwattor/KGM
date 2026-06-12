<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['min_qty' => 1,  'max_qty' => 5,    'price' => 40],
            ['min_qty' => 6,  'max_qty' => 10,   'price' => 50],
            ['min_qty' => 11, 'max_qty' => 15,   'price' => 60],
            ['min_qty' => 16, 'max_qty' => 20,   'price' => 70],
            ['min_qty' => 21, 'max_qty' => null,  'price' => 80],
        ];

        foreach ($rates as $rate) {
            ShippingRate::firstOrCreate(
                ['min_qty' => $rate['min_qty']],
                $rate + ['is_active' => true]
            );
        }
    }
}
