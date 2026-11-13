<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Edit Transaksi — TaxFlow</title>

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
        <a href="{{ route('transactions.index') }}"
           class="taxflow-btn-back">
            Kembali ke Daftar Transaksi
        </a>
    </div>

    <div class="mb-4">
        <h1 class="h2 taxflow-page-title mb-2">
            Edit Transaksi
        </h1>

        <p class="taxflow-page-subtitle mb-0">
            Perbarui transaksi Draft dengan validasi
            dan konfirmasi sebelum penyimpanan.
        </p>
    </div>

    <div class="alert alert-info border-0 rounded-4 mb-4">
        <strong>Informasi:</strong>
        Perubahan Merchant, Jenis Pajak, atau Nominal Dasar
        dapat memengaruhi hasil perhitungan pajak.
        Perhitungan dilakukan kembali oleh backend Laravel.
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 mb-4"
             role="alert">
            <strong>
                Perubahan belum dapat disimpan.
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card taxflow-card border-0">
        <div class="card-body p-4 p-lg-5">

            <div class="mb-4">
                <h2 class="h5 taxflow-card-title mb-2">
                    Informasi Transaksi
                </h2>

                <p class="small text-muted mb-0">
                    Transaksi:
                    <strong>
                        {{ $transaction->transaction_code }}
                    </strong>
                </p>
            </div>

            <hr class="mb-4">

            <form id="editTransactionForm"
                  method="POST"
                  action="{{ route('transactions.update', $transaction) }}">

                @csrf
                @method('PUT')

                <div class="row g-4">

                    <!-- Kode Transaksi -->
                    <div class="col-md-6">
                        <label for="transaction_code"
                               class="form-label fw-semibold">
                            Kode Transaksi
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            id="transaction_code"
                            name="transaction_code"
                            type="text"
                            maxlength="40"
                            value="{{ old('transaction_code', $transaction->transaction_code) }}"
                            class="form-control rounded-3 @error('transaction_code') is-invalid @enderror"
                            required
                        >

                        @error('transaction_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div class="col-md-6">
                        <label for="transaction_date"
                               class="form-label fw-semibold">
                            Tanggal Transaksi
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            id="transaction_date"
                            name="transaction_date"
                            type="datetime-local"
                            value="{{ old('transaction_date', $transaction->transaction_date?->format('Y-m-d\TH:i')) }}"
                            class="form-control rounded-3 @error('transaction_date') is-invalid @enderror"
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
                            id="merchant_id"
                            name="merchant_id"
                            class="form-select rounded-3 @error('merchant_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Pilih Merchant
                            </option>

                            @foreach ($merchants as $merchant)
                                <option
                                    value="{{ $merchant->id }}"
                                    @selected(
                                        (string) old('merchant_id', $transaction->merchant_id)
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
                            Hanya Merchant aktif yang dapat dipilih.
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
                            id="tax_type_id"
                            name="tax_type_id"
                            class="form-select rounded-3 @error('tax_type_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Pilih Jenis Pajak
                            </option>

                            @foreach ($taxTypes as $taxType)
                                <option
                                    value="{{ $taxType->id }}"
                                    @selected(
                                        (string) old('tax_type_id', $transaction->tax_type_id)
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
                    </div>

                    <!-- Nominal -->
                    <div class="col-md-6">
                        <label for="base_amount_display"
                               class="form-label fw-semibold">
                            Nominal Dasar (Rp)
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                Rp
                            </span>

                            <input
                                type="text"
                                id="base_amount_display"
                                class="form-control @error('base_amount') is-invalid @enderror"
                                placeholder="150.000,00"
                                inputmode="decimal"
                                autocomplete="off"
                                required
                            >

                            <input
                                type="hidden"
                                id="base_amount"
                                name="base_amount"
                                value="{{ old('base_amount', $transaction->base_amount) }}"
                            >

                            @error('base_amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-text">
                            Contoh: 150.000,00
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control bg-light"
                            value="Draft"
                            disabled
                        >

                        <div class="form-text">
                            Status tidak dapat diubah melalui form ini.
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
                            class="form-control rounded-3 @error('description') is-invalid @enderror"
                        >{{ old('description', $transaction->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a
                        href="{{ route('transactions.index') }}"
                        class="btn btn-outline-secondary rounded-3 px-4">
                        Batal
                    </a>

                    <button
                        type="button"
                        id="openConfirmation"
                        class="btn taxflow-btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</main>

<!-- Modal Konfirmasi -->
<div class="modal fade"
     id="confirmEditModal"
     tabindex="-1"
     aria-labelledby="confirmEditModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title fs-5 fw-bold"
                    id="confirmEditModalLabel">
                    Konfirmasi Perubahan Transaksi
                </h2>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    Apakah Anda yakin ingin menyimpan
                    perubahan transaksi ini?
                </p>

                <p class="small text-muted mb-0">
                    Nominal pajak dan total akan
                    dihitung kembali oleh sistem.
                    Perubahan baru disimpan setelah
                    Anda memilih Ya, Simpan.
                </p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                        id="confirmSubmit"
                        class="btn taxflow-btn-primary">
                    Ya, Simpan Perubahan
                </button>
            </div>

        </div>
    </div>
</div>

@include('partials.footer')

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editTransactionForm');
    const display = document.getElementById('base_amount_display');
    const hidden = document.getElementById('base_amount');

    const confirmModal = new bootstrap.Modal(
        document.getElementById('confirmEditModal')
    );

    function formatThousands(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function normalizeAmount(rawValue) {
        const value = rawValue.trim();

        const validPattern =
            /^(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d{0,2})?$/;

        if (!validPattern.test(value)) {
            return null;
        }

        const parts = value.split(',');

        const integerPart =
            parts[0]
                .replace(/\./g, '')
                .replace(/^0+(?=\d)/, '');

        if (integerPart.length > 16) {
            return null;
        }

        const decimalPart =
            (parts[1] || '').padEnd(2, '0');

        return {
            canonical: integerPart + '.' + decimalPart,
            formatted: formatThousands(integerPart) +
                ',' + decimalPart
        };
    }

    function syncAmount(finalize = false) {
        const raw = display.value.trim();

        if (!raw) {
            hidden.value = '';
            display.setCustomValidity(
                'Nominal dasar wajib diisi.'
            );
            return false;
        }

        const result = normalizeAmount(raw);

        if (!result) {
            hidden.value = '';
            display.setCustomValidity(
                'Masukkan format Rupiah yang valid, misalnya 150.000,00.'
            );
            return false;
        }

        display.setCustomValidity('');
        hidden.value = result.canonical;

        if (finalize) {
            display.value = result.formatted;
        } else {
            const parts = raw.split(',');
            const integer = parts[0]
                .replace(/\./g, '')
                .replace(/^0+(?=\d)/, '');

            display.value =
                formatThousands(integer) +
                (parts.length > 1 ? ',' + parts[1] : '');
        }

        return true;
    }

    // Tampilkan nilai asli dari database dalam format Rupiah.
    const original = hidden.value;

    if (/^\d+(?:\.\d{1,2})?$/.test(original)) {
        const parts = original.split('.');

        display.value =
            formatThousands(parts[0]) +
            ',' + (parts[1] || '').padEnd(2, '0');
    }

    display.addEventListener('input', function () {
        syncAmount(false);
    });

    display.addEventListener('blur', function () {
        syncAmount(true);
    });

    document.getElementById('openConfirmation')
        .addEventListener('click', function () {
            if (!syncAmount(true) || !form.reportValidity()) {
                display.reportValidity();
                return;
            }

            confirmModal.show();
        });

    document.getElementById('confirmSubmit')
        .addEventListener('click', function () {
            if (!syncAmount(true) || !form.reportValidity()) {
                confirmModal.hide();
                return;
            }

            this.disabled = true;
            form.requestSubmit();
        });
});
</script>

</body>
</html>
