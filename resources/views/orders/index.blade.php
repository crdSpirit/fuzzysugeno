@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Order</h4>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            + Order Baru
        </a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode Order</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total Berat (kg)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->order_date }}</td>
                    <td>
                        <span
                            class="badge
                        @if ($order->status === 'draft') bg-secondary
                        @elseif($order->status === 'submitted') bg-warning
                        @elseif($order->status === 'processed') bg-info
                        @elseif($order->status === 'completed') bg-success @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->total_weight ?? 0 }}</td>
                    <td>
                        {{-- HANYA DRAFT BOLEH EDIT --}}
                        @if ($order->status === 'draft')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            {{-- SETELAH SUBMIT --}}
                        @else
                            <span class="text-muted">
                                Menunggu Supplier
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada order
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
