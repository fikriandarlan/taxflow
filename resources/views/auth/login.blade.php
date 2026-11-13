<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Login — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex
            align-items-center justify-content-center">

    <div class="card shadow-sm border-0"
         style="width: 100%; max-width: 420px;">

        <div class="card-body p-4">

            <h2 class="text-center fw-bold mb-2">
                TaxFlow
            </h2>

            <p class="text-center text-muted mb-4">
                Sistem Manajemen Transaksi Pajak
            </p>

            <h5 class="mb-3">Masuk ke akun</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('login.submit') }}">

                @csrf

                <div class="mb-3">
                    <label for="email"
                           class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="password"
                           class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit"
                        class="btn btn-primary w-100">
                    Login
                </button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
