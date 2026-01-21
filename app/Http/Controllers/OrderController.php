<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * ============================
     * LIST ORDER TOKO (LOGIN)
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
     * BUAT ORDER BARU (DRAFT)
     * ============================
     */
    public function create()
    {
        $spareparts = Sparepart::where('stock', '>', 0)->get();
        return view('orders.create', compact('spareparts'));
    }

    /**
     * ============================
     * SIMPAN ORDER (HEADER)
     * ============================
     */
    public function store(Request $request)
    {
        $order = Order::create([
            'order_code' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
            'store_id' => auth()->user()->store_id,
            'order_date' => now(),
            'status' => 'draft',
            'total_weight' => 0,
        ]);

        return redirect()->route('orders.edit', $order->id)
            ->with('success', 'Order berhasil dibuat');
    }

    /**
     * ============================
     * FORM TAMBAH ITEM
     * ============================
     */
    public function edit($id)
    {
        $order = Order::with('items.sparepart')->findOrFail($id);

        // keamanan: pastikan order milik toko sendiri
        if ($order->store_id !== auth()->user()->store_id) {
            abort(403);
        }

        $spareparts = Sparepart::where('stock', '>', 0)->get();

        return view('orders.edit', compact('order', 'spareparts'));
    }

    /**
     * ============================
     * TAMBAH ITEM KE ORDER
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

        // cek stok
        if ($request->quantity > $sparepart->stock) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $totalWeight = $sparepart->weight * $request->quantity;

        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'quantity' => $request->quantity,
            'unit_weight' => $sparepart->weight,
            'total_weight' => $totalWeight,
        ]);

        // update total berat order
        $this->recalculateTotalWeight($order->id);

        return back()->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * ============================
     * HAPUS ITEM
     * ============================
     */
    public function removeItem($itemId)
    {
        $item = OrderItem::findOrFail($itemId);
        $orderId = $item->order_id;

        $item->delete();
        $this->recalculateTotalWeight($orderId);

        return back()->with('success', 'Item dihapus');
    }

    /**
     * ============================
     * CHECKOUT ORDER
     * ============================
     */
    public function checkout(Request $request, $orderId)
    {
        $request->validate([
            'distance_km' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($orderId, $request) {
            $order = Order::with('items.sparepart')->findOrFail($orderId);

            // ubah status
            $order->update([
                'status' => 'submitted',
            ]);

            // kurangi stok
            foreach ($order->items as $item) {
                $item->sparepart->decrement('stock', $item->quantity);
            }

            // buat shipment (fuzzy nanti diisi)
            Shipment::create([
                'order_id' => $order->id,
                'distance_km' => $request->distance_km,
            ]);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil di-checkout');
    }

    /**
     * ============================
     * HITUNG ULANG TOTAL BERAT
     * ============================
     */
    private function recalculateTotalWeight($orderId)
    {
        $total = OrderItem::where('order_id', $orderId)->sum('total_weight');

        Order::where('id', $orderId)->update([
            'total_weight' => $total,
        ]);
    }
}
