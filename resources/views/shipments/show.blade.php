@extends('layouts.app')

@section('content')
    <div class="container py-3">

        <h4 class="mb-3">
            Shipment Order {{ $order->order_code }}
        </h4>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($order->shipment)
            <table class="table table-bordered mb-4">
                <tr>
                    <th width="200">Jarak Pengiriman</th>
                    <td>
                        {{ number_format($order->shipment->distance_km, 2) }} km
                    </td>
                </tr>

                <tr>
                    <th>Total Berat</th>
                    <td>
                        {{ number_format($order->total_weight, 2) }} kg
                    </td>
                </tr>

                <tr>
                    <th>Ongkir</th>
                    <td>
                        <strong>
                            Rp {{ number_format($order->shipment->shipping_cost) }}
                        </strong>
                    </td>
                </tr>

                <tr>
                    <th>Fuzzy Score</th>
                    <td>
                        {{ $order->shipment->fuzzy_score ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Keputusan Pengiriman</th>
                    <td>
                        @if ($order->shipment->delivery_decision)
                            <span class="badge bg-info">
                                {{ $order->shipment->delivery_decision }}
                            </span>
                        @else
                            <span class="text-muted">Belum diproses</span>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- BUTTON PROSES FUZZY --}}
            @if (!$order->shipment->fuzzy_score)
                <form method="POST" action="{{ route('shipments.process', $order->id) }}">
                    @csrf
                    <button class="btn btn-success">
                        Proses Pengiriman (Fuzzy Sugeno)
                    </button>
                </form>
            @endif
        @else
            <div class="alert alert-warning">
                Data shipment belum tersedia.
            </div>
        @endif

    </div>
@endsection
