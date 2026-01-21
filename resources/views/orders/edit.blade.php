@extends('layouts.app')

@section('content')
    <h4>Order {{ $order->order_code }}</h4>

    {{-- FORM TAMBAH ITEM --}}
    <form method="POST" action="{{ route('orders.items.add', $order->id) }}" class="row g-2 mb-4">
        @csrf
        <div class="col-md-5">
            <select name="sparepart_id" class="form-control" required>
                <option value="">-- Pilih Ban --</option>
                @foreach ($spareparts as $sp)
                    <option value="{{ $sp->id }}">
                        {{ $sp->name }} (stok: {{ $sp->stock }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="quantity" class="form-control" placeholder="Qty" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success">Tambah</button>
        </div>
    </form>

    {{-- LIST ITEM --}}
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>Ban</th>
                <th>Qty</th>
                <th>Berat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->sparepart->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->total_weight }} kg</td>
                    <td>
                        <form method="POST" action="{{ route('orders.items.remove', $item->id) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Berat:</strong> {{ $order->total_weight }} kg</p>

    {{-- CHECKOUT --}}
    <form method="POST" action="{{ route('orders.checkout', $order->id) }}">
        @csrf
        <div class="mb-3">
            <label>Jarak Pengiriman (KM)</label>
            <input type="number" step="0.1" name="distance_km" class="form-control" required>
        </div>
        <button class="btn btn-primary">Checkout Order</button>
    </form>
@endsection
