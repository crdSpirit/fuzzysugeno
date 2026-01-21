@extends('layouts.app')

@section('content')
    <h4>Dashboard Toko</h4>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Order</h6>
                    <h3>{{ auth()->user()->store->orders()->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Order Diproses</h6>
                    <h3>
                        {{ auth()->user()->store->orders()->where('status', 'processed')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Order Selesai</h6>
                    <h3>
                        {{ auth()->user()->store->orders()->where('status', 'completed')->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-primary">
            Kelola Order
        </a>
    </div>
@endsection
