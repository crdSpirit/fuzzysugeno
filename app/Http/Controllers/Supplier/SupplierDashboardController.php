<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SupplierDashboardController extends Controller
{
    /**
     * ============================
     * DASHBOARD SUPPLIER
     * ============================
     */
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

    /**
     * ============================
     * LIST ORDER (SORT BY FUZZY)
     * ============================
     */
    public function orders()
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
                END
            ")
            ->orderByRaw("
                (
                    SELECT fuzzy_score
                    FROM shipments
                    WHERE shipments.order_id = orders.id
                ) DESC
            ")
            ->orderBy('orders.created_at', 'ASC')
            ->get();

        return view('supplier.orders', compact('orders'));
    }

    /**
     * ============================
     * DETAIL ORDER SUPPLIER
     * ============================
     */
    public function show($id)
    {
        $order = Order::with(['store', 'items.sparepart', 'shipment'])
            ->findOrFail($id);

        return view('supplier.order_detail', compact('order'));
    }
}
