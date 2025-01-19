<!--
=========================================================
* Argon Dashboard 2 - v2.0.4
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logoDLH2.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDLH2.png') }}">
    <title>
        DLH KOTA MALANG
    </title>

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css" />
</head>

<body class="g-sidenav-show bg-p">
    <div class="position-absolute w-100"></div>
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Content -->
        @yield('content')
    </main>

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.0.4') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Toggle Sidenav --}}
    <script>
        const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
        const iconSidenav = document.getElementById('iconSidenav');
        const sidenav = document.getElementById('sidenav-main');
        let body = document.getElementsByTagName('body')[0];
        let className = 'g-sidenav-pinned';

        if (iconNavbarSidenav) {
            iconNavbarSidenav.addEventListener("click", toggleSidenav);
        }

        if (iconSidenav) {
            iconSidenav.addEventListener("click", toggleSidenav);
        }

        function toggleSidenav() {
            if (body.classList.contains(className)) {
                // sidebar terbuka
                body.classList.remove(className);
                setTimeout(function() {
                    sidenav.classList.remove('bg-white');
                }, 100);
                sidenav.classList.remove('bg-transparent');

            } else {
                // sidebar tertutup
                body.classList.add(className);
                sidenav.classList.add('bg-white');
                sidenav.classList.remove('bg-transparent');
                iconSidenav.classList.remove('d-none');
            }
        }
    </script>

    {{-- mengatur button delete dg sweet alert --}}
    <script type="text/javascript">
        $(function() {
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var link = $(this).attr("href");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#017534",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: link,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: "Error!",
                                    text: xhr.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        })
                    }
                });
            });
        });
    </script>

    {{-- dropdown data wilayah --}}
    <script>
        // Muat jQuery secara dinamis
        function loadJQuery(callback) {
            if (typeof jQuery == 'undefined') {
                var script = document.createElement('script');
                script.src = "https://code.jquery.com/jquery-3.6.0.min.js";
                script.type = "text/javascript";
                script.onload = callback;
                document.head.appendChild(script);
            } else {
                callback();
            }
        }

        // Inisialisasi fungsi dropdown
        function initializeDropdown() {
            $(document).ready(function() {
                let submenuOpen = false; // Status submenu

                // Toggle submenu hanya ketika menu Data Wilayah diklik
                $('#dataWilayahDropdown').on('click', function(e) {
                    e.preventDefault(); // Mencegah default behavior link
                    submenuOpen = !submenuOpen; // Toggle status submenu

                    if (submenuOpen) {
                        $('#subMenu').css('display', 'block'); // Tampilkan submenu
                        $('#dropdownIcon').removeClass('bi-chevron-down').addClass('bi-chevron-up');
                    } else {
                        $('#subMenu').css('display', 'none'); // Sembunyikan submenu
                        $('#dropdownIcon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
                    }
                });

                // Mencegah klik pada subMenu menutup submenu
                $('#subMenu').on('click', function(e) {
                    e.stopPropagation();
                });

                // Klik di luar #dataWilayahDropdown dan #subMenu menutup submenu
                $(document).on('click', function(e) {
                    if (submenuOpen && !$(e.target).closest('#dataWilayahDropdown, #subMenu').length) {
                        submenuOpen = false;
                        $('#subMenu').css('display', 'none'); // Sembunyikan submenu
                        $('#dropdownIcon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
                    }
                });
            });
        }

        // Muat jQuery dan jalankan fungsi dropdown
        loadJQuery(initializeDropdown);
    </script>

    {{-- menjalankan script pada peta --}}
    @yield('scripts')

</body>

</html>
