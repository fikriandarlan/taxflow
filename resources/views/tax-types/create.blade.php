<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Tambah Jenis Pajak — TaxFlow</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- TaxFlow Custom CSS -->
    <link
        href="{{ asset('css/taxflow.css') }}"
        rel="stylesheet"
    >
</head>

<body class="taxflow-app">

    <!-- Navbar Bersama -->
    @include('partials.navbar')

    <!-- Konten Utama -->
    <main class="container-xl taxflow-page">

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="{{ route('tax-types.index') }}"
               class="taxflow-btn-back">
                Kembali ke Daftar Jenis Pajak
            </a>
        </div>

        <!-- Header Halaman -->
        <div class="mb-4">

            <h1 class="h2 taxflow-page-title mb-2">
                Tambah Jenis Pajak
            </h1>

            <p class="taxflow-page-subtitle mb-0">
                Tambahkan jenis dan tarif pajak
                simulasi pada sistem TaxFlow.
            </p>

        </div>

        <!-- Informasi Simulasi -->
        <div class="alert alert-info
                    border-0 rounded-4 mb-4"
             role="note">

            <strong>Informasi:</strong>
            Tarif pajak yang digunakan merupakan
            data simulasi untuk pembelajaran,
            bukan tarif pajak resmi.

        </div>

        <!-- Informasi Kesalahan Validasi -->
        @if ($errors->any())

            <div class="alert alert-danger
                        rounded-4 mb-4"
                 role="alert">

                <strong>
                    Data jenis pajak belum dapat disimpan.
                </strong>

                <p class="mb-0 mt-1">
                    Periksa kembali kolom yang ditandai.
                </p>

            </div>

        @endif

        <!-- Card Form -->
        <div class="card taxflow-card border-0">

            <div class="card-body p-4 p-lg-5">

                <!-- Header Card -->
                <div class="mb-4">

                    <h2 class="h5 taxflow-card-title mb-2">
                        Informasi Jenis Pajak
                    </h2>

                    <p class="text-muted small mb-0">
                        Lengkapi informasi jenis pajak
                        melalui formulir berikut.
                    </p>

                </div>

                <hr class="mb-4">

                <!-- Form Tambah -->
                <form
                    method="POST"
                    action="{{ route('tax-types.store') }}">

                    @csrf

                    <div class="row g-4">

                        <!-- Kode Pajak -->
                        <div class="col-md-6">

                            <label for="tax_code"
                                   class="form-label fw-semibold">

                                Kode Pajak
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="tax_code"
                                name="tax_code"
                                maxlength="30"
                                value="{{ old('tax_code') }}"
                                placeholder="Contoh: TAX-003"
                                class="form-control rounded-3
                                    @error('tax_code') is-invalid @enderror"
                                required
                            >

                            @error('tax_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Kode harus unik dan hanya
                                menggunakan huruf, angka,
                                strip atau underscore.
                            </div>

                        </div>

                        <!-- Nama Jenis Pajak -->
                        <div class="col-md-6">

                            <label for="name"
                                   class="form-label fw-semibold">

                                Nama Jenis Pajak
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                maxlength="100"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Pajak Simulasi"
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

                        <!-- Tarif Pajak -->
                        <div class="col-md-6">

                            <label for="rate_percent"
                                   class="form-label fw-semibold">

                                Tarif Pajak (%)
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="rate_percent"
                                    name="rate_percent"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    value="{{ old('rate_percent') }}"
                                    placeholder="Contoh: 10.00"
                                    class="form-control
                                        @error('rate_percent') is-invalid @enderror"
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
                                Masukkan tarif antara 0 sampai 100.
                                Gunakan titik untuk desimal,
                                misalnya 10.50.
                            </div>

                        </div>

                        <!-- Status Pajak -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Status Jenis Pajak
                            </label>

                            <!-- Nilai saat checkbox nonaktif -->
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <div
                                class="form-check form-switch
                                       rounded-3 border p-3"
                                style="padding-left: 3.5rem !important;">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    @checked(
                                        (bool) old('is_active', 1)
                                    )
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="is_active">

                                    Jenis Pajak Aktif

                                </label>

                                <div class="form-text">
                                    Jenis pajak aktif dapat digunakan
                                    saat membuat transaksi baru.
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

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-wrap
                                justify-content-end gap-2">

                        <a
                            href="{{ route('tax-types.index') }}"
                            class="btn btn-outline-secondary
                                   rounded-3 px-4">

                            Batal

                        </a>

                        <button
                            type="submit"
                            class="btn taxflow-btn-primary">

                            Simpan Jenis Pajak

                        </button>

                    </div>

                </form>

            </div>
        </div>

    </main>

    <!-- Footer Bersama -->
    @include('partials.footer')

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
