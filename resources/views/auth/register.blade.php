<!DOCTYPE html>
<html>

<head>
    <title>Registrasi Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        Registrasi Toko
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <h6 class="mb-2">Data Toko</h6>

                            <div class="mb-3">
                                <label>Nama Toko</label>
                                <input type="text" name="store_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Kota</label>
                                <input type="text" name="city" class="form-control">
                            </div>

                            <hr>

                            <h6 class="mb-2">Akun Admin Toko</h6>

                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button class="btn btn-success w-100">Daftar</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">Sudah punya akun? Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
