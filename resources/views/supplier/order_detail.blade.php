@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    Detail Order
                    <span class="text-primary">{{ $order->order_code }}</span>
                </h4>
                <small class="text-muted">
                    Informasi lengkap pesanan & pengiriman
                </small>
            </div>
        </div>

        {{-- INFO TOKO --}}
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 fs-3 text-primary">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div>
                            <small class="text-muted">Toko</small>
                            <h5 class="fw-semibold mb-0">
                                {{ $order->store->store_name }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 fs-3 text-success">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <small class="text-muted">Kota</small>
                            <h5 class="fw-semibold mb-0">
                                {{ $order->store->city }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LIST ITEM --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-list-check me-1"></i>
                Daftar Item Ban
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th>Ban</th>
                            <th width="120">Qty</th>
                            <th width="160">Berat (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="fw-medium">{{ $item->sparepart->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-dark-lt">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="text-center fw-semibold">
                                    {{ $item->total_weight }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-box-seam fs-3 text-primary mb-1"></i>
                        <small class="text-muted">Total Berat</small>
                        <h4 class="fw-bold mb-0">
                            {{ number_format($order->total_weight, 2) }} kg
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-signpost-2 fs-3 text-success mb-1"></i>
                        <small class="text-muted">Jarak</small>
                        <h4 class="fw-bold mb-0">
                            {{ $order->shipment ? number_format($order->shipment->distance_km, 2) . ' km' : '-' }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-cash-coin fs-3 text-warning mb-1"></i>
                        <small class="text-muted">Ongkir</small>
                        <h4 class="fw-bold mb-0">
                            {{ $order->shipment ? 'Rp ' . number_format($order->shipment->shipping_cost) : '-' }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- FUZZY / SHIPMENT ACTION --}}
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">

                {{-- BELUM ADA SHIPMENT --}}
                @if (!$order->shipment)
                    <div class="alert alert-warning mb-0">
                        Ongkir & jarak belum dihitung.
                    </div>

                    {{-- BELUM DIPROSES FUZZY --}}
                @elseif (!$order->shipment->fuzzy_score)
                    <form action="{{ route('supplier.shipments.process', $order->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-lg px-4">
                            <i class="bi bi-cpu me-1"></i>
                            Proses Pengiriman (Fuzzy Sugeno)
                        </button>
                    </form>

                    {{-- SUDAH FUZZY, BELUM DIKIRIM --}}
                @elseif ($order->status === 'processed')
                    <div class="alert alert-info mb-3">
                        <strong>Fuzzy Score:</strong> {{ $order->shipment->fuzzy_score }} <br>
                        <strong>Keputusan:</strong> {{ $order->shipment->delivery_decision }}
                    </div>

                    <form action="{{ route('supplier.shipments.ship', $order->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-truck me-1"></i>
                            Kirim Barang
                        </button>
                    </form>

                    {{-- SUDAH DIKIRIM --}}
                @elseif ($order->status === 'shipped')
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-truck"></i>
                        Barang sedang dalam pengiriman
                    </div>
                @endif

            </div>
        </div>


    </div>
@endsection
