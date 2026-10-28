<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    private function komerce()
    {
        return Http::withHeaders([
            'key' => config('services.komerce_rajaongkir.key'),
        ]);
    }

    public function provinces()
    {
        $res = $this->komerce()
            ->get(config('services.komerce_rajaongkir.url') . '/destination/province');

        return response()->json($res->json()['data'] ?? []);
    }

    public function cities($provinceId)
    {
        $res = $this->komerce()
            ->get(config('services.komerce_rajaongkir.url') . "/destination/city/{$provinceId}");

        return response()->json($res->json()['data'] ?? []);
    }

    public function districts($cityId)
    {
        $res = $this->komerce()
            ->get(config('services.komerce_rajaongkir.url') . "/destination/district/{$cityId}");

        return response()->json($res->json()['data'] ?? []);
    }

    public function subdistricts($districtId)
    {
        $res = $this->komerce()
            ->get(config('services.komerce_rajaongkir.url') . "/destination/sub-district/{$districtId}");

        return response()->json($res->json()['data'] ?? []);
    }
}
