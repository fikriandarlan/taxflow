<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Jenis dan Tarif Pajak — TaxFlow</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- TaxFlow CSS -->
    <link
        href="{{ asset('css/taxflow.css') }}"
        rel="stylesheet"
    >
</head>

<body class="taxflow-app">

    <!-- Navbar Modern -->
    @include('partials.navbar')

    <main class="container-xl taxflow-page">

        <!-- Header Halaman -->
        <div class="d-flex flex-wrap
                    justify-content-between
                    align-items-center gap-3 mb-4">

            <div>
                <h1 class="h2 taxflow-page-title mb-2">
                    Jenis dan Tarif Pajak
                </h1>

                <p class="taxflow-page-subtitle mb-0">
                    Kelola jenis dan tarif pajak simulasi
                    pada sistem TaxFlow.
                </p>
            </div>

            <a href="{{ route('tax-types.create') }}"
               class="btn taxflow-btn-primary">
                + Tambah Jenis Pajak
            </a>

        </div>

        <!-- Informasi Simulasi -->
        <div class="alert alert-info
                    border-0 rounded-4 mb-4"
             role="note">

            <strong>Informasi:</strong>
            Tarif yang digunakan merupakan data simulasi
            untuk pembelajaran, bukan tarif pajak resmi.
        </div>

        <!-- Notifikasi -->
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

        <!-- Card Daftar Jenis Pajak -->
        <div class="card taxflow-card border-0">
            <div class="card-body p-4">

                <!-- Header Card Tanpa Ikon -->
                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-center gap-3 mb-4">

                    <h2 class="h5 taxflow-card-title mb-0">
                        Daftar Jenis Pajak
                    </h2>

                    <span class="taxflow-total">
                        Total: {{ $taxTypes->total() }}
                    </span>
                </div>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-hover
                                  align-middle taxflow-table">

                        <thead>
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

                                    <!-- Kode Pajak -->
                                    <td class="fw-semibold">
                                        {{ $taxType->tax_code }}
                                    </td>

                                    <!-- Nama Jenis Pajak -->
                                    <td class="fw-bold">
                                        {{ $taxType->name }}
                                    </td>

                                    <!-- Tarif -->
                                    <td class="text-nowrap">
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
                                            <span class="taxflow-status active">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="taxflow-status inactive">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td>
                                        <a
                                            href="{{ route('tax-types.edit', $taxType->id) }}"
                                            class="btn btn-outline-primary
                                                   btn-sm rounded-3 px-3">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <!-- Empty State -->
                                <tr>
                                    <td colspan="6"
                                        class="text-center py-5">

                                        <h3 class="h6 fw-bold text-secondary">
                                            Belum ada jenis pajak
                                        </h3>

                                        <p class="text-muted small mb-3">
                                            Tambahkan jenis pajak simulasi
                                            untuk mulai mengelola tarif.
                                        </p>

                                        <a
                                            href="{{ route('tax-types.create') }}"
                                            class="btn taxflow-btn-primary btn-sm">
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
                    <div class="mt-4">
                        {{ $taxTypes->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>

    </main>

    <!-- Footer Modern -->
    @include('partials.footer')

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>
</html>
