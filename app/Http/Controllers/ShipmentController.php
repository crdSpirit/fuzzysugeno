<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipment;

class ShipmentController extends Controller
{
    /**
     * =====================================
     * DETAIL SHIPMENT + HASIL FUZZY
     * =====================================
     */
    public function show($orderId)
    {
        $order = Order::with(['items.sparepart', 'shipment'])
            ->where('store_id', auth()->user()->store_id)
            ->findOrFail($orderId);

        return view('shipments.show', compact('order'));
    }

    /**
     * =====================================
     * HITUNG FUZZY & UPDATE SHIPMENT
     * =====================================
     */
    public function process($orderId)
    {
        // ============================
        // AMBIL ORDER (TANPA FILTER store_id)
        // ============================
        $order = Order::with(['items.sparepart', 'shipment'])
            ->findOrFail($orderId);

        if (!$order->shipment) {
            abort(404, 'Shipment tidak ditemukan');
        }

        // ============================
        // INPUT FUZZY
        // ============================
        $distance = $order->shipment->distance_km;

        /**
         * Ambil fuzzy_weight_value tertinggi
         * (ban terberat menentukan pengiriman)
         */
        $barangValue = $order->items
            ->max(fn($item) => $item->sparepart->fuzzy_weight_value);

        // ============================
        // HITUNG FUZZY SUGENO
        // ============================
        $fuzzyScore = $this->fuzzySugeno($barangValue, $distance);
        $decision = $this->decision($fuzzyScore);

        // ============================
        // SIMPAN HASIL
        // ============================
        $order->shipment->update([
            'fuzzy_score' => $fuzzyScore,
            'delivery_decision' => $decision,
        ]);

        // ============================
        // UPDATE STATUS ORDER
        // ============================
        $order->update([
            'status' => 'processed',
        ]);

        // ============================
        // REDIRECT KE DETAIL ORDER SUPPLIER
        // ============================
        return redirect()
            ->route('supplier.orders.show', $order->id)
            ->with('success', 'Pengiriman berhasil diproses (Fuzzy Sugeno)');
    }

    /**
     * =====================================
     * FUZZY SUGENO (BARANG + JARAK) - FIXED
     * =====================================
     */
    private function fuzzySugeno($barang, $jarak)
    {
        /**
         * barang:
         * 1 = ringan
         * 2 = sedang
         * 3 = berat
         */

        // -------------------------
        // FUZZIFIKASI BARANG
        // -------------------------
        $ringan = ($barang == 1) ? 1 : (($barang == 2) ? 0.5 : 0);
        $sedang = ($barang == 2) ? 1 : 0;
        $berat = ($barang == 3) ? 1 : (($barang == 2) ? 0.5 : 0);

        // -------------------------
        // FUZZIFIKASI JARAK (KM)
        // -------------------------
        $dekat = max(0, min(1, (10 - $jarak) / 10));
        $jauh = max(0, min(1, ($jarak - 20) / 30));

        // -------------------------
        // RULE BASE SUGENO
        // -------------------------
        $r1 = min($ringan, $dekat); // cepat
        $z1 = 90;

        $r2 = min($sedang, $dekat); // normal
        $z2 = 70;

        $r3 = min($berat, $dekat); // normal
        $z3 = 60;

        $r4 = min($ringan, $jauh); // normal
        $z4 = 65;

        $r5 = min($berat, $jauh); // lambat
        $z5 = 40;

        // -------------------------
        // DEFUZZIFIKASI (AMAN)
        // -------------------------
        $numerator =
            ($r1 * $z1) +
            ($r2 * $z2) +
            ($r3 * $z3) +
            ($r4 * $z4) +
            ($r5 * $z5);

        $denominator = $r1 + $r2 + $r3 + $r4 + $r5;

        // fallback (ANTI 0)
        if ($denominator == 0) {
            return 60; // NORMAL
        }

        return round($numerator / $denominator, 2);
    }

    /**
     * =====================================
     * KEPUTUSAN PENGIRIMAN
     * =====================================
     */
    private function decision($nilai)
    {
        if ($nilai >= 75) {
            return 'Cepat';
        }

        if ($nilai >= 55) {
            return 'Normal';
        }

        return 'Lambat';
    }
}
