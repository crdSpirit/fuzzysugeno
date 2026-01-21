<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupplierAdminSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Store::where('type', 'supplier')->first();

        if (!$supplier) {
            return;
        }

        // Cegah duplikasi admin
        $exists = User::where('email', 'admin@supplier.com')->exists();

        if (!$exists) {
            User::create([
                'name' => 'Supplier Admin',
                'email' => 'admin@supplier.com',
                'password' => Hash::make('password'),
                'store_id' => $supplier->id,
                'role' => 'supplier_admin',
            ]);
        }
    }
}
