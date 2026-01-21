<!DOCTYPE html>
<html>

<head>
    <title>Order Ban Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">
                Sistem Order Ban Motor
            </span>
            <span class="text-white">
                {{ auth()->user()->name }} ({{ auth()->user()->store->store_name }})
            </span>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-sm btn-outline-light">
            Logout
        </button>
    </form>


    @if (auth()->user()->isCustomer())
        <a href="{{ route('orders.index') }}">Order Ban</a>
    @endif

    @if (auth()->user()->isSupplier())
        <a href="/supplier/orders">Order Masuk</a>
    @endif

    @if (auth()->user()->isSupplier())
        <a href="{{ route('supplier.dashboard') }}" class="btn btn-sm btn-warning">
            Supplier Dashboard
        </a>
    @endif



</body>

</html>
