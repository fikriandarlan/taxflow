<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Daftar Merchant — TaxFlow</title>

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
                    Daftar Merchant
                </h1>

                <p class="taxflow-page-subtitle mb-0">
                    Kelola data merchant pada sistem TaxFlow.
                </p>
            </div>

            <a href="{{ route('merchants.create') }}"
               class="btn taxflow-btn-primary">
                + Tambah Merchant
            </a>

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

        <!-- Card Data Merchant -->
        <div class="card taxflow-card border-0">
            <div class="card-body p-4">

                <!-- Header Card Tanpa Ikon -->
                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-center gap-3 mb-4">

                    <h2 class="h5 taxflow-card-title mb-0">
                        Data Merchant
                    </h2>

                    <span class="taxflow-total">
                        Total: {{ $merchants->total() }}
                    </span>

                </div>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-hover
                                  align-middle taxflow-table">

                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Kode Merchant</th>
                                <th scope="col">Nama Merchant</th>
                                <th scope="col">Email</th>
                                <th scope="col">Telepon</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($merchants as $merchant)
                                <tr>
                                    <!-- Nomor Urut -->
                                    <td>
                                        {{ $merchants->firstItem() + $loop->index }}
                                    </td>

                                    <!-- Kode Merchant -->
                                    <td class="fw-semibold">
                                        {{ $merchant->merchant_code }}
                                    </td>

                                    <!-- Nama Merchant -->
                                    <td class="fw-bold">
                                        {{ $merchant->name }}
                                    </td>

                                    <!-- Email -->
                                    <td>
                                        {{ $merchant->email ?: '-' }}
                                    </td>

                                    <!-- Telepon -->
                                    <td>
                                        {{ $merchant->phone ?: '-' }}
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if ($merchant->is_active)
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
                                            href="{{ route('merchants.edit', $merchant->id) }}"
                                            class="btn btn-outline-primary
                                                   btn-sm rounded-3 px-3">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <!-- Empty State -->
                                <tr>
                                    <td colspan="7"
                                        class="text-center py-5">

                                        <h3 class="h6 fw-bold text-secondary">
                                            Belum ada data Merchant
                                        </h3>

                                        <p class="text-muted small mb-3">
                                            Tambahkan Merchant pertama
                                            untuk mulai mengelola data.
                                        </p>

                                        <a
                                            href="{{ route('merchants.create') }}"
                                            class="btn taxflow-btn-primary btn-sm">
                                            + Tambah Merchant
                                        </a>

                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                @if ($merchants->hasPages())
                    <div class="mt-4">
                        {{ $merchants->links('pagination::bootstrap-5') }}
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
