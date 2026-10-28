<div class="sticky-top">
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="." class="navbar-brand-image">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-navbar">
                </a>
            </div>
            <div class="navbar-nav flex-row order-md-last">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                        aria-label="Open user menu">
                        <span class="avatar avatar-sm"
                            style="background-image: url({{ asset('assets/images/user.png') }})"></span>
                        <div class="d-none d-xl-block ps-2">
                            <div>{{ auth()->user()->name }}</div>
                            <div class="mt-1 small text-secondary">
                                {{ auth()->user()->store->store_name }}
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar">
                <div class="container-xl">
                    <div class="row flex-fill align-items-center">
                        <div class="col">
                            <ul class="navbar-nav">
                                <li
                                    class="nav-item {{ request()->is(['/', '/*', 'supplier/orders', 'supplier/orders/*', 'orders', 'orders/*']) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('dashboard') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M13.45 11.55l2.05 -2.05" />
                                                <path d="M6.4 20a9 9 0 1 1 11.2 0z" />
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Dashboard
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->is('store/profile') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('store.profile.edit') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icon-tabler-building-store">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 21h18" />
                                                <path d="M5 21v-14l7 -4l7 4v14" />
                                                <path d="M9 21v-6h6v6" />
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Data Toko
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
