<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CustomerTier;
use App\Models\CustomerTierAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@partlyfe.com'],
            [
                'name' => 'Admin Partlyfe',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jakarta, Indonesia',
            ]
        );

        $rizal = User::firstOrCreate(
            ['email' => 'rizal.h@email.com'],
            [
                'name' => 'Rizal Hermawan',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567890',
                'address' => 'Jl. Rungkut Industri No. 45, Kota Surabaya',
            ]
        );

        $budi = User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '082345678901',
                'address' => 'Jl. Sudirman No. 10, Jakarta Pusat',
            ]
        );

        // Assign default customer tiers to customers (only if no assignments exist)
        $retailTier = CustomerTier::where('slug', 'retail-b2c')->first();
        if ($retailTier) {
            CustomerTierAssignment::firstOrCreate(
                ['user_id' => $rizal->id, 'customer_tier_id' => $retailTier->id],
                ['assigned_at' => now(), 'expires_at' => null]
            );
            CustomerTierAssignment::firstOrCreate(
                ['user_id' => $budi->id, 'customer_tier_id' => $retailTier->id],
                ['assigned_at' => now(), 'expires_at' => null]
            );
        }
    }
}