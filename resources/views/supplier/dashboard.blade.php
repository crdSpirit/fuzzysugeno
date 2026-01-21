@extends('layouts.app')

@section('content')
    <h4>Dashboard Supplier</h4>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Order Masuk</h6>
                    <h3>{{ $totalOrder }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Order Menunggu</h6>
                    <h3>{{ $pendingOrder }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Order Diproses</h6>
                    <h3>{{ $processedOrder }}</h3>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('supplier.orders') }}" class="btn btn-primary mt-4">
        Lihat Order Masuk
    </a>
@endsection
