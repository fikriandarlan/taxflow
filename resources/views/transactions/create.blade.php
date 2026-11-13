<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Tambah Transaksi — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">

            <a href="{{ route('transactions.index') }}"
               class="navbar-brand fw-bold">
                TaxFlow
            </a>

            <div class="d-flex flex-wrap
                        align-items-center gap-3">

                <a href="{{ route('merchants.index') }}"
                   class="text-white text-decoration-none small">
                    Merchant
                </a>

                <a href="{{ route('tax-types.index') }}"
                   class="text-white text-decoration-none small">
                    Jenis Pajak
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="text-white text-decoration-none small fw-bold">
                    Transaksi
                </a>

                <span class="text-white-50 small">
                    {{ auth()->user()->name }}
                </span>

                <!-- Logout -->
                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </nav>

    <!-- Konten -->
    <main class="container py-5">

        <div class="mb-4">

            <a href="{{ route('transactions.index') }}"
               class="text-decoration-none">
                &larr; Kembali ke Daftar Transaksi
            </a>

            <h2 class="fw-bold mt-3 mb-1">
                Tambah Transaksi
            </h2>

            <p class="text-muted mb-0">
                Buat transaksi baru menggunakan
                perhitungan pajak simulasi TaxFlow.
            </p>

        </div>

        <!-- Informasi -->
        <div class="alert alert-info" role="note">
            <strong>Informasi:</strong>
            Tarif pajak dan total transaksi dihitung
            oleh backend Laravel.
            Form ini hanya digunakan untuk simulasi.
        </div>

        <!-- Error Validation -->
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Data belum dapat disimpan.</strong>
                Periksa kembali kolom yang ditandai.
            </div>
        @endif

        <!-- Form Create -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('transactions.store') }}">

                    @csrf

                    <div class="row g-3">

                        <!-- Kode Transaksi -->
                        <div class="col-md-6">

                            <label for="transaction_code"
                                   class="form-label">
                                Kode Transaksi *
                            </label>

                            <input
                                type="text"
                                id="transaction_code"
                                name="transaction_code"
                                maxlength="40"
                                value="{{ old('transaction_code') }}"
                                class="form-control @error('transaction_code') is-invalid @enderror"
                                placeholder="TRX-001"
                                required
                            >

                            @error('transaction_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Tanggal Transaksi -->
                        <div class="col-md-6">

                            <label for="transaction_date"
                                   class="form-label">
                                Tanggal Transaksi *
                            </label>

                            <input
                                type="datetime-local"
                                id="transaction_date"
                                name="transaction_date"
                                value="{{ old('transaction_date') }}"
                                class="form-control @error('transaction_date') is-invalid @enderror"
                                required
                            >

                            @error('transaction_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Merchant -->
                        <div class="col-md-6">

                            <label for="merchant_id"
                                   class="form-label">
                                Merchant *
                            </label>

                            <select
                                id="merchant_id"
                                name="merchant_id"
                                class="form-select @error('merchant_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- Pilih Merchant --
                                </option>

                                @foreach ($merchants as $merchant)
                                    <option
                                        value="{{ $merchant->id }}"
                                        @selected(
                                            (string) old('merchant_id')
                                            === (string) $merchant->id
                                        )>
                                        {{ $merchant->merchant_code }}
                                        — {{ $merchant->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('merchant_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Jenis Pajak -->
                        <div class="col-md-6">

                            <label for="tax_type_id"
                                   class="form-label">
                                Jenis Pajak *
                            </label>

                            <select
                                id="tax_type_id"
                                name="tax_type_id"
                                class="form-select @error('tax_type_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- Pilih Jenis Pajak --
                                </option>

                                @foreach ($taxTypes as $taxType)
                                    <option
                                        value="{{ $taxType->id }}"
                                        @selected(
                                            (string) old('tax_type_id')
                                            === (string) $taxType->id
                                        )>
                                        {{ $taxType->tax_code }}
                                        — {{ $taxType->name }}
                                        ({{ number_format(
                                            (float) $taxType->rate_percent,
                                            2,
                                            ',',
                                            '.'
                                        ) }}%)
                                    </option>
                                @endforeach

                            </select>

                            @error('tax_type_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Nominal Dasar -->
                        <div class="col-md-6">

                            <label for="base_amount"
                                   class="form-label">
                                Nominal Dasar (Rp) *
                            </label>

                            <input
                                type="number"
                                id="base_amount"
                                name="base_amount"
                                min="0"
                                max="9999999999999999.99"
                                step="0.01"
                                value="{{ old('base_amount') }}"
                                class="form-control @error('base_amount') is-invalid @enderror"
                                placeholder="100000.00"
                                required
                            >

                            @error('base_amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Gunakan titik sebagai pemisah
                                desimal, misalnya 100000.00.
                            </div>

                        </div>

                        <!-- Status Awal -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Status Awal
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="Draft"
                                disabled
                            >

                            <div class="form-text">
                                Status ditetapkan otomatis
                                oleh backend.
                            </div>

                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">

                            <label for="description"
                                   class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                maxlength="2000"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Keterangan transaksi (opsional)"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- Aksi -->
                    <div class="d-flex
                                justify-content-end gap-2">

                        <a href="{{ route('transactions.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>

                        <button type="submit"
                                class="btn btn-primary"
                                @disabled(
                                    $merchants->isEmpty()
                                    || $taxTypes->isEmpty()
                                )>
                            Simpan Transaksi
                        </button>

                    </div>

                    @if ($merchants->isEmpty() || $taxTypes->isEmpty())
                        <p class="text-danger small mt-3 mb-0">
                            Transaksi belum dapat dibuat.
                            Pastikan terdapat Merchant aktif
                            dan Jenis Pajak aktif.
                        </p>
                    @endif

                </form>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="text-center text-muted py-4 small">
        TaxFlow — Sistem Manajemen Transaksi Pajak
    </footer>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
