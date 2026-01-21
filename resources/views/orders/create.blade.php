@extends('layouts.app')

@section('content')
    <h4>Buat Order Baru</h4>

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <button class="btn btn-primary">Buat Order</button>
    </form>
@endsection
