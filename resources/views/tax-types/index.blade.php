<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Jenis Pajak — TaxFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">

            <a href="{{ route('merchants.index') }}"
               class="navbar-brand fw-bold">
                TaxFlow
            </a>

            <div class="d-flex flex-wrap align-items-center gap-3">

                <a href="{{ route('merchants.index') }}"
                   class="text-white text-decoration-none small">
                    Merchant
                </a>

                <a href="{{ route('tax-types.index') }}"
                   class="text-white text-decoration-none small fw-bold"
                   aria-current="page">
                    Jenis Pajak
                </a>

                <a href="{{ route('transactions.index') }}"
                class="text-white text-decoration-none small">
                    Transaksi
                </a>

                <span class="text-white-50 small">
                    {{ auth()->user()->name }}
                </span>

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

        <!-- Judul Halaman -->
        <div class="d-flex flex-wrap
                    justify-content-between
                    align-items-center gap-3 mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    Jenis dan Tarif Pajak
                </h2>

                <p class="text-muted mb-0">
                    Kelola jenis dan tarif pajak simulasi
                    pada sistem TaxFlow.
                </p>
            </div>

            <!-- Tambah Jenis Pajak -->
            <a href="{{ route('tax-types.create') }}"
               class="btn btn-primary">
                + Tambah Jenis Pajak
            </a>

        </div>

        <!-- Informasi Simulasi -->
        <div class="alert alert-info" role="note">
            <strong>Informasi:</strong>
            Tarif yang digunakan merupakan
            data simulasi untuk pembelajaran,
            bukan tarif pajak resmi.
        </div>

        <!-- Notifikasi -->
        @if (session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup notifikasi">
                </button>

            </div>

        @endif

        <!-- Tabel -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-center gap-2 mb-3">

                    <h5 class="mb-0">
                        Daftar Jenis Pajak
                    </h5>

                    <span class="badge bg-primary">
                        Total: {{ $taxTypes->total() }}
                    </span>

                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Kode Pajak</th>
                                <th scope="col">Nama Jenis Pajak</th>
                                <th scope="col">Tarif (%)</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($taxTypes as $taxType)

                                <tr>

                                    <!-- Nomor Urut -->
                                    <td>
                                        {{ $taxTypes->firstItem() + $loop->index }}
                                    </td>

                                    <!-- Kode -->
                                    <td>
                                        {{ $taxType->tax_code }}
                                    </td>

                                    <!-- Nama -->
                                    <td class="fw-semibold">
                                        {{ $taxType->name }}
                                    </td>

                                    <!-- Tarif -->
                                    <td>
                                        {{ number_format(
                                            (float) $taxType->rate_percent,
                                            2,
                                            ',',
                                            '.'
                                        ) }}%
                                    </td>

                                    <!-- Status -->
                                    <td>

                                        @if ($taxType->is_active)

                                            <span class="badge bg-success">
                                                Aktif
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Tidak Aktif
                                            </span>

                                        @endif

                                    </td>

                                    <!-- Tombol Edit -->
                                    <td>
                                        <a
                                            href="{{ route('tax-types.edit', $taxType) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            Edit
                                        </a>
                                    </td>

                                </tr>

                            @empty

                                <!-- Data Kosong -->
                                <tr>
                                    <td colspan="6"
                                        class="text-center py-5">

                                        <h6 class="text-muted mb-2">
                                            Belum ada jenis pajak.
                                        </h6>

                                        <p class="small text-muted mb-3">
                                            Data jenis dan tarif pajak
                                            akan muncul setelah ditambahkan.
                                        </p>

                                        <a href="{{ route('tax-types.create') }}"
                                           class="btn btn-primary btn-sm">
                                            + Tambah Jenis Pajak
                                        </a>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- Pagination -->
                @if ($taxTypes->hasPages())

                    <div class="mt-3">
                        {{ $taxTypes->links('pagination::bootstrap-5') }}
                    </div>

                @endif

            </div>
        </div>

    </main>

    <footer class="text-center text-muted py-4 small">
        TaxFlow — Sistem Manajemen Transaksi Pajak
    </footer>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
