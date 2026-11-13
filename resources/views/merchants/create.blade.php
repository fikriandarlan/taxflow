<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Tambah Merchant — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a href="{{ route('merchants.index') }}"
           class="navbar-brand fw-bold">
            TaxFlow
        </a>
    </div>
</nav>

<main class="container py-5">

    <div class="mb-4">
        <a href="{{ route('merchants.index') }}"
           class="text-decoration-none">
            &larr; Kembali ke Daftar Merchant
        </a>

        <h2 class="fw-bold mt-3">Tambah Merchant</h2>

        <p class="text-muted">
            Lengkapi informasi merchant baru.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('merchants.store') }}">

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="merchant_code"
                               class="form-label">
                            Kode Merchant *
                        </label>

                        <input
                            type="text"
                            id="merchant_code"
                            name="merchant_code"
                            maxlength="30"
                            value="{{ old('merchant_code') }}"
                            class="form-control @error('merchant_code') is-invalid @enderror"
                            placeholder="MRC-001"
                            required
                        >

                        @error('merchant_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name"
                               class="form-label">
                            Nama Merchant *
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            maxlength="150"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Contoh: Toko Nusantara"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            maxlength="150"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="merchant@example.com"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">
                            Telepon
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            maxlength="30"
                            value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="08xxxxxxxxxx"
                        >

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">
                            Alamat
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Alamat merchant"
                        >{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="is_active"
                               class="form-label">
                            Status Merchant *
                        </label>

                        <select
                            id="is_active"
                            name="is_active"
                            class="form-select @error('is_active') is-invalid @enderror"
                            required
                        >
                            <option value="1"
                                @selected(old('is_active', '1') == '1')>
                                Aktif
                            </option>

                            <option value="0"
                                @selected(old('is_active') === '0')>
                                Tidak Aktif
                            </option>
                        </select>

                        @error('is_active')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('merchants.index') }}"
                       class="btn btn-outline-secondary">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Simpan Merchant
                    </button>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>

@csrf
@method('PUT')