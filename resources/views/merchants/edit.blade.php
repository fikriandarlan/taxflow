<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Edit Merchant — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="{{ asset('css/taxflow.css') }}"
        rel="stylesheet"
    >
</head>

<body class="taxflow-app">

    @include('partials.navbar')

    <main class="container-xl taxflow-page">

        <div class="mb-4">
            <a href="{{ route('merchants.index') }}"
               class="text-decoration-none fw-semibold">
                Kembali ke Daftar Merchant
            </a>
        </div>

        <!-- Header -->
        <div class="mb-4">
            <h1 class="h2 taxflow-page-title mb-2">
                Edit Merchant
            </h1>

            <p class="taxflow-page-subtitle mb-0">
                Perbarui informasi Merchant
                yang sudah terdaftar.
            </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 mb-4"
                 role="alert">
                <strong>
                    Perubahan belum dapat disimpan.
                </strong>

                <p class="mb-0 mt-1">
                    Periksa kembali kolom yang ditandai.
                </p>
            </div>
        @endif

        <!-- Card Form -->
        <div class="card taxflow-card border-0">
            <div class="card-body p-4 p-lg-5">

                <div class="mb-4">
                    <h2 class="h5 taxflow-card-title mb-2">
                        Informasi Merchant
                    </h2>

                    <p class="text-muted small mb-0">
                        Kode Merchant:
                        <strong>
                            {{ $merchant->merchant_code }}
                        </strong>
                    </p>
                </div>

                <hr class="mb-4">

                <form
                    method="POST"
                    action="{{ route('merchants.update', $merchant->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        <!-- Kode -->
                        <div class="col-md-6">
                            <label for="merchant_code"
                                   class="form-label fw-semibold">
                                Kode Merchant
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="merchant_code"
                                name="merchant_code"
                                maxlength="30"
                                value="{{ old('merchant_code', $merchant->merchant_code) }}"
                                class="form-control rounded-3
                                    @error('merchant_code') is-invalid @enderror"
                                required
                            >

                            @error('merchant_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div class="col-md-6">
                            <label for="name"
                                   class="form-label fw-semibold">
                                Nama Merchant
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                maxlength="150"
                                value="{{ old('name', $merchant->name) }}"
                                class="form-control rounded-3
                                    @error('name') is-invalid @enderror"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email"
                                   class="form-label fw-semibold">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $merchant->email) }}"
                                class="form-control rounded-3
                                    @error('email') is-invalid @enderror"
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="col-md-6">
                            <label for="phone"
                                   class="form-label fw-semibold">
                                Nomor Telepon
                            </label>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $merchant->phone) }}"
                                class="form-control rounded-3
                                    @error('phone') is-invalid @enderror"
                            >

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="col-12">
                            <label for="address"
                                   class="form-label fw-semibold">
                                Alamat
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                rows="3"
                                class="form-control rounded-3
                                    @error('address') is-invalid @enderror"
                            >{{ old('address', $merchant->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Status Merchant
                            </label>

                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <div class="form-check form-switch
                                        p-3 rounded-3 border"
                                 style="padding-left: 3.5rem !important;">

                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    role="switch"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    @checked(
                                        (bool) old(
                                            'is_active',
                                            $merchant->is_active
                                        )
                                    )
                                >

                                <label class="form-check-label
                                              fw-semibold"
                                       for="is_active">
                                    Merchant Aktif
                                </label>

                                <div class="form-text">
                                    Merchant tidak aktif tidak
                                    tersedia untuk transaksi baru.
                                </div>
                            </div>

                            @error('is_active')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- Aksi -->
                    <div class="d-flex flex-wrap
                                justify-content-end gap-2">

                        <a href="{{ route('merchants.index') }}"
                           class="btn btn-outline-secondary
                                  rounded-3 px-4">
                            Batal
                        </a>

                        <button type="submit"
                                class="btn taxflow-btn-primary">
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </main>

    @include('partials.footer')

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
