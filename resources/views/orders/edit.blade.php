@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                Order <span class="text-primary">{{ $order->order_code }}</span>
            </h4>
        </div>

        {{-- CARD TAMBAH ITEM --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">
                Tambah Item Ban
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.items.add', $order->id) }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Pilih Ban</label>
                        <select name="sparepart_id" class="form-select" required>
                            <option value="">-- Pilih Ban --</option>
                            @foreach ($spareparts as $sp)
                                <option value="{{ $sp->id }}">
                                    {{ $sp->name }} (stok: {{ $sp->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-success w-100">
                            <i class="bi bi-plus-circle me-1"></i> Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD LIST ITEM --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">
                Daftar Item Order
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-secondary text-center align-middle">
                        <tr>
                            <th>Ban</th>
                            <th width="100">Qty</th>
                            <th width="150">Berat (kg)</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->items as $item)
                            <tr class="align-middle">
                                <td>{{ $item->sparepart->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center">{{ $item->total_weight }}</td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('orders.items.remove', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada item ditambahkan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOTAL & CHECKOUT --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="mb-1">
                            Total Berat :
                            <span class="text-primary fw-bold">
                                {{ $order->total_weight }} kg
                            </span>
                        </h5>

                        @if ($order->shipment)
                            <div class="text-muted small">
                                Jarak : {{ number_format($order->shipment->distance_km, 2) }} KM<br>
                                Ongkir : <strong>Rp {{ number_format($order->shipment->shipping_cost) }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 text-end">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <hr>

                        {{-- PREVIEW ONGKIR --}}
                        <form method="POST" action="{{ route('orders.previewCheckout', $order->id) }}">
                            @csrf
                            <button class="btn btn-outline-primary">
                                Hitung Ongkir & Jarak
                            </button>
                        </form>

                        {{-- TAMPILKAN HASIL --}}
                        @if ($order->shipment)
                            <div class="mt-3">
                                <strong>Jarak:</strong> {{ $order->shipment->distance_km }} KM <br>
                                <strong>Ongkir:</strong> Rp {{ number_format($order->shipment->shipping_cost) }}
                            </div>

                            <form method="POST" action="{{ route('orders.finalCheckout', $order->id) }}" class="mt-2">
                                @csrf
                                <button class="btn btn-success">
                                    Checkout
                                </button>
                            </form>
                        @endif

                    </div>

                </div>
            </div>
        </div>


    </div>
@endsection
