<style>
    .container5 {
        position: fixed;
        top: 60px;
        left: 40%;
    }
    
    .d-flex {
        z-index: 11;
    }

    .toast-custom {
        display: flex;
        align-items: center;
        background-color: black !important;
        color: white !important;
        border-bottom: 2px solid red !important;
        padding: 10px 24px !important;
        max-width: 500px;
        width: auto;
        font-size: 1.15rem;
        border: none;
        border-radius: 0.375rem;
    }

    .toast-custom .toast-body {
        flex-grow: 1;
    }

    .toast-custom[data-bs-autohide="true"] {
        animation: fadeOut 500ms ease-out 5s forwards;
        /* Custom fade-out effect */
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }
</style>

<?php
    if(isset($_SESSION['message'])) {
    ?>
    <div class="container5">
    <div class="d-flex">
        <div id="liveToast" class="toast-custom" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="500000" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <!--<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>-->
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            var toastEl = document.getElementById('liveToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
    </script>
    <?php
    unset($_SESSION['message']);
    }
?>