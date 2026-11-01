<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;

class DatatablesController extends Controller
{
    public function shipments(Request $request)
    {
        $orders = Order::with(['store', 'shipment'])
            ->orderByRaw("
                CASE (
                    SELECT delivery_decision
                    FROM shipments
                    WHERE shipments.order_id = orders.id
                )
                    WHEN 'Cepat' THEN 1
                    WHEN 'Normal' THEN 2
                    WHEN 'Lambat' THEN 3
                    ELSE 4
                END ASC
            ")
            ->orderByRaw("
                (
                    SELECT fuzzy_score
                    FROM shipments
                    WHERE shipments.order_id = orders.id
                ) DESC
            ")
            ->orderBy('orders.created_at', 'ASC');

        // 1. Filter rentang tanggal (Start Date & End Date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $orders->whereBetween('orders.order_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $orders->whereDate('orders.order_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $orders->whereDate('orders.order_date', '<=', $request->end_date);
        }

        // 2. Filter Keputusan Pengiriman (Cepat / Normal / Lambat)
        if ($request->filled('delivery_decision')) {
            $decision = $request->input('delivery_decision');
            $orders->whereHas('shipment', function ($q) use ($decision) {
                $q->where('delivery_decision', $decision);
            });
        }

        // 3. Filter Status Order (draft, submitted, processed, completed)
        if ($request->filled('status')) {
            $orders->where('status', $request->input('status'));
        }

        // 4. Filter Toko Tertentu
        if ($request->filled('store_id')) {
            $orders->where('store_id', $request->input('store_id'));
        }

        return DataTables::of($orders)
            ->addIndexColumn()
            ->setRowId('id')
            ->addColumn('store_name', fn($order) => $order->store->store_name ?? '-')
            ->addColumn('city', fn($order) => $order->store->city ?? '-')
            ->addColumn('status_badge', function ($order) {
                $statusColor = match ($order->status) {
                    'draft' => 'secondary',
                    'submitted' => 'warning',
                    'processed' => 'info',
                    'completed' => 'success',
                    default => 'secondary',
                };
                return '<span class="badge bg-'.$statusColor.'">'.ucfirst($order->status).'</span>';
            })
            ->addColumn('decision_badge', function ($order) {
                $decision = $order->shipment->delivery_decision ?? null;
                if (!$decision) return '<span class="badge bg-secondary">Belum Diproses</span>';

                $decisionColor = match ($decision) {
                    'Cepat' => 'success',
                    'Normal' => 'info',
                    'Lambat' => 'danger',
                    default => 'secondary',
                };
                return '<span class="badge bg-'.$decisionColor.'">'.$decision.'</span>';
            })
            ->addColumn('fuzzy_score', fn($order) => $order->shipment->fuzzy_score ?? '-')
            ->addColumn('shipping_cost', fn($order) => $order->shipment ? 'Rp ' . number_format($order->shipment->shipping_cost, 0, ',', '.') : '-')
            ->addColumn('action', function ($order) {
                if ($order->shipment && !is_null($order->shipment->fuzzy_score)) {
                    return '<span class="text-muted small">Selesai</span>';
                }
                return '<button class="btn btn-sm btn-primary process-btn" data-id="'.$order->id.'">Proses Fuzzy</button>';
            })
            ->rawColumns(['status_badge', 'decision_badge', 'action'])
            ->toJson();
    }

    public function fuzzyCounts($orderId, Request $request)
    {
        $order = Order::with(['items.sparepart', 'shipment'])
            ->findOrFail($orderId);

        if (!$order->shipment) {
            return response()->json([
                'status' => 404,
                'message' => 'Shipment tidak ditemukan'
            ], 404);
        }

        // INPUT FUZZY
        $distance = $order->shipment->distance_km;
        $barangValue = $order->items->max(fn($item) => $item->sparepart->fuzzy_weight_value ?? 1);

        // HITUNG FUZZY SUGENO
        $fuzzyScore = $this->fuzzySugeno($barangValue, $distance);
        $decision = $this->decision($fuzzyScore);

        // SIMPAN HASIL
        $order->shipment->update([
            'fuzzy_score' => $fuzzyScore,
            'delivery_decision' => $decision,
        ]);

        // UPDATE STATUS ORDER
        $order->update([
            'status' => 'processed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order #' . $order->order_code . ' berhasil diproses!',
            'fuzzy_score' => $fuzzyScore,
            'decision' => $decision,
            'status' => 'processed'
        ]);
    }

    public function fuzzySugeno($barang, $jarak)
    {
        $ringan = ($barang == 1) ? 1 : (($barang == 2) ? 0.5 : 0);
        $sedang = ($barang == 2) ? 1 : 0;
        $berat = ($barang == 3) ? 1 : (($barang == 2) ? 0.5 : 0);

        $dekat = max(0, min(1, (10 - $jarak) / 10));
        $jauh = max(0, min(1, ($jarak - 20) / 30));

        $r1 = min($ringan, $dekat); $z1 = 90;
        $r2 = min($sedang, $dekat); $z2 = 70;
        $r3 = min($berat, $dekat);  $z3 = 60;
        $r4 = min($ringan, $jauh);  $z4 = 65;
        $r5 = min($berat, $jauh);   $z5 = 40;

        $numerator = ($r1 * $z1) + ($r2 * $z2) + ($r3 * $z3) + ($r4 * $z4) + ($r5 * $z5);
        $denominator = $r1 + $r2 + $r3 + $r4 + $r5;

        if ($denominator == 0) {
            return 60;
        }

        return round($numerator / $denominator, 2);
    }

    public function decision($nilai)
    {
        if ($nilai >= 75) return 'Cepat';
        if ($nilai >= 55) return 'Normal';
        return 'Lambat';
    }
}
