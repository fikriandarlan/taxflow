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

    <link
        href="{{ asset('css/taxflow.css') }}"
        rel="stylesheet"
    >
</head>

<body class="taxflow-app">

    @include('partials.navbar')

    <main class="container-xl taxflow-page">

        <!-- Header Dashboard -->
        <div class="mb-4">

            <h1 class="h2 taxflow-page-title mb-2">
                Dashboard
            </h1>

            <p class="taxflow-page-subtitle mb-0">
                Ringkasan data operasional dan
                transaksi simulasi TaxFlow.
            </p>

        </div>

        <!-- Informasi -->
        <div class="alert alert-info border-0 rounded-4 mb-4"
             role="note">

            <strong>Informasi:</strong>
            Seluruh angka berasal dari database TaxFlow.
            Total nominal pajak mencakup semua status
            transaksi dan bukan merupakan pajak
            yang telah dibayarkan.

        </div>

        <!-- Statistik -->
        <div class="row g-4 mb-4">

            <!-- Merchant -->
            <div class="col-md-6 col-xl-3">

                <div class="card taxflow-card border-0 h-100">

                    <div class="card-body p-4">

                        <p class="text-muted small mb-2">
                            Total Merchant
                        </p>

                        <h2 class="h3 fw-bold mb-2">
                            {{ $totalMerchants }}
                        </h2>

                        <p class="text-muted small mb-0">
                            {{ $activeMerchants }} Merchant aktif
                        </p>

                    </div>

                </div>

            </div>

            <!-- Jenis Pajak -->
            <div class="col-md-6 col-xl-3">

                <div class="card taxflow-card border-0 h-100">

                    <div class="card-body p-4">

                        <p class="text-muted small mb-2">
                            Total Jenis Pajak
                        </p>

                        <h2 class="h3 fw-bold mb-2">
                            {{ $totalTaxTypes }}
                        </h2>

                        <p class="text-muted small mb-0">
                            {{ $activeTaxTypes }} jenis pajak aktif
                        </p>

                    </div>

                </div>

            </div>

            <!-- Transaksi -->
            <div class="col-md-6 col-xl-3">

                <div class="card taxflow-card border-0 h-100">

                    <div class="card-body p-4">

                        <p class="text-muted small mb-2">
                            Total Transaksi
                        </p>

                        <h2 class="h3 fw-bold mb-2">
                            {{ $totalTransactions }}
                        </h2>

                        <p class="text-muted small mb-0">
                            {{ $draftTransactions }} transaksi Draft
                        </p>

                    </div>

                </div>

            </div>

            <!-- Nominal Pajak -->
            <div class="col-md-6 col-xl-3">

                <div class="card taxflow-card border-0 h-100">

                    <div class="card-body p-4">

                        <p class="text-muted small mb-2">
                            Total Pajak Simulasi
                        </p>

                        <h2 class="h4 fw-bold mb-2">
                            Rp {{ \App\Support\RupiahFormatter::format($totalTaxAmount) }}
                        </h2>

                        <p class="text-muted small mb-0">
                            Seluruh status transaksi
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Transaksi Terbaru -->
        <div class="card taxflow-card border-0">

            <div class="card-body p-4">

                <div class="d-flex flex-wrap
                            align-items-center
                            justify-content-between
                            gap-3 mb-4">

                    <div>

                        <h2 class="h5 taxflow-card-title mb-2">
                            Transaksi Terbaru
                        </h2>

                        <p class="text-muted small mb-0">
                            Lima transaksi terakhir berdasarkan
                            urutan pencatatan.
                        </p>

                    </div>

                    <a
                        href="{{ route('transactions.index') }}"
                        class="btn btn-outline-primary rounded-3">
                        Lihat Semua Transaksi
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
                                <th>Nominal Dasar</th>
                                <th>Pajak</th>
                                <th>Total</th>
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

                                    <td class="text-nowrap fw-semibold">
                                        Rp {{ \App\Support\RupiahFormatter::format($transaction->total_amount) }}
                                    </td>

                                    <td>
                                        @if ($transaction->status === 'draft')

                                            <span class="taxflow-status inactive">
                                                Draft
                                            </span>

                                        @elseif ($transaction->status === 'completed')

                                            <span class="taxflow-status active">
                                                Selesai
                                            </span>

                                        @elseif ($transaction->status === 'cancelled')

                                            <span class="badge
                                                         bg-danger-subtle
                                                         text-danger-emphasis
                                                         rounded-pill px-3 py-2">
                                                Dibatalkan
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                {{ $transaction->status }}
                                            </span>

                                        @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7"
                                        class="text-center text-muted py-5">
                                        Belum ada transaksi.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </main>

    @include('partials.footer')

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
