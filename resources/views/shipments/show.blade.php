@extends('layouts.app')

@section('content')
    <h4>Shipment Order {{ $order->order_code }}</h4>

    <table class="table table-bordered mb-4">
        <tr>
            <th>Jarak</th>
            <td>{{ $order->shipment->distance_km }} km</td>
        </tr>
        <tr>
            <th>Total Berat</th>
            <td>{{ $order->total_weight }} kg</td>
        </tr>
        <tr>
            <th>Fuzzy Score</th>
            <td>{{ $order->shipment->fuzzy_score ?? '-' }}</td>
        </tr>
        <tr>
            <th>Keputusan</th>
            <td>
                @if ($order->shipment->delivery_decision)
                    <span class="badge bg-info">
                        {{ $order->shipment->delivery_decision }}
                    </span>
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    @if (!$order->shipment->fuzzy_score)
        <form method="POST" action="{{ route('shipments.process', $order->id) }}">
            @csrf
            <button class="btn btn-success">
                Proses Pengiriman (Fuzzy Sugeno)
            </button>
        </form>
    @endif
@endsection
