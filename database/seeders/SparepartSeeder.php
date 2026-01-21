<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // =====================
            // BAN DEPAN MATIC
            // =====================
            [
                'name' => 'Ban Depan Beat',
                'brand' => 'IRC',
                'size' => '80/90-14',
                'type' => 'tubeless',
                'weight' => 3.20,
                'fuzzy_weight_value' => 1,
                'stock' => 100,
                'price' => 285000,
            ],
            [
                'name' => 'Ban Depan Vario',
                'brand' => 'FDR',
                'size' => '90/80-14',
                'type' => 'tubeless',
                'weight' => 3.50,
                'fuzzy_weight_value' => 1,
                'stock' => 80,
                'price' => 310000,
            ],

            // =====================
            // BAN BELAKANG MATIC
            // =====================
            [
                'name' => 'Ban Belakang Beat',
                'brand' => 'IRC',
                'size' => '90/90-14',
                'type' => 'tubeless',
                'weight' => 4.80,
                'fuzzy_weight_value' => 2,
                'stock' => 70,
                'price' => 335000,
            ],
            [
                'name' => 'Ban Belakang NMAX',
                'brand' => 'Michelin',
                'size' => '130/70-13',
                'type' => 'tubeless',
                'weight' => 6.50,
                'fuzzy_weight_value' => 2,
                'stock' => 60,
                'price' => 685000,
            ],

            // =====================
            // BAN BEBEK / SPORT
            // =====================
            [
                'name' => 'Ban Depan Supra X',
                'brand' => 'FDR',
                'size' => '70/90-17',
                'type' => 'tubetype',
                'weight' => 3.00,
                'fuzzy_weight_value' => 1,
                'stock' => 90,
                'price' => 260000,
            ],
            [
                'name' => 'Ban Belakang Supra X',
                'brand' => 'FDR',
                'size' => '80/90-17',
                'type' => 'tubetype',
                'weight' => 4.20,
                'fuzzy_weight_value' => 2,
                'stock' => 75,
                'price' => 295000,
            ],

            // =====================
            // BAN SPORT / TRAIL
            // =====================
            [
                'name' => 'Ban Depan CBR 150',
                'brand' => 'Pirelli',
                'size' => '100/80-17',
                'type' => 'tubeless',
                'weight' => 5.80,
                'fuzzy_weight_value' => 2,
                'stock' => 40,
                'price' => 720000,
            ],
            [
                'name' => 'Ban Belakang CBR 150',
                'brand' => 'Pirelli',
                'size' => '130/70-17',
                'type' => 'tubeless',
                'weight' => 7.20,
                'fuzzy_weight_value' => 3,
                'stock' => 35,
                'price' => 890000,
            ],
            [
                'name' => 'Ban Trail CRF',
                'brand' => 'Corsa',
                'size' => '120/90-18',
                'type' => 'tubetype',
                'weight' => 8.50,
                'fuzzy_weight_value' => 3,
                'stock' => 30,
                'price' => 560000,
            ],
        ];

        foreach ($data as $item) {
            Sparepart::create($item);
        }
    }
}
