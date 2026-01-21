<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class SupplierStoreSeeder extends Seeder
{
    public function run(): void
    {
        // Cegah duplikasi supplier
        $supplier = Store::where('type', 'supplier')->first();

        if (!$supplier) {
            Store::create([
                'store_code' => 'SUP-001',
                'store_name' => 'PT Supplier Ban Nusantara',
                'type' => 'supplier',
                'owner_name' => 'Direktur Utama',
                'phone' => '0218889999',
                'email' => 'supplier@ban-nusantara.co.id',
                'address' => 'Jl. Industri Raya No. 1',
                'city' => 'Jakarta',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
            ]);
        }
    }
}
