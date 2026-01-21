<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $toko1 = Store::where('store_code', 'TOKO-001')->first();
        $toko2 = Store::where('store_code', 'TOKO-002')->first();
        $toko3 = Store::where('store_code', 'TOKO-003')->first();

        $users = [
            // =====================
            // TOKO 1
            // =====================
            [
                'name' => 'Admin Bengkel Maju Jaya',
                'email' => 'admin@majubengkel.com',
                'password' => Hash::make('password'),
                'store_id' => $toko1?->id,
                'role' => 'customer_admin',
            ],
            [
                'name' => 'Staff Bengkel Maju Jaya',
                'email' => 'staff@majubengkel.com',
                'password' => Hash::make('password'),
                'store_id' => $toko1?->id,
                'role' => 'customer_staff',
            ],

            // =====================
            // TOKO 2
            // =====================
            [
                'name' => 'Admin Toko Ban Sentosa',
                'email' => 'admin@sentosaban.com',
                'password' => Hash::make('password'),
                'store_id' => $toko2?->id,
                'role' => 'customer_admin',
            ],

            // =====================
            // TOKO 3
            // =====================
            [
                'name' => 'Admin Dealer Motor Abadi',
                'email' => 'admin@abadi.com',
                'password' => Hash::make('password'),
                'store_id' => $toko3?->id,
                'role' => 'customer_admin',
            ],
        ];

        foreach ($users as $user) {
            if ($user['store_id']) {
                User::updateOrCreate(
                    ['email' => $user['email']],
                    $user
                );
            }
        }
    }
}
