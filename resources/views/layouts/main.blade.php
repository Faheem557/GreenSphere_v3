<!doctype html>
<html lang="en" dir="ltr">

<head>
@include('layouts.head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    /* Custom toastr styling for Bootstrap 5 */
    .toast-top-right {
        top: 1rem;
        right: 1rem;
    }
    #toast-container > div {
        opacity: 1;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .toast-success {
        background-color: #198754;
    }
    .toast-error {
        background-color: #dc3545;
    }
    .toast-info {
        background-color: #0dcaf0;
    }
    .toast-warning {
        background-color: #ffc107;
    }
</style>
</head>

<body class="app sidebar-mini ltr light-mode">

@include('layouts.loader')

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

        @include('layouts.header')

        @include('layouts.sidebar')

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                @yield('maincontent')
                        
                </div>
            </div>
            <!--app-content close-->

        </div>

        @include('layouts.sidebarright')

        @include('layouts.footer')

    </div>

<<<<<<< HEAD
    <!-- Scripts in correct order -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    
=======
    <!-- Core JS Files -->
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

>>>>>>> 865d8f054825cc550d859cd9305be146439ead36
    <!-- Initialize toastr -->
    <script>
    $(document).ready(function() {
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Handle Laravel flash messages
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}", 'Success');
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}", 'Error');
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}", 'Information');
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}", 'Warning');
        @endif
    });
    </script>

    @include('layouts.vendorscripts')
    @stack('scripts')

</body>

</html>