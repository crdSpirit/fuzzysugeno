<!doctype html>
<html lang="en">

<head>
    @include('layouts.partials.head')
</head>

<body>
    <div class="page">

        @include('layouts.partials.navbar')

        <div class="page-wrapper">
            <div class="page-body">


                @yield('content')
            </div>

            @include('layouts.partials.footer')

        </div>
    </div>

    @include('layouts.partials.js')
    @yield('scripts')
</body>

</html>
