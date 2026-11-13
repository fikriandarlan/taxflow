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

                <!-- Navigasi Merchant -->
                <a href="{{ route('merchants.index') }}"
                   class="text-white text-decoration-none small">
                    Merchant
                </a>

                <!-- Navigasi Jenis Pajak -->
                <a href="{{ route('tax-types.index') }}"
                   class="text-white text-decoration-none small">
                    Jenis Pajak
                </a>

                <!-- Navigasi Transaksi -->
                <a href="{{ route('transactions.index') }}"
                   class="text-white text-decoration-none small fw-bold"
                   aria-current="page">
                    Transaksi
                </a>

                <!-- Pengguna -->
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

    <!-- Konten Utama -->
    <main class="container py-5">

        <!-- Header Halaman -->
        <div class="d-flex flex-wrap
                    justify-content-between
                    align-items-center gap-3 mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    Daftar Transaksi
                </h2>

                <p class="text-muted mb-0">
                    Pantau transaksi simulasi
                    pada sistem TaxFlow.
                </p>
            </div>

            <!-- Akan diaktifkan pada sesi Create -->
            <a href="{{ route('transactions.create') }}"
            class="btn btn-primary">
                + Tambah Transaksi
            </a>

        </div>

        <!-- Informasi -->
        <div class="alert alert-info" role="note">
            <strong>Informasi:</strong>
            Modul ini digunakan untuk simulasi
            pengelolaan transaksi dan perhitungan pajak.
            Data transaksi belum menggunakan
            pembayaran atau pelaporan pajak resmi.
        </div>

        <!-- Notifikasi untuk sesi berikutnya -->
        @if (session('success'))
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup notifikasi">
                </button>
            </div>
        @endif

        <!-- Daftar Transaksi -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-center gap-2 mb-3">

                    <h5 class="mb-0">
                        Data Transaksi
                    </h5>

                    <span class="badge bg-primary">
                        Total: {{ $transactions->total() }}
                    </span>

                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">ID Transaksi</th>
                                <th scope="col">Kode Merchant</th>
                                <th scope="col">Nama Merchant</th>
                                <th scope="col">Kode Jenis Pajak</th>
                                <th scope="col">Nama Jenis Pajak</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($transactions as $transaction)

                                <tr>

                                    <!-- Nomor Urut -->
                                    <td>
                                        {{ $transactions->firstItem() + $loop->index }}
                                    </td>

                                    <!-- ID Transaksi -->
                                    <td>
                                        {{ $transaction->id }}
                                    </td>

                                    <!-- Merchant -->
                                    <td>
                                        {{ $transaction->merchant?->merchant_code ?? '-' }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $transaction->merchant?->name ?? '-' }}
                                    </td>

                                    <!-- Jenis Pajak -->
                                    <td>
                                        {{ $transaction->taxType?->tax_code ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $transaction->taxType?->name ?? '-' }}
                                    </td>

                                </tr>

                            @empty

                                <!-- Belum Ada Transaksi -->
                                <tr>
                                    <td colspan="6"
                                        class="text-center py-5">

                                        <h6 class="text-muted mb-2">
                                            Belum ada transaksi.
                                        </h6>

                                        <p class="small text-muted mb-0">
                                            Transaksi akan ditampilkan
                                            setelah fitur Create
                                            diimplementasikan.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                @if ($transactions->hasPages())
                    <div class="mt-3">
                        {{ $transactions->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="text-center text-muted py-4 small">
        TaxFlow — Sistem Manajemen Transaksi Pajak
    </footer>

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
