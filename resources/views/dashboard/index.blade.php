<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Dashboard — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link href="{{ asset('css/taxflow.css') }}"
          rel="stylesheet">
</head>

<body class="taxflow-app">

@include('partials.navbar')

<main class="container-xl taxflow-page">

    <!-- Header -->
    <div class="mb-4">
        <h1 class="h2 taxflow-page-title mb-2">
            Dashboard TaxFlow
        </h1>

        <p class="taxflow-page-subtitle mb-0">
            Ringkasan Merchant, Jenis Pajak,
            dan Transaksi dalam satu halaman.
        </p>
    </div>

    <!-- Informasi -->
    <div class="alert alert-info border-0 rounded-4 mb-4">
        <strong>Informasi:</strong>
        Dashboard menampilkan perhitungan simulasi.
        Nilai seluruh transaksi termasuk Draft dan Cancelled
        tidak menunjukkan penerimaan pajak resmi.
    </div>

    <!-- Menu Utama -->
    <section class="mb-4">
        <h2 class="h5 fw-bold mb-3">Menu Pengelolaan</h2>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold">Merchant</h3>

                        <p class="text-muted small">
                            Kelola data usaha dan status Merchant.
                        </p>

                        <a href="{{ route('merchants.index') }}"
                           class="btn taxflow-btn-primary">
                            Buka Merchant
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold">Jenis Pajak</h3>

                        <p class="text-muted small">
                            Kelola kategori dan tarif pajak simulasi.
                        </p>

                        <a href="{{ route('tax-types.index') }}"
                           class="btn taxflow-btn-primary">
                            Buka Jenis Pajak
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold">Transaksi</h3>

                        <p class="text-muted small">
                            Tambah, lihat, dan edit transaksi Draft.
                        </p>

                        <a href="{{ route('transactions.index') }}"
                           class="btn taxflow-btn-primary">
                            Buka Transaksi
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Statistik Master Data -->
    <section class="mb-4">
        <h2 class="h5 fw-bold mb-3">Statistik Sistem</h2>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">
                            Total Merchant
                        </p>

                        <h3 class="h3 fw-bold">
                            {{ number_format($totalMerchants, 0, ',', '.') }}
                        </h3>

                        <p class="small text-muted mb-0">
                            {{ $activeMerchants }} Merchant aktif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">
                            Jenis Pajak
                        </p>

                        <h3 class="h3 fw-bold">
                            {{ number_format($totalTaxTypes, 0, ',', '.') }}
                        </h3>

                        <p class="small text-muted mb-0">
                            {{ $activeTaxTypes }} jenis pajak aktif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">
                            Total Transaksi
                        </p>

                        <h3 class="h3 fw-bold">
                            {{ number_format($totalTransactions, 0, ',', '.') }}
                        </h3>

                        <p class="small text-muted mb-0">
                            {{ $draftTransactions }} Draft,
                            {{ $completedTransactions }} Completed,
                            {{ $cancelledTransactions }} Cancelled
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Ringkasan Nominal -->
    <section class="mb-4">
        <h2 class="h5 fw-bold mb-3">
            Ringkasan Perhitungan Pajak
        </h2>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">
                            Total Sebelum Pajak
                        </p>

                        <h3 class="h5 fw-bold">
                            Rp {{ \App\Support\RupiahFormatter::format($totalBaseAmount) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">
                            Total Nominal Pajak
                        </p>

                        <h3 class="h5 fw-bold text-primary">
                            Rp {{ \App\Support\RupiahFormatter::format($totalTaxAmount) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card border-0 h-100">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2">
                            Total Setelah Pajak
                        </p>

                        <h3 class="h5 fw-bold">
                            Rp {{ \App\Support\RupiahFormatter::format($totalGrandAmount) }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        <p class="small text-muted mt-3 mb-0">
            Ringkasan mencakup semua status transaksi.
            Nominal pajak untuk transaksi Completed saja:
            <strong>
                Rp {{ \App\Support\RupiahFormatter::format($completedTaxAmount) }}
            </strong>
            (bukan bukti pembayaran).
        </p>
    </section>

    <!-- Ringkasan Per Jenis Pajak -->
    <section class="mb-4">

        <div class="d-flex flex-wrap
                    justify-content-between
                    align-items-center gap-3 mb-3">

            <div>
                <h2 class="h5 fw-bold mb-1">
                    Ringkasan Per Jenis Pajak
                </h2>

                <p class="text-muted small mb-0">
                    Perbandingan nominal sebelum pajak,
                    pajak, dan setelah pajak untuk setiap kategori.
                </p>
            </div>

            <a href="{{ route('tax-types.index') }}"
               class="btn btn-outline-primary rounded-3">
                Kelola Jenis Pajak
            </a>
        </div>

        <div class="row g-3">

            @forelse ($taxTypeSummaries as $item)

                <div class="col-lg-6">
                    <div class="card taxflow-card border-0 h-100">
                        <div class="card-body p-4">

                            <div class="d-flex flex-wrap
                                        justify-content-between
                                        align-items-start gap-2 mb-3">

                                <div>
                                    <p class="small text-muted mb-1">
                                        {{ $item['tax_code'] }}
                                    </p>

                                    <h3 class="h5 fw-bold mb-0">
                                        {{ $item['name'] }}
                                    </h3>
                                </div>

                                @if ($item['is_active'])
                                    <span class="taxflow-status active">
                                        Aktif
                                    </span>
                                @else
                                    <span class="taxflow-status inactive">
                                        Nonaktif
                                    </span>
                                @endif

                            </div>

                            <p class="small mb-3">
                                Tarif saat ini:
                                <strong>
                                    {{ \App\Support\RupiahFormatter::format($item['rate_percent']) }}%
                                </strong>
                                <span class="text-muted">
                                    · {{ $item['transaction_count'] }}
                                    transaksi
                                </span>
                            </p>

                            <div class="border-top pt-3">

                                <div class="d-flex
                                            justify-content-between gap-2 mb-2">
                                    <span class="text-muted small">
                                        Sebelum Pajak
                                    </span>
                                    <strong>
                                        Rp {{ \App\Support\RupiahFormatter::format($item['base_total']) }}
                                    </strong>
                                </div>

                                <div class="d-flex
                                            justify-content-between gap-2 mb-2">
                                    <span class="text-muted small">
                                        Nominal Pajak
                                    </span>
                                    <strong class="text-primary">
                                        Rp {{ \App\Support\RupiahFormatter::format($item['tax_total']) }}
                                    </strong>
                                </div>

                                <div class="d-flex
                                            justify-content-between gap-2 mb-2">
                                    <span class="text-muted small">
                                        Setelah Pajak
                                    </span>
                                    <strong>
                                        Rp {{ \App\Support\RupiahFormatter::format($item['grand_total']) }}
                                    </strong>
                                </div>

                                <div class="d-flex
                                            justify-content-between gap-2">
                                    <span class="text-muted small">
                                        Pajak Efektif
                                    </span>
                                    <strong>
                                        {{ \App\Support\RupiahFormatter::format($item['effective_percent']) }}%
                                    </strong>
                                </div>

                            </div>

                            @if ($item['transaction_count'] === 0)
                                <p class="text-muted small mt-3 mb-0">
                                    Belum ada transaksi pada jenis pajak ini.
                                </p>
                            @endif

                        </div>
                    </div>
                </div>

            @empty

                <div class="col-12">
                    <div class="card taxflow-card border-0">
                        <div class="card-body p-4 text-center">
                            Belum ada jenis pajak terdaftar.
                        </div>
                    </div>
                </div>

            @endforelse

        </div>

        <p class="text-muted small mt-3 mb-0">
            Persentase pajak efektif merupakan rasio nominal
            pajak terhadap nominal sebelum pajak dari transaksi
            yang tercatat, dibulatkan untuk tampilan.
        </p>

    </section>

    <!-- Transaksi Terbaru -->
    <section class="mb-4">
        <div class="card taxflow-card border-0">
            <div class="card-body p-4">

                <div class="d-flex flex-wrap
                            align-items-center
                            justify-content-between gap-3 mb-4">

                    <h2 class="h5 taxflow-card-title mb-0">
                        Transaksi Terbaru
                    </h2>

                    <a href="{{ route('transactions.index') }}"
                       class="btn btn-outline-primary rounded-3">
                        Lihat Semua
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover
                                  align-middle taxflow-table">

                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Merchant</th>
                                <th>Jenis Pajak</th>
                                <th>Sebelum Pajak</th>
                                <th>Pajak</th>
                                <th>Setelah Pajak</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $transaction->transaction_code }}
                                    </td>

                                    <td>
                                        {{ $transaction->merchant?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $transaction->taxType?->name ?? '-' }}
                                    </td>

                                    <td class="text-nowrap">
                                        Rp {{ \App\Support\RupiahFormatter::format($transaction->base_amount) }}
                                    </td>

                                    <td class="text-nowrap">
                                        Rp {{ \App\Support\RupiahFormatter::format($transaction->tax_amount) }}
                                    </td>

                                    <td class="fw-semibold text-nowrap">
                                        Rp {{ \App\Support\RupiahFormatter::format($transaction->total_amount) }}
                                    </td>

                                    <td>
                                        {{ ucfirst($transaction->status) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="text-center py-4 text-muted">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>

</main>

@include('partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
