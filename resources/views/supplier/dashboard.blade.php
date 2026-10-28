@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- ================= CUSTOMER DASHBOARD ================= --}}
        @if (auth()->user()->isCustomer())
            <div class="mb-4">
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-shop me-1"></i>
                    Dashboard Toko
                </h3>
                <small class="text-muted">Ringkasan aktivitas order toko</small>
            </div>

            {{-- STAT CARD --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-primary">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <small class="text-muted">Total Order</small>
                                <h4 class="fw-bold mb-0">
                                    {{ auth()->user()->store->orders()->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-warning">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div>
                                <small class="text-muted">Order Diproses</small>
                                <h4 class="fw-bold mb-0">
                                    {{ auth()->user()->store->orders()->where('status', 'processed')->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div>
                                <small class="text-muted">Order Selesai</small>
                                <h4 class="fw-bold mb-0">
                                    {{ auth()->user()->store->orders()->where('status', 'completed')->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRAPH --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-semibold mb-3">
                        Grafik Order Toko
                    </h4>
                    <div style="height:220px;">
                        <canvas id="customerOrderChart"></canvas>
                    </div>
                </div>
            </div>

            <a href="{{ route('orders.index') }}" class="btn btn-primary">
                <i class="bi bi-gear me-1"></i> Kelola Order
            </a>
        @endif


        {{-- ================= SUPPLIER DASHBOARD ================= --}}
        @if (auth()->user()->isSupplier())
            <div class="mb-4">
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-truck me-1"></i>
                    Dashboard Supplier
                </h3>
                <small class="text-muted">Monitoring order masuk & proses</small>
            </div>

            {{-- STAT CARD --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-primary">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <div>
                                <small class="text-muted">Total Order Masuk</small>
                                <h4 class="fw-bold mb-0">{{ $totalOrder }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div>
                                <small class="text-muted">Order Menunggu</small>
                                <h4 class="fw-bold mb-0">{{ $pendingOrder }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-info">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div>
                                <small class="text-muted">Order Diproses</small>
                                <h4 class="fw-bold mb-0">{{ $processedOrder }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRAPH --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-semibold mb-3">
                        Grafik Status Order Supplier
                    </h4>
                    <div style="height:220px;">
                        <canvas id="supplierOrderChart"></canvas>
                    </div>
                </div>
            </div>

            <a href="{{ route('supplier.orders') }}" class="btn btn-primary">
                <i class="bi bi-eye me-1"></i> Lihat Order Masuk
            </a>
        @endif

    </div>
@endsection
@section('scripts')
    <script>
        @if (auth()->user()->isCustomer())
            new Chart(document.getElementById('customerOrderChart'), {
                type: 'bar',
                data: {
                    labels: ['Total', 'Diproses', 'Selesai'],
                    datasets: [{
                        data: [
                            {{ auth()->user()->store->orders()->count() }},
                            {{ auth()->user()->store->orders()->where('status', 'processed')->count() }},
                            {{ auth()->user()->store->orders()->where('status', 'completed')->count() }}
                        ],
                        backgroundColor: ['#0d6efd', '#ffc107', '#198754'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1, // ✅ kelipatan 1
                                precision: 0 // ✅ tidak ada desimal
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Order';
                                }
                            }
                        }
                    }
                }
            });
        @endif


        @if (auth()->user()->isSupplier())
            new Chart(document.getElementById('supplierOrderChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Total', 'Pending', 'Processed'],
                    datasets: [{
                        data: [{{ $totalOrder }}, {{ $pendingOrder }}, {{ $processedOrder }}],
                        backgroundColor: ['#0d6efd', '#ffc107', '#0dcaf0']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        @endif
    </script>
@endsection
