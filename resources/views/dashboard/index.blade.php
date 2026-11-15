<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Dashboard — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="{{ asset('css/taxflow.css') }}"
        rel="stylesheet">
</head>

<body class="taxflow-app">

@include('partials.navbar')

<main class="container-xl taxflow-page taxflow-dashboard-main">

    <!-- Judul Halaman -->
    <header class="taxflow-dashboard-header mb-4">
        <div>
            <p class="taxflow-eyebrow mb-2">
                RINGKASAN OPERASIONAL
            </p>

            <h1 class="taxflow-page-title mb-2">
                Dashboard TaxFlow
            </h1>

            <p class="taxflow-page-subtitle mb-0">
                Pantau Merchant, kategori pajak,
                dan transaksi melalui satu halaman.
            </p>
        </div>
    </header>

    <!-- Informasi -->
    <div class="alert alert-info border-0 rounded-4 mb-4">
        <strong>Informasi:</strong>
        Data merupakan simulasi transaksi TaxFlow.
        Nilai pajak tidak otomatis berarti pajak
        sudah dibayarkan.
    </div>

    <!-- Menu Pengelolaan -->
    <section class="mb-4"
             aria-labelledby="menuTitle">

        <div class="taxflow-section-header">
            <div>
                <h2 id="menuTitle" class="h5 fw-bold mb-1">
                    Menu Pengelolaan
                </h2>
                <p class="text-muted small mb-0">
                    Akses modul pengelolaan TaxFlow.
                </p>
            </div>
        </div>

        <div class="row g-3">
            @foreach ([
                [
                    'title' => 'Merchant',
                    'description' => 'Kelola data dan status usaha.',
                    'route' => 'merchants.index',
                    'button' => 'Buka Merchant'
                ],
                [
                    'title' => 'Jenis Pajak',
                    'description' => 'Kelola kategori serta tarif pajak.',
                    'route' => 'tax-types.index',
                    'button' => 'Buka Jenis Pajak'
                ],
                [
                    'title' => 'Transaksi',
                    'description' => 'Lihat, buat, dan edit transaksi.',
                    'route' => 'transactions.index',
                    'button' => 'Buka Transaksi'
                ]
            ] as $menu)
                <div class="col-md-4">
                    <div class="card taxflow-card taxflow-menu-card
                                border-0 h-100">
                        <div class="card-body">
                            <h3 class="h5 fw-bold mb-2">
                                {{ $menu['title'] }}
                            </h3>

                            <p class="text-muted small mb-3">
                                {{ $menu['description'] }}
                            </p>

                            <a href="{{ route($menu['route']) }}"
                               class="btn taxflow-btn-primary mt-auto">
                                {{ $menu['button'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Statistik Sistem -->
    <section class="mb-4"
             aria-labelledby="statsTitle">

        <div class="taxflow-section-header">
            <div>
                <h2 id="statsTitle" class="h5 fw-bold mb-1">
                    Statistik Sistem
                </h2>
                <p class="small text-muted mb-0">
                    Statistik keseluruhan dari database.
                </p>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="card taxflow-card taxflow-stat-card
                            border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            Total Merchant
                        </p>
                        <h3 class="taxflow-stat-value">
                            {{ number_format($totalMerchants, 0, ',', '.') }}
                        </h3>
                        <p class="small text-muted mb-0">
                            {{ $activeMerchants }} Merchant aktif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card taxflow-stat-card
                            border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            Total Jenis Pajak
                        </p>
                        <h3 class="taxflow-stat-value">
                            {{ number_format($totalTaxTypes, 0, ',', '.') }}
                        </h3>
                        <p class="small text-muted mb-0">
                            {{ $activeTaxTypes }} kategori aktif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card taxflow-card taxflow-stat-card
                            border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            Total Transaksi
                        </p>
                        <h3 class="taxflow-stat-value">
                            {{ number_format($totalTransactions, 0, ',', '.') }}
                        </h3>
                        <p class="small text-muted mb-0">
                            {{ $draftTransactions }} Draft ·
                            {{ $completedTransactions }} Completed ·
                            {{ $cancelledTransactions }} Cancelled
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Ringkasan Perhitungan Pajak -->
    <section class="mb-4"
             aria-labelledby="summaryTitle">

        <div class="card taxflow-card border-0">
            <div class="card-body p-4 p-lg-5">

                <div class="taxflow-section-header">
                    <div>
                        <h2 id="summaryTitle"
                            class="h5 fw-bold mb-1">
                            Ringkasan Perhitungan Pajak
                        </h2>

                        <p class="small text-muted mb-0">
                            Filter jenis pajak dan periode,
                            lalu bandingkan nominal sebelum
                            serta setelah pajak.
                        </p>
                    </div>
                </div>

                <!-- Filter -->
                <form action="{{ route('dashboard') }}"
                      method="GET"
                      class="row g-3 align-items-end mb-4">

                    <div class="col-lg-5">
                        <label for="tax_type"
                               class="form-label fw-semibold">
                            Jenis Pajak
                        </label>

                        <select name="tax_type"
                                id="tax_type"
                                class="form-select rounded-3">
                            <option value="all"
                                    @selected($selectedTaxType === 'all')>
                                Semua Jenis Pajak
                            </option>

                            @foreach ($taxTypes as $taxType)
                                <option
                                    value="{{ $taxType->id }}"
                                    @selected(
                                        (string) $selectedTaxType
                                        === (string) $taxType->id
                                    )>
                                    {{ $taxType->name }}
                                    ({{ \App\Support\RupiahFormatter::format($taxType->rate_percent) }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label for="period"
                               class="form-label fw-semibold">
                            Periode Ringkasan
                        </label>

                        <select name="period"
                                id="period"
                                class="form-select rounded-3">
                            @foreach ($periodOptions as $key => $label)
                                <option value="{{ $key }}"
                                        @selected($period === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <button type="submit"
                                class="btn taxflow-btn-primary w-100">
                            Tampilkan Ringkasan
                        </button>
                    </div>

                </form>

                <!-- Filter aktif -->
                <div class="taxflow-filter-description mb-4">
                    <div>
                        <strong>{{ $periodOptions[$period] }}</strong>
                        <span class="text-muted">
                            · {{ $filteredTransactionCount }}
                            transaksi ditemukan
                        </span>
                    </div>

                    @if ($startDate !== null)
                        <div class="small text-muted mt-1">
                            {{ $startDate->format('d/m/Y H:i') }}
                            –
                            {{ $now->format('d/m/Y H:i') }}
                        </div>
                    @else
                        <div class="small text-muted mt-1">
                            Seluruh tanggal transaksi yang tersedia.
                        </div>
                    @endif

                    @if ($selectedTaxType !== 'all'
                         || $period !== 'all')
                        <a href="{{ route('dashboard') }}"
                           class="small fw-semibold text-decoration-none">
                            Reset Filter
                        </a>
                    @endif
                </div>

                @if ($filteredTransactionCount === 0)
                    <div class="alert alert-warning
                                border-0 rounded-3 mb-4">
                        Tidak ada transaksi untuk jenis pajak
                        dan periode yang dipilih. Coba memilih
                        <strong>Semua Waktu</strong> atau
                        <strong>Semua Jenis Pajak</strong>.
                    </div>
                @endif

                <!-- Nominal -->
                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="taxflow-finance-card">
                            <p class="small text-muted mb-2">
                                Total Sebelum Pajak
                            </p>
                            <h3 class="taxflow-finance-value">
                                Rp {{ \App\Support\RupiahFormatter::format($totalBaseAmount) }}
                            </h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="taxflow-finance-card">
                            <p class="small text-muted mb-2">
                                Total Nominal Pajak
                            </p>
                            <h3 class="taxflow-finance-value text-primary">
                                Rp {{ \App\Support\RupiahFormatter::format($totalTaxAmount) }}
                            </h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="taxflow-finance-card">
                            <p class="small text-muted mb-2">
                                Total Setelah Pajak
                            </p>
                            <h3 class="taxflow-finance-value">
                                Rp {{ \App\Support\RupiahFormatter::format($totalGrandAmount) }}
                            </h3>
                        </div>
                    </div>

                </div>

                <!-- Persentase efektif -->
                <div class="taxflow-effective-tax mt-3">
                    <div>
                        <strong>Persentase Pajak Efektif</strong>
                        <p class="small text-muted mb-0">
                            Nominal pajak dibandingkan dengan
                            nominal sebelum pajak.
                        </p>
                    </div>

                    <strong class="taxflow-effective-value">
                        @if (bccomp($totalBaseAmount, '0', 2) > 0)
                            {{ \App\Support\RupiahFormatter::format($effectivePercent) }}%
                        @else
                            —
                        @endif
                    </strong>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    Angka merupakan simulasi seluruh status
                    transaksi. Bukan laporan penerimaan pajak resmi.
                </p>
            </div>
        </div>
    </section>

    <!-- Per Kategori -->
    <section class="mb-4">
        <div class="taxflow-section-header">
            <div>
                <h2 class="h5 fw-bold mb-1">
                    Ringkasan Per Jenis Pajak
                </h2>
                <p class="small text-muted mb-0">
                    Semua kategori pada periode
                    {{ $periodOptions[$period] }}.
                </p>
            </div>
        </div>

        <div class="row g-3">
            @forelse ($taxTypeSummaries as $item)
                <div class="col-lg-6">
                    <div class="card taxflow-card
                                taxflow-category-card border-0 h-100">
                        <div class="card-body p-4">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-start gap-3 mb-3">
                                <div>
                                    <p class="small text-muted mb-1">
                                        {{ $item['tax_code'] }}
                                    </p>
                                    <h3 class="h5 fw-bold mb-0">
                                        {{ $item['name'] }}
                                    </h3>
                                </div>

                                <span class="taxflow-status
                                    {{ $item['is_active'] ? 'active' : 'inactive' }}">
                                    {{ $item['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>

                            <p class="small text-muted">
                                Tarif saat ini:
                                <strong>
                                    {{ \App\Support\RupiahFormatter::format($item['rate_percent']) }}%
                                </strong>
                                · {{ $item['transaction_count'] }} transaksi
                            </p>

                            <div class="taxflow-category-details">

                                <div class="taxflow-amount-row">
                                    <span>Sebelum Pajak</span>
                                    <strong>
                                        Rp {{ \App\Support\RupiahFormatter::format($item['base_total']) }}
                                    </strong>
                                </div>

                                <div class="taxflow-amount-row">
                                    <span>Nominal Pajak</span>
                                    <strong class="text-primary">
                                        Rp {{ \App\Support\RupiahFormatter::format($item['tax_total']) }}
                                    </strong>
                                </div>

                                <div class="taxflow-amount-row">
                                    <span>Setelah Pajak</span>
                                    <strong>
                                        Rp {{ \App\Support\RupiahFormatter::format($item['grand_total']) }}
                                    </strong>
                                </div>

                                <div class="taxflow-amount-row">
                                    <span>Pajak Efektif</span>
                                    <strong>
                                        @if (bccomp($item['base_total'], '0', 2) > 0)
                                            {{ \App\Support\RupiahFormatter::format($item['effective_percent']) }}%
                                        @else
                                            —
                                        @endif
                                    </strong>
                                </div>
                            </div>

                            @if ($item['transaction_count'] === 0)
                                <p class="small text-muted mt-3 mb-0">
                                    Belum ada transaksi pada periode ini.
                                </p>
                            @endif

                            <a href="{{ route('dashboard', [
                                    'tax_type' => $item['id'],
                                    'period' => $period
                                ]) }}"
                               class="btn btn-outline-primary
                                      rounded-3 w-100 mt-4">
                                Lihat Ringkasan Kategori
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="taxflow-empty-state">
                        Belum ada kategori pajak.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Transaksi terbaru -->
    <section class="mb-4">
        <div class="card taxflow-card border-0">
            <div class="card-body p-4">

                <div class="taxflow-section-header">
                    <div>
                        <h2 class="h5 fw-bold mb-1">
                            Transaksi Terbaru
                        </h2>
                        <p class="small text-muted mb-0">
                            Lima transaksi terakhir dari seluruh periode.
                        </p>
                    </div>

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

                                    <td class="text-nowrap text-primary">
                                        Rp {{ \App\Support\RupiahFormatter::format($transaction->tax_amount) }}
                                    </td>

                                    <td class="text-nowrap fw-bold">
                                        Rp {{ \App\Support\RupiahFormatter::format($transaction->total_amount) }}
                                    </td>

                                    <td>
                                        {{ ucfirst($transaction->status) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="text-center text-muted py-4">
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

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
