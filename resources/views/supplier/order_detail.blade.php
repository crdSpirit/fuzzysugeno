@extends('layouts.app')

@section('content')
    <h4>Detail Order {{ $order->order_code }}</h4>

    <p>
        <strong>Toko:</strong> {{ $order->store->store_name }} <br>
        <strong>Kota:</strong> {{ $order->store->city }}
    </p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ban</th>
                <th>Qty</th>
                <th>Berat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->sparepart->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->total_weight }} kg</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Berat:</strong> {{ $order->total_weight }} kg</p>
    <p><strong>Jarak:</strong> {{ $order->shipment->distance_km }} km</p>

    @if (!$order->shipment->fuzzy_score)
        <form action="{{ route('supplier.shipments.process', $order->id) }}" method="POST">
            @csrf
            <button class="btn btn-success">
                Proses Pengiriman (Fuzzy Sugeno)
            </button>
        </form>
    @else
        <div class="alert alert-info mt-3">
            <strong>Fuzzy Score:</strong> {{ $order->shipment->fuzzy_score }} <br>
            <strong>Keputusan:</strong> {{ $order->shipment->delivery_decision }}
        </div>
    @endif
@endsection
