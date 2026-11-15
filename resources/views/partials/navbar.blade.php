<nav class="navbar taxflow-navbar taxflow-topbar"
     aria-label="Bilah atas TaxFlow">

    <div class="container-fluid px-3 px-lg-4">

        <div class="d-flex align-items-center flex-wrap gap-2">

            @auth
                <button
                    type="button"
                    id="taxflowSidebarToggle"
                    class="btn taxflow-topbar-control"
                    aria-controls="taxflowSidebar"
                    aria-expanded="true">
                    Tutup Menu
                </button>
            @endauth

            <a href="{{ route('dashboard') }}"
               class="navbar-brand taxflow-brand mb-0 me-0">
                TaxFlow
            </a>

            @auth
                <span class="taxflow-user-separator"
                      aria-hidden="true">|</span>

                <span class="taxflow-user-name">
                    {{ auth()->user()->name }}
                </span>
            @endauth

        </div>

        @auth
            <div class="d-flex align-items-center gap-2 ms-auto taxflow-topbar-actions">

                <button
                    type="button"
                    id="taxflowThemeToggle"
                    class="btn taxflow-topbar-control"
                    aria-label="Aktifkan mode gelap"
                    aria-pressed="false">
                    Mode Gelap
                </button>

                <form method="POST"
                      action="{{ route('logout') }}"
                      class="m-0">
                    @csrf

                    <button type="submit"
                            class="btn taxflow-logout">
                        Logout
                    </button>
                </form>

            </div>
        @endauth

    </div>
</nav>

@auth
    @include('partials.dashboard-sidebar')

    <script
        src="{{ asset('js/taxflow-preferences.js') }}"
        defer>
    </script>
@endauth
