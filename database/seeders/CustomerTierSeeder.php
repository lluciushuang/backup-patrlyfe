<?php

namespace Database\Seeders;

use App\Models\CustomerTier;
use Illuminate\Database\Seeder;

class CustomerTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Level 1 - Eceran',
                'slug' => 'level-1-eceran',
                'price_level' => 1,
                'min_purchase' => 0,
            ],
            [
                'name' => 'Level 2 - Grosir Kecil',
                'slug' => 'level-2-grosir-kecil',
                'price_level' => 2,
                'min_purchase' => 5000000,
            ],
            [
                'name' => 'Level 3 - Grosir Menengah',
                'slug' => 'level-3-grosir-menengah',
                'price_level' => 3,
                'min_purchase' => 20000000,
            ],
            [
                'name' => 'Level 4 - Grosir Besar',
                'slug' => 'level-4-grosir-besar',
                'price_level' => 4,
                'min_purchase' => 50000000,
            ],
        ];

        foreach ($tiers as $tier) {
            CustomerTier::firstOrCreate(
                ['slug' => $tier['slug']],
                $tier
            );
        }
    }
}