<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'store_code' => 'TOKO-001',
                'store_name' => 'Bengkel Maju Jaya',
                'type' => 'customer',
                'owner_name' => 'Ahmad Fauzi',
                'phone' => '081234567890',
                'email' => 'majubengkel@gmail.com',
                'address' => 'Jl. Raya Kebon Jeruk No. 12',
                'city' => 'Jakarta',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
            ],
            [
                'store_code' => 'TOKO-002',
                'store_name' => 'Toko Ban Sentosa',
                'type' => 'customer',
                'owner_name' => 'Budi Santoso',
                'phone' => '082233445566',
                'email' => 'sentosaban@gmail.com',
                'address' => 'Jl. Diponegoro No. 45',
                'city' => 'Bandung',
                'latitude' => -6.914744,
                'longitude' => 107.609810,
            ],
            [
                'store_code' => 'TOKO-003',
                'store_name' => 'Dealer Motor Abadi',
                'type' => 'customer',
                'owner_name' => 'Rina Wijaya',
                'phone' => '081377889900',
                'email' => 'abadi.motor@gmail.com',
                'address' => 'Jl. Ahmad Yani No. 88',
                'city' => 'Surabaya',
                'latitude' => -7.250445,
                'longitude' => 112.768845,
            ],
            [
                'store_code' => 'TOKO-004',
                'store_name' => 'PT Ban Jaya Abadi',
                'type' => 'customer',
                'owner_name' => 'Jaya',
                'phone' => '081234567890123',
                'email' => 'admin@bja.id',
                'address' => 'Jalan Kijang I, RW 05, Gayamsari, Semarang, Central Java, 50248, Indonesia',
                'city' => 'Semarang',
                'latitude' => -6.996758,
                'longitude' => 110.446458,
            ],

        ];

        foreach ($stores as $store) {
            Store::updateOrCreate(
                ['store_code' => $store['store_code']],
                $store
            );
        }
    }
}
