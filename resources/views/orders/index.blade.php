@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-list-ul me-1"></i>
                    Daftar Order
                </h4>
                <small class="text-muted">
                    Kelola dan pantau status order Anda
                </small>
            </div>

            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Order Baru
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-left">
                            <th>Kode Order</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Total Berat (kg)</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-semibold text-primary">
                                    {{ $order->order_code }}
                                </td>

                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @php
                                        $statusColor = match ($order->status) {
                                            'draft' => 'secondary',
                                            'submitted' => 'warning',
                                            'processed' => 'info',
                                            'completed' => 'success',
                                            default => 'dark',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}-lt">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td class="text-center fw-semibold">
                                    {{ $order->total_weight ?? 0 }}
                                </td>

                                <td class="text-center">
                                    @if ($order->status === 'draft')
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    @else
                                        <span class="text-muted small">
                                            <i class="bi bi-clock-history"></i>
                                            Menunggu Supplier
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                    Belum ada order
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
