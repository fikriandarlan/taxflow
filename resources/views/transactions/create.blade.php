<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Tambah Transaksi — TaxFlow</title>

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

    <!-- Navbar bersama tanpa logo/avatar -->
    @include('partials.navbar')

    <main class="container-xl taxflow-page">

        <div class="mb-4">
            <a href="{{ route('transactions.index') }}"
            class="taxflow-btn-back">
                Kembali ke Daftar Transaksi
            </a>
        </div>

        <!-- Header -->
        <div class="mb-4">
            <h1 class="h2 taxflow-page-title mb-2">
                Tambah Transaksi
            </h1>

            <p class="taxflow-page-subtitle mb-0">
                Tambahkan transaksi baru menggunakan
                perhitungan pajak simulasi TaxFlow.
            </p>
        </div>

        <!-- Informasi -->
        <div class="alert alert-info
                    border-0 rounded-4 mb-4"
             role="note">

            <strong>Informasi:</strong>
            Tarif, nominal pajak, dan total transaksi
            dihitung oleh backend Laravel.
            Status awal transaksi adalah Draft.
        </div>

        <!-- Pesan validasi -->
        @if ($errors->any())
            <div class="alert alert-danger
                        rounded-4 mb-4"
                 role="alert">

                <strong>
                    Data transaksi belum dapat disimpan.
                </strong>

                <p class="mb-0 mt-1">
                    Periksa kembali kolom yang ditandai.
                </p>
            </div>
        @endif

        <!-- Card Form -->
        <div class="card taxflow-card border-0">

            <div class="card-body p-4 p-lg-5">

                <!-- Header Card tanpa ikon -->
                <div class="mb-4">

                    <h2 class="h5 taxflow-card-title mb-2">
                        Informasi Transaksi
                    </h2>

                    <p class="text-muted small mb-0">
                        Lengkapi data transaksi melalui
                        formulir berikut.
                    </p>

                </div>

                <hr class="mb-4">

                <form method="POST"
                      action="{{ route('transactions.store') }}">

                    @csrf

                    <div class="row g-4">

                        <!-- Kode Transaksi -->
                        <div class="col-md-6">

                            <label for="transaction_code"
                                   class="form-label fw-semibold">
                                Kode Transaksi
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="transaction_code"
                                id="transaction_code"
                                maxlength="40"
                                value="{{ old('transaction_code') }}"
                                placeholder="Contoh: TRX-02"
                                class="form-control rounded-3
                                       @error('transaction_code')
                                           is-invalid
                                       @enderror"
                                required
                            >

                            @error('transaction_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Kode harus unik untuk setiap transaksi.
                            </div>

                        </div>

                        <!-- Tanggal Transaksi -->
                        <div class="col-md-6">

                            <label for="transaction_date"
                                   class="form-label fw-semibold">
                                Tanggal Transaksi
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="datetime-local"
                                name="transaction_date"
                                id="transaction_date"
                                value="{{ old('transaction_date') }}"
                                class="form-control rounded-3
                                       @error('transaction_date')
                                           is-invalid
                                       @enderror"
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
                                   class="form-label fw-semibold">
                                Merchant
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="merchant_id"
                                id="merchant_id"
                                class="form-select rounded-3
                                       @error('merchant_id')
                                           is-invalid
                                       @enderror"
                                required
                            >

                                <option value="">
                                    Pilih Merchant
                                </option>

                                @foreach ($merchants as $merchant)

                                    <option
                                        value="{{ $merchant->id }}"
                                        @selected(
                                            (string) old('merchant_id')
                                            === (string) $merchant->id
                                        )
                                    >
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

                            <div class="form-text">
                                Hanya Merchant aktif yang ditampilkan.
                            </div>

                        </div>

                        <!-- Jenis Pajak -->
                        <div class="col-md-6">

                            <label for="tax_type_id"
                                   class="form-label fw-semibold">
                                Jenis Pajak
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="tax_type_id"
                                id="tax_type_id"
                                class="form-select rounded-3
                                       @error('tax_type_id')
                                           is-invalid
                                       @enderror"
                                required
                            >

                                <option value="">
                                    Pilih Jenis Pajak
                                </option>

                                @foreach ($taxTypes as $taxType)

                                    <option
                                        value="{{ $taxType->id }}"
                                        @selected(
                                            (string) old('tax_type_id')
                                            === (string) $taxType->id
                                        )
                                    >
                                        {{ $taxType->tax_code }}
                                        — {{ $taxType->name }}
                                        ({{ $taxType->rate_percent }}%)
                                    </option>

                                @endforeach

                            </select>

                            @error('tax_type_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Tarif diambil dari database saat
                                transaksi disimpan.
                            </div>

                        </div>
                        
                        <!-- Nominal Dasar -->
                        <div class="col-md-6">

                            <label for="base_amount_display"
                                class="form-label fw-semibold">
                                Nominal Dasar (Rp)
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text
                                            bg-light fw-semibold">
                                    Rp
                                </span>

                                <!-- Input yang dilihat pengguna -->
                                <input
                                    type="text"
                                    id="base_amount_display"
                                    class="form-control rounded-end-3
                                        @error('base_amount') is-invalid @enderror"
                                    placeholder="150.000,00"
                                    inputmode="decimal"
                                    autocomplete="off"
                                    aria-describedby="base_amount_help"
                                    required
                                >

                                <!-- Nilai standar untuk Laravel -->
                                <input
                                    type="hidden"
                                    id="base_amount"
                                    name="base_amount"
                                    value="{{ old('base_amount') }}"
                                >

                                @error('base_amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div id="base_amount_help"
                                class="form-text">
                                Gunakan format Rupiah Indonesia,
                                misalnya 150.000,00.
                                Maksimal dua angka desimal.
                            </div>

                        </div>

                        <!-- Status Awal -->
                        <div class="col-md-6">

                            <label for="initial_status"
                                   class="form-label fw-semibold">
                                Status Awal
                            </label>

                            <input
                                type="text"
                                id="initial_status"
                                class="form-control rounded-3
                                       bg-light"
                                value="Draft"
                                disabled
                            >

                            <div class="form-text">
                                Ditentukan otomatis oleh backend.
                            </div>

                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">

                            <label for="description"
                                   class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                maxlength="2000"
                                placeholder="Masukkan keterangan transaksi (opsional)"
                                class="form-control rounded-3
                                       @error('description')
                                           is-invalid
                                       @enderror"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Maksimal 2.000 karakter.
                            </div>

                        </div>

                    </div>

                    <!-- Informasi Perhitungan -->
                    <div class="mt-4 p-3 rounded-4"
                         style="
                            background: #f0f6ff;
                            border: 1px solid #dbeafe;
                         ">

                        <h3 class="h6 fw-bold mb-2">
                            Perhitungan Pajak
                        </h3>

                        <p class="small text-muted mb-0">
                            Nominal pajak dan total transaksi
                            akan dihitung secara otomatis
                            ketika data disimpan.
                            Nilai hasil perhitungan dapat
                            dilihat pada Daftar Transaksi.
                        </p>

                    </div>

                    <hr class="my-4">

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-wrap
                                justify-content-end gap-2">

                        <a href="{{ route('transactions.index') }}"
                           class="btn btn-outline-secondary
                                  rounded-3 px-4">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="btn taxflow-btn-primary"
                            @disabled(
                                $merchants->isEmpty()
                                || $taxTypes->isEmpty()
                            )
                        >
                            Simpan Transaksi
                        </button>

                    </div>

                    @if ($merchants->isEmpty() || $taxTypes->isEmpty())

                        <p class="text-danger small mt-3 mb-0">
                            Transaksi belum dapat dibuat.
                            Pastikan tersedia Merchant aktif
                            dan Jenis Pajak aktif.
                        </p>

                    @endif

                </form>

            </div>
        </div>

    </main>

    <!-- Footer bersama -->
    @include('partials.footer')

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const display = document.getElementById(
        'base_amount_display'
    );

    const hidden = document.getElementById(
        'base_amount'
    );

    const form = display.closest('form');

    if (!display || !hidden || !form) {
        return;
    }

    function groupThousands(digits) {
        return digits.replace(
            /\B(?=(\d{3})+(?!\d))/g,
            '.'
        );
    }

    function normalizeAmount(value) {
        const input = value.trim();

        // Terima angka tanpa pemisah ribuan atau
        // angka dengan pemisah ribuan Indonesia.
        const validFormat =
            /^(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d{0,2})?$/;

        if (!validFormat.test(input)) {
            return null;
        }

        const parts = input.split(',');
        const integerDigits = parts[0].replace(/\./g, '');

        // NUMERIC(18,2): maksimum 16 digit integer.
        const integerPart =
            integerDigits.replace(/^0+(?=\d)/, '');

        if (integerPart.length > 16) {
            return null;
        }

        const decimalPart =
            (parts[1] || '').padEnd(2, '0');

        const canonical =
            integerPart + '.' + decimalPart;

        return {
            canonical: canonical,
            formatted:
                groupThousands(integerPart) +
                ',' + decimalPart
        };
    }

    function syncAmount(finalize) {
        const rawValue = display.value.trim();

        if (rawValue === '') {
            hidden.value = '';
            display.setCustomValidity('');
            return;
        }

        const result = normalizeAmount(rawValue);

        if (result === null) {
            hidden.value = '';

            display.setCustomValidity(
                'Gunakan format Rupiah seperti 150.000,00 ' +
                'dengan maksimal 16 digit angka utama ' +
                'dan 2 angka desimal.'
            );

            return;
        }

        hidden.value = result.canonical;
        display.setCustomValidity('');

        if (finalize) {
            display.value = result.formatted;
            return;
        }

        // Format ribuan saat mengetik.
        // Jangan menambahkan ,00 sampai input selesai.
        const parts = rawValue.split(',');

        const integerDigits =
            parts[0].replace(/\./g, '');

        const grouped = groupThousands(
            integerDigits.replace(/^0+(?=\d)/, '')
        );

        display.value = parts.length > 1
            ? grouped + ',' + parts[1]
            : grouped;
    }

    // Format otomatis saat mengetik.
    display.addEventListener('input', function () {
        syncAmount(false);
    });

    // Lengkapi dua angka desimal saat fokus keluar.
    display.addEventListener('blur', function () {
        syncAmount(true);
    });

    // Pastikan nilai backend valid sebelum submit.
    form.addEventListener('submit', function (event) {
        syncAmount(true);

        if (!display.checkValidity() || hidden.value === '') {
            event.preventDefault();
            display.reportValidity();
        }
    });

    // Kembalikan input lama setelah validasi gagal.
    // old('base_amount') memakai format standar Laravel.
    const previousAmount = @json(old('base_amount', ''));

    if (
        previousAmount !== '' &&
        /^\d+(?:\.\d{1,2})?$/.test(previousAmount)
    ) {
        const parts = previousAmount.split('.');

        const integerPart = parts[0];
        const decimalPart = (parts[1] || '').padEnd(2, '0');

        display.value =
            groupThousands(integerPart) +
            ',' + decimalPart;

        syncAmount(true);
    }
});
</script>

</body>
</html>
