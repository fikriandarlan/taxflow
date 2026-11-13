<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Tambah Jenis Pajak — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">

            <a href="{{ route('tax-types.index') }}"
               class="navbar-brand fw-bold">
                TaxFlow
            </a>

            <div class="d-flex align-items-center gap-3">

                <span class="text-white small">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
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

            <a href="{{ route('tax-types.index') }}"
               class="text-decoration-none">
                &larr; Kembali ke Daftar Jenis Pajak
            </a>

            <h2 class="fw-bold mt-3 mb-1">
                Tambah Jenis Pajak
            </h2>

            <p class="text-muted mb-0">
                Masukkan informasi jenis dan tarif
                pajak simulasi.
            </p>

        </div>

        <!-- Pemberitahuan -->
        <div class="alert alert-info" role="note">
            <strong>Informasi:</strong>
            Data tarif yang dimasukkan hanya digunakan
            untuk simulasi pembelajaran TaxFlow,
            bukan tarif pajak resmi.
        </div>

        <!-- Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('tax-types.store') }}">

                    @csrf

                    <div class="row g-3">

                        <!-- Kode Pajak -->
                        <div class="col-md-6">

                            <label
                                for="tax_code"
                                class="form-label">
                                Kode Pajak *
                            </label>

                            <input
                                type="text"
                                id="tax_code"
                                name="tax_code"
                                maxlength="30"
                                value="{{ old('tax_code') }}"
                                class="form-control @error('tax_code') is-invalid @enderror"
                                placeholder="TAX-001"
                                required
                            >

                            @error('tax_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Nama Jenis Pajak -->
                        <div class="col-md-6">

                            <label
                                for="name"
                                class="form-label">
                                Nama Jenis Pajak *
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                maxlength="100"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Pajak Simulasi A"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Tarif -->
                        <div class="col-md-6">

                            <label
                                for="rate_percent"
                                class="form-label">
                                Tarif Pajak (%) *
                            </label>

                            <div class="input-group has-validation">

                                <input
                                    type="number"
                                    id="rate_percent"
                                    name="rate_percent"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    value="{{ old('rate_percent') }}"
                                    class="form-control @error('rate_percent') is-invalid @enderror"
                                    placeholder="10.00"
                                    required
                                >

                                <span class="input-group-text">
                                    %
                                </span>

                                @error('rate_percent')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="form-text">
                                Gunakan nilai 0 sampai 100,
                                maksimal dua angka desimal.
                            </div>

                        </div>

                        <!-- Status -->
                        <div class="col-md-6">

                            <label
                                for="is_active"
                                class="form-label">
                                Status *
                            </label>

                            <select
                                id="is_active"
                                name="is_active"
                                class="form-select @error('is_active') is-invalid @enderror"
                                required
                            >

                                <option
                                    value="1"
                                    @selected(old('is_active', '1') == '1')>
                                    Aktif
                                </option>

                                <option
                                    value="0"
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

                    <!-- Aksi -->
                    <div class="d-flex
                                justify-content-end gap-2">

                        <a href="{{ route('tax-types.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary">
                            Simpan Jenis Pajak
                        </button>

                    </div>

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
