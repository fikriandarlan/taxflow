<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Daftar Transaksi — TaxFlow</title>

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

        <!-- Header Halaman -->
        <div class="d-flex flex-wrap
                    justify-content-between
                    align-items-center
                    gap-3 mb-4">

            <div>

                <h1 class="h2 taxflow-page-title mb-2">
                    Daftar Transaksi
                </h1>

                <p class="taxflow-page-subtitle mb-0">
                    Pantau transaksi simulasi
                    pada sistem TaxFlow.
                </p>

            </div>

            <!-- Tambah Transaksi -->
            <a href="{{ route('transactions.create') }}"
               class="btn taxflow-btn-primary">

                <span class="me-1">+</span>
                Tambah Transaksi

            </a>

        </div>

        <!-- Informasi Simulasi -->
        <div class="alert alert-info
                    border-0 rounded-4 mb-4"
             role="note">

            <strong>Informasi:</strong>
            Modul ini digunakan untuk simulasi
            pengelolaan transaksi dan perhitungan pajak.
            Data transaksi belum menggunakan pembayaran
            atau pelaporan pajak resmi.

        </div>

        <!-- Notifikasi Berhasil -->
        @if (session('success'))

            <div class="alert alert-success
                        alert-dismissible fade show
                        rounded-4 mb-4"
                 role="alert">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup notifikasi">
                </button>

            </div>

        @endif

        <!-- Card Data Transaksi -->
        <div class="card taxflow-card border-0">

            <div class="card-body p-4">

                <!-- Header Card -->
                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-center
                            gap-3 mb-4">

                    <div class="d-flex
                                align-items-center
                                gap-3">

                        <!-- Identitas Modul -->
                        <div
                            class="d-flex align-items-center
                                   justify-content-center
                                   rounded-3"
                            style="
                                width: 44px;
                                height: 44px;
                                background: #e8f2ff;
                                color: #075be8;
                                font-size: 1.3rem;
                                font-weight: 800;
                            ">

                            T

                        </div>

                        <div>

                            <h2 class="h5 taxflow-card-title mb-1">
                                Data Transaksi
                            </h2>

                            <p class="text-muted small mb-0">
                                Daftar transaksi yang tercatat
                                di database TaxFlow.
                            </p>

                        </div>

                    </div>

                    <!-- Total Data -->
                    <span class="taxflow-total">
                        Total: {{ $transactions->total() }}
                    </span>

                </div>

                <!-- Tabel Transaksi -->
                <div class="table-responsive">

                    <table class="table table-hover
                                  align-middle taxflow-table">

                        <thead>
                            <tr>

                                <th scope="col">
                                    No.
                                </th>

                                <th scope="col">
                                    Kode Transaksi
                                </th>

                                <th scope="col">
                                    Tanggal
                                </th>

                                <th scope="col">
                                    Merchant
                                </th>

                                <th scope="col">
                                    Jenis Pajak
                                </th>

                                <th scope="col">
                                    Nominal Dasar
                                </th>

                                <th scope="col">
                                    Tarif
                                </th>

                                <th scope="col">
                                    Nominal Pajak
                                </th>

                                <th scope="col">
                                    Total
                                </th>

                                <th scope="col">
                                    Status
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($transactions as $transaction)

                                <tr>

                                    <!-- Nomor Urut -->
                                    <td>
                                        {{ $transactions->firstItem() + $loop->index }}
                                    </td>

                                    <!-- Kode Transaksi -->
                                    <td>
                                        <span class="fw-bold text-primary">
                                            {{ $transaction->transaction_code }}
                                        </span>
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

                                    <!-- Jenis Pajak -->
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $transaction->taxType?->name ?? '-' }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $transaction->taxType?->tax_code ?? '-' }}
                                        </div>

                                    </td>

                                    <!-- Nominal Dasar -->
                                    <td class="text-nowrap">
                                        Rp {{ $transaction->base_amount }}
                                    </td>

                                    <!-- Tarif Snapshot -->
                                    <td class="text-nowrap">
                                        {{ $transaction->tax_rate_percent }}%
                                    </td>

                                    <!-- Nominal Pajak -->
                                    <td class="text-nowrap">
                                        Rp {{ $transaction->tax_amount }}
                                    </td>

                                    <!-- Total Transaksi -->
                                    <td class="text-nowrap fw-bold">
                                        Rp {{ $transaction->total_amount }}
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

                                </tr>

                            @empty

                                <!-- Belum Ada Transaksi -->
                                <tr>

                                    <td colspan="10"
                                        class="text-center py-5">

                                        <div class="mb-3"
                                             style="font-size: 2rem;">
                                            📋
                                        </div>

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

    <!-- Footer Bersama -->
    @include('partials.footer')

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
