{{-- ==========================================
     TAXFLOW — SUCCESS NOTIFICATION MODAL

     Ditampilkan hanya ketika Laravel
     memiliki session flash 'success'.
     ========================================== --}}

@if (session('success'))

    <div
        class="modal fade taxflow-success-modal"
        id="taxflowSuccessModal"
        tabindex="-1"
        aria-labelledby="taxflowSuccessTitle"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="true">

        <div
            class="modal-dialog
                   modal-dialog-centered
                   taxflow-success-dialog">

            <div class="modal-content">

                <div class="modal-body text-center">

                    <!-- Tanda berhasil -->
                    <div
                        class="taxflow-success-symbol"
                        aria-hidden="true">
                        &#10003;
                    </div>

                    <!-- Judul -->
                    <h2
                        id="taxflowSuccessTitle"
                        class="taxflow-success-title">
                        Berhasil!
                    </h2>

                    <!-- Pesan dari Laravel -->
                    <p class="taxflow-success-message">
                        {{ session('success') }}
                    </p>

                    <!-- Tombol Tutup -->
                    <button
                        type="button"
                        class="btn taxflow-btn-primary
                               taxflow-success-close"
                        data-bs-dismiss="modal">
                        Tutup
                    </button>

                </div>

            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            const modalElement =
                document.getElementById(
                    'taxflowSuccessModal'
                );

            if (
                modalElement &&
                typeof bootstrap !== 'undefined'
            ) {
                const modal = bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );

                modal.show();
            }
        });
    </script>

@endif
