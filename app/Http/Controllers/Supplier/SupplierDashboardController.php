<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SupplierDashboardController extends Controller
{
    public function dashboard()
    {
        $totalOrder = Order::count();
        $pendingOrder = Order::where('status', 'submitted')->count();
        $processedOrder = Order::where('status', 'processed')->count();

        return view('supplier.dashboard', compact(
            'totalOrder',
            'pendingOrder',
            'processedOrder'
        ));
    }

    public function orders()
    {
        $orders = Order::with(['store', 'shipment'])
            ->latest()
            ->get();

        return view('supplier.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['store', 'items.sparepart', 'shipment'])
            ->findOrFail($id);

        return view('supplier.order_detail', compact('order'));
    }
}
