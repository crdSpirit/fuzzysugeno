<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Sparepart;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * ============================
     * LIST ORDER
     * ============================
     */
    public function index()
    {
        $orders = Order::with('shipment')
            ->where('store_id', auth()->user()->store_id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * ============================
     * BUAT ORDER BARU
     * ============================
     */
    public function create()
    {
        $spareparts = Sparepart::where('stock', '>', 0)->get();
        return view('orders.create', compact('spareparts'));
    }

    /**
     * ============================
     * SIMPAN ORDER HEADER
     * ============================
     */
    public function store()
    {
        $order = Order::create([
            'order_code' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
            'store_id' => auth()->user()->store_id,
            'order_date' => now(),
            'status' => 'draft',
            'total_weight' => 0,
        ]);

        return redirect()->route('orders.edit', $order->id);
    }

    /**
     * ============================
     * EDIT ORDER
     * ============================
     */
    public function edit($id)
    {
        $order = Order::with('items.sparepart', 'shipment')->findOrFail($id);

        if ($order->store_id !== auth()->user()->store_id) {
            abort(403);
        }

        $spareparts = Sparepart::where('stock', '>', 0)->get();

        return view('orders.edit', compact('order', 'spareparts'));
    }

    /**
     * ============================
     * TAMBAH ITEM
     * ============================
     */
    public function addItem(Request $request, $orderId)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($orderId);
        $sparepart = Sparepart::findOrFail($request->sparepart_id);

        if ($request->quantity > $sparepart->stock) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'quantity' => $request->quantity,
            'unit_weight' => $sparepart->weight,
            'total_weight' => $sparepart->weight * $request->quantity,
        ]);

        $this->recalculateTotalWeight($order->id);

        return back()->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * ============================
     * PREVIEW ONGKIR & JARAK
     * ============================
     */
    public function previewCheckout($orderId)
    {
        $order = Order::with('items', 'store')->findOrFail($orderId);

        if ($order->items->isEmpty()) {
            return back()->with('error', 'Order belum memiliki item');
        }

        $supplier = Store::where('type', 'supplier')->first();
        if (!$supplier || !$supplier->latitude || !$supplier->longitude) {
            return back()->with('error', 'Lokasi supplier belum ditentukan');
        }

        if (!$order->store->latitude || !$order->store->longitude) {
            return back()->with('error', 'Lokasi toko belum ditentukan');
        }

        $distanceKm = $this->calculateDistanceKm(
            $supplier->latitude,
            $supplier->longitude,
            $order->store->latitude,
            $order->store->longitude
        );

        $ongkir = round($distanceKm * 20000);

        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'distance_km' => round($distanceKm, 2),
                'shipping_cost' => $ongkir,
            ]
        );

        return redirect()
            ->route('orders.edit', $order->id)
            ->with('success', 'Jarak & ongkir berhasil dihitung');
    }

    /**
     * ============================
     * CHECKOUT FINAL
     * ============================
     */
    public function finalCheckout($orderId)
    {
        DB::transaction(function () use ($orderId) {

            $order = Order::with('items.sparepart', 'shipment')->findOrFail($orderId);

            if (!$order->shipment) {
                throw new \Exception('Hitung ongkir terlebih dahulu');
            }

            $order->update([
                'status' => 'submitted',
            ]);

            foreach ($order->items as $item) {
                $item->sparepart->decrement('stock', $item->quantity);
            }
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order berhasil di-checkout');
    }

    /**
     * ============================
     * HITUNG TOTAL BERAT
     * ============================
     */
    private function recalculateTotalWeight($orderId)
    {
        $total = OrderItem::where('order_id', $orderId)->sum('total_weight');

        Order::where('id', $orderId)->update([
            'total_weight' => $total,
        ]);
    }

    /**
     * ============================
     * RUMUS HAVERSINE
     * ============================
     */
    private function calculateDistanceKm($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 +
        cos(deg2rad($lat1)) *
        cos(deg2rad($lat2)) *
        sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
