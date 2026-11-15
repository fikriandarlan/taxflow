<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Daftar Transaksi — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="{{ asset('css/taxflow.css') }}"
        rel="stylesheet">
</head>

<body class="taxflow-app">

@include('partials.navbar')

<main class="container-xl taxflow-page">

    <!-- Header halaman -->
    <div class="d-flex flex-wrap
                justify-content-between
                align-items-center
                gap-3 mb-4">

        <div>
            <h1 class="h2 taxflow-page-title mb-2">
                Daftar Transaksi
            </h1>

            <p class="taxflow-page-subtitle mb-0">
                Pantau, urutkan, dan kelola transaksi
                simulasi pada sistem TaxFlow.
            </p>
        </div>

        <a href="{{ route('transactions.create') }}"
           class="btn taxflow-btn-primary">
            + Tambah Transaksi
        </a>

    </div>

    <!-- Informasi simulasi -->
    <div class="alert alert-info
                border-0 rounded-4 mb-4"
         role="note">

        <strong>Informasi:</strong>
        Modul ini digunakan untuk simulasi
        pengelolaan transaksi dan perhitungan pajak.
        Data transaksi belum menggunakan pembayaran
        atau pelaporan pajak resmi.

    </div>

    <!-- Notifikasi sukses bersama -->
    @include('partials.success-modal')

    <!-- Card transaksi -->
    <div class="card taxflow-card border-0">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap
                        justify-content-between
                        align-items-center
                        gap-3 mb-4">

                <div>
                    <h2 class="h5 taxflow-card-title mb-1">
                        Data Transaksi
                    </h2>

                    <p class="text-muted small mb-0">
                        Klik panah pada judul kolom
                        untuk mengurutkan data transaksi.
                    </p>
                </div>

                <span class="taxflow-total">
                    Total:
                    {{ number_format($transactions->total(), 0, ',', '.') }}
                </span>

            </div>

            <!-- Informasi sorting aktif -->
            @php
                $sortLabels = [
                    'no' => 'No.',
                    'date' => 'Tanggal',
                    'tax_type' => 'Jenis Pajak',
                    'base_amount' => 'Nominal Dasar',
                    'tax_amount' => 'Nominal Pajak',
                    'total_amount' => 'Total',
                ];

                $activeSortLabel =
                    $sortLabels[$sort] ?? 'No.';

                $activeDirectionLabel =
                    $direction === 'asc'
                        ? 'Naik'
                        : 'Turun';
            @endphp

            <div class="taxflow-sort-summary mb-3">

                <div class="small">
                    <span class="text-muted">
                        Urutan aktif:
                    </span>

                    <strong>
                        {{ $activeSortLabel }}
                        ({{ $activeDirectionLabel }})
                    </strong>
                </div>

                <a
                    href="{{ route('transactions.index') }}"
                    class="taxflow-sort-reset">
                    Reset Urutan
                </a>

            </div>

            <!-- Tabel -->
            <div class="table-responsive
                        taxflow-transaction-table-wrapper">

                <table class="table table-hover
                              align-middle taxflow-table
                              taxflow-transaction-table">

                    <thead>
                        <tr>

                            @include(
                                'transactions.partials.sortable-heading',
                                [
                                    'label' => 'No.',
                                    'column' => 'no',
                                ]
                            )

                            <th scope="col">
                                Kode Transaksi
                            </th>

                            @include(
                                'transactions.partials.sortable-heading',
                                [
                                    'label' => 'Tanggal',
                                    'column' => 'date',
                                ]
                            )

                            <th scope="col">
                                Merchant
                            </th>

                            @include(
                                'transactions.partials.sortable-heading',
                                [
                                    'label' => 'Jenis Pajak',
                                    'column' => 'tax_type',
                                ]
                            )

                            @include(
                                'transactions.partials.sortable-heading',
                                [
                                    'label' => 'Nominal Dasar',
                                    'column' => 'base_amount',
                                ]
                            )

                            <th scope="col">
                                Tarif
                            </th>

                            @include(
                                'transactions.partials.sortable-heading',
                                [
                                    'label' => 'Nominal Pajak',
                                    'column' => 'tax_amount',
                                ]
                            )

                            @include(
                                'transactions.partials.sortable-heading',
                                [
                                    'label' => 'Total',
                                    'column' => 'total_amount',
                                ]
                            )

                            <th scope="col">
                                Status
                            </th>

                            <th scope="col" class="text-center">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($transactions as $transaction)

                            <tr>

                                <!-- Nomor tampil berdasarkan pagination -->
                                <td class="taxflow-transaction-number">
                                    {{ $transactions->firstItem() + $loop->index }}
                                </td>

                                <!-- Kode transaksi -->
                                <td class="fw-bold text-primary
                                           text-nowrap">
                                    {{ $transaction->transaction_code }}
                                </td>

                                <!-- Tanggal -->
                                <td class="text-nowrap">
                                    {{ $transaction->transaction_date?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <!-- Merchant -->
                                <td>
                                    <div class="fw-semibold">
                                        {{ $transaction->merchant?->name ?? '-' }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $transaction->merchant?->merchant_code ?? '-' }}
                                    </div>
                                </td>

                                <!-- Jenis pajak -->
                                <td>
                                    <div class="fw-semibold">
                                        {{ $transaction->taxType?->name ?? '-' }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $transaction->taxType?->tax_code ?? '-' }}
                                    </div>
                                </td>

                                <!-- Nominal dasar -->
                                <td class="text-nowrap taxflow-money-cell">
                                    Rp {{ \App\Support\RupiahFormatter::format($transaction->base_amount) }}
                                </td>

                                <!-- Tarif snapshot -->
                                <td class="text-nowrap">
                                    {{ \App\Support\RupiahFormatter::format($transaction->tax_rate_percent) }}%
                                </td>

                                <!-- Nominal pajak -->
                                <td class="text-nowrap taxflow-money-cell">
                                    Rp {{ \App\Support\RupiahFormatter::format($transaction->tax_amount) }}
                                </td>

                                <!-- Total -->
                                <td class="fw-bold text-nowrap
                                           taxflow-money-cell">
                                    Rp {{ \App\Support\RupiahFormatter::format($transaction->total_amount) }}
                                </td>

                                <!-- Status -->
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

                                        <span class="badge
                                                     bg-secondary-subtle
                                                     text-secondary-emphasis
                                                     rounded-pill px-3 py-2">
                                            {{ $transaction->status }}
                                        </span>

                                    @endif

                                </td>

                                <!-- Aksi -->
                                <td class="text-center">

                                    @if ($transaction->status === 'draft')

                                        <a
                                            href="{{ route('transactions.edit', $transaction) }}"
                                            class="btn btn-sm
                                                   btn-outline-primary
                                                   rounded-3 px-3">
                                            Edit
                                        </a>

                                    @else

                                        <span class="text-muted small">
                                            &mdash;
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="11"
                                    class="text-center py-5">

                                    <h3 class="h6 fw-bold text-secondary">
                                        Belum ada transaksi
                                    </h3>

                                    <p class="text-muted small mb-3">
                                        Tambahkan transaksi pertama
                                        untuk mulai mengelola data.
                                    </p>

                                    <a
                                        href="{{ route('transactions.create') }}"
                                        class="btn taxflow-btn-primary btn-sm">
                                        + Tambah Transaksi
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())

                <div class="mt-4">
                    {{ $transactions->links('pagination::bootstrap-5') }}
                </div>

            @endif

        </div>

    </div>

</main>

@include('partials.footer')

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
