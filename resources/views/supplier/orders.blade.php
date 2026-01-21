@extends('layouts.app')

@section('content')
    <h4>Order Masuk dari Toko</h4>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode Order</th>
                <th>Toko</th>
                <th>Kota</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->store->store_name }}</td>
                    <td>{{ $order->store->city }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('supplier.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
