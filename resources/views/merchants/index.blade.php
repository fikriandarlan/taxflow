
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

    <!-- TaxFlow Custom CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('css/taxflow.css') }}"
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
                <h1 class="taxflow-page-title h2 mb-2">
                    Daftar Merchant
                </h1>

                <p class="taxflow-page-subtitle mb-0">
                    Kelola data merchant pada sistem TaxFlow.
                </p>
            </div>

            <!-- Tambah Merchant -->
            <a href="{{ route('merchants.create') }}"
               class="btn taxflow-btn-primary">

                <span class="me-1">+</span>
                Tambah Merchant

            </a>

        </div>

        <!-- Notifikasi Berhasil -->
        @if (session('success'))

            <div
                class="alert alert-success
                       alert-dismissible fade show mb-4"
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

        <!-- Card Data Merchant -->
        <div class="card taxflow-card border-0">

            <div class="card-body p-4">

                <!-- Card Header -->
                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-center
                            gap-3 mb-4">

                    <div class="d-flex
                                align-items-center gap-3">

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

                            M

                        </div>

                        <h2 class="h5 taxflow-card-title mb-0">
                            Data Merchant
                        </h2>

                    </div>

                    <span class="taxflow-total">
                        Total: {{ $merchants->total() }}
                    </span>

                </div>

                <!-- Tabel -->
                <div class="table-responsive">

                    <table
                        class="table table-hover
                               align-middle taxflow-table">

                        <thead>
                            <tr>
                                <th scope="col">No.</th>

                                <th scope="col">
                                    Kode Merchant
                                </th>

                                <th scope="col">
                                    Nama Merchant
                                </th>

                                <th scope="col">Email</th>

                                <th scope="col">Telepon</th>

                                <th scope="col">Status</th>

                                <th scope="col">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($merchants as $merchant)

                                <tr>

                                    <!-- Nomor -->
                                    <td>
                                        {{ $merchants->firstItem() + $loop->index }}
                                    </td>

                                    <!-- Kode -->
                                    <td>
                                        <span class="fw-semibold">
                                            {{ $merchant->merchant_code }}
                                        </span>
                                    </td>

                                    <!-- Nama -->
                                    <td>
                                        <span class="fw-bold">
                                            {{ $merchant->name }}
                                        </span>
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

                                            <span
                                                class="taxflow-status active">

                                                <span class="me-1">
                                                    ●
                                                </span>

                                                Aktif

                                            </span>

                                        @else

                                            <span
                                                class="taxflow-status inactive">

                                                <span class="me-1">
                                                    ●
                                                </span>

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

                                    <td
                                        colspan="7"
                                        class="text-center py-5">

                                        <div class="mb-2"
                                             style="font-size: 2rem;">
                                            📋
                                        </div>

                                        <h3
                                            class="h6 fw-bold text-secondary">
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

    <!-- Footer Bersama -->
    @include('partials.footer')

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
