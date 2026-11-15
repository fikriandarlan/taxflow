/*
 * TaxFlow UI Preferences
 * - Sidebar show/hide
 * - Light/Dark mode
 * - Realtime Jakarta clock (WIB)
 */

(() => {
    'use strict';

    const root = document.body;

    const sidebar = document.getElementById(
        'taxflowSidebar'
    );

    const sidebarButton = document.getElementById(
        'taxflowSidebarToggle'
    );

    const themeButton = document.getElementById(
        'taxflowThemeToggle'
    );

    const clock = document.getElementById(
        'taxflowJakartaTime'
    );

    const date = document.getElementById(
        'taxflowJakartaDate'
    );

    // Simpan preferensi di browser.
    function readPreference(name) {
        try {
            return localStorage.getItem(name);
        } catch (error) {
            return null;
        }
    }

    function savePreference(name, value) {
        try {
            localStorage.setItem(name, value);
        } catch (error) {
            // UI tetap berfungsi tanpa localStorage.
        }
    }

    /*
     * Sidebar
     */
    function applySidebar(collapsed) {
        root.classList.toggle(
            'taxflow-sidebar-collapsed',
            collapsed
        );

        if (sidebar) {
            sidebar.setAttribute(
                'aria-hidden',
                String(collapsed)
            );

            sidebar.inert = collapsed;
        }

        if (sidebarButton) {
            sidebarButton.textContent = collapsed
                ? 'Tampilkan Menu'
                : 'Tutup Menu';

            sidebarButton.setAttribute(
                'aria-expanded',
                String(!collapsed)
            );
        }
    }

    /*
     * Theme
     */
    function applyTheme(theme) {
        const dark = theme === 'dark';

        root.setAttribute(
            'data-tf-theme',
            dark ? 'dark' : 'light'
        );

        document.documentElement.setAttribute(
            'data-bs-theme',
            dark ? 'dark' : 'light'
        );

        if (themeButton) {
            themeButton.textContent = dark
                ? 'Mode Terang'
                : 'Mode Gelap';

            themeButton.setAttribute(
                'aria-label',
                dark
                    ? 'Aktifkan mode terang'
                    : 'Aktifkan mode gelap'
            );

            themeButton.setAttribute(
                'aria-pressed',
                String(dark)
            );
        }
    }

    /*
     * Initial preferences
     */
    applySidebar(
        readPreference('taxflow.sidebar') === 'collapsed'
    );

    applyTheme(
        readPreference('taxflow.theme') === 'dark'
            ? 'dark'
            : 'light'
    );

    /*
     * Sidebar button
     */
    sidebarButton?.addEventListener('click', () => {
        const collapsed = !root.classList.contains(
            'taxflow-sidebar-collapsed'
        );

        applySidebar(collapsed);

        savePreference(
            'taxflow.sidebar',
            collapsed ? 'collapsed' : 'expanded'
        );
    });

    /*
     * Theme button
     */
    themeButton?.addEventListener('click', () => {
        const next =
            root.getAttribute('data-tf-theme') === 'dark'
                ? 'light'
                : 'dark';

        applyTheme(next);

        savePreference('taxflow.theme', next);
    });

    /*
     * Jakarta realtime clock
     * Asia/Jakarta = WIB (UTC+7)
     */
    const timeFormatter = new Intl.DateTimeFormat(
        'id-ID',
        {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hourCycle: 'h23'
        }
    );

    const dateFormatter = new Intl.DateTimeFormat(
        'id-ID',
        {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }
    );

    function updateJakartaClock() {
        const now = new Date();

        if (clock) {
            clock.textContent = timeFormatter.format(now);

            clock.setAttribute(
                'datetime',
                now.toISOString()
            );
        }

        if (date) {
            date.textContent = dateFormatter.format(now);
        }
    }

    if (clock || date) {
        updateJakartaClock();

        setInterval(updateJakartaClock, 1000);
    }
})();
