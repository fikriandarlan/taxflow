<nav class="navbar navbar-expand-lg taxflow-navbar">
    <div class="container-xl">

        <!-- Identitas Aplikasi dan Pengguna -->
        <div class="d-flex align-items-center gap-3">

            <a class="navbar-brand taxflow-brand"
               href="{{ route('merchants.index') }}">
                TaxFlow
            </a>

            <div class="taxflow-user">
                <span class="taxflow-user-name">
                    {{ auth()->user()->name }}
                </span>
            </div>

        </div>

        <!-- Navigasi Mobile -->
        <button
            class="navbar-toggler taxflow-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#taxflowNavbar"
            aria-controls="taxflowNavbar"
            aria-expanded="false"
            aria-label="Buka navigasi">

            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigasi Utama -->
        <div class="collapse navbar-collapse"
             id="taxflowNavbar">

            <div class="navbar-nav ms-auto
                        align-items-lg-center
                        gap-lg-2 mt-3 mt-lg-0">

                <a
                    href="{{ route('merchants.index') }}"
                    class="nav-link taxflow-nav-link
                    {{ request()->routeIs('merchants.*') ? 'active' : '' }}"
                    @if(request()->routeIs('merchants.*'))
                        aria-current="page"
                    @endif>
                    Merchant
                </a>

                <a
                    href="{{ route('tax-types.index') }}"
                    class="nav-link taxflow-nav-link
                    {{ request()->routeIs('tax-types.*') ? 'active' : '' }}"
                    @if(request()->routeIs('tax-types.*'))
                        aria-current="page"
                    @endif>
                    Jenis Pajak
                </a>

                <a
                    href="{{ route('transactions.index') }}"
                    class="nav-link taxflow-nav-link
                    {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                    @if(request()->routeIs('transactions.*'))
                        aria-current="page"
                    @endif>
                    Transaksi
                </a>

                <div class="taxflow-nav-divider"></div>

                <!-- Logout -->
                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="btn taxflow-logout">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>
