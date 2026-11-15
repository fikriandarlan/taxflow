<aside
    id="taxflowSidebar"
    class="taxflow-global-sidebar"
    aria-label="Sidebar aplikasi TaxFlow">

    <div class="taxflow-sidebar-inner">

        <p class="taxflow-sidebar-heading">
            MENU UTAMA
        </p>

        <nav class="taxflow-sidebar-nav"
             aria-label="Navigasi utama">

            <a href="{{ route('dashboard') }}"
               class="taxflow-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               @if(request()->routeIs('dashboard'))
                   aria-current="page"
               @endif>
                Dashboard
            </a>

            <a href="{{ route('merchants.index') }}"
               class="taxflow-sidebar-link {{ request()->routeIs('merchants.*') ? 'active' : '' }}"
               @if(request()->routeIs('merchants.*'))
                   aria-current="page"
               @endif>
                Merchant
            </a>

            <a href="{{ route('tax-types.index') }}"
               class="taxflow-sidebar-link {{ request()->routeIs('tax-types.*') ? 'active' : '' }}"
               @if(request()->routeIs('tax-types.*'))
                   aria-current="page"
               @endif>
                Jenis Pajak
            </a>

            <a href="{{ route('transactions.index') }}"
               class="taxflow-sidebar-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
               @if(request()->routeIs('transactions.*'))
                   aria-current="page"
               @endif>
                Transaksi
            </a>

        </nav>

        <!-- Jam Jakarta, berjarak dari menu Transaksi -->
        <section class="taxflow-jakarta-clock"
                 aria-label="Jam Jakarta">

            <p class="taxflow-clock-label">
                WAKTU INDONESIA BARAT
            </p>

            <p class="taxflow-clock-time">
                <time id="taxflowJakartaTime"
                      aria-label="Waktu Jakarta">
                    --:--:--
                </time>

                <span>WIB</span>
            </p>

            <p class="taxflow-clock-date"
               id="taxflowJakartaDate">
                Jakarta, Indonesia
            </p>

        </section>

        <div class="taxflow-sidebar-bottom">
            <strong>TaxFlow</strong>

            <p class="mb-0">
                Sistem Manajemen Transaksi Pajak
            </p>
        </div>

    </div>
</aside>
