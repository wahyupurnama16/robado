<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ ENV('app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="MyraStudio" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Icons css  (Mandatory in All Pages) -->
    <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Google Font Family (Mandatory in All Pages) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    <!-- App css  (Mandatory in All Pages) -->
    <link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .simplebar-content {
            height: 90vh !important;
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')
    <main>
        <div class="flex wrapper">
            <!-- Start Sidebar -->
            <aside id="app-menu"
                class="hs-overlay fixed inset-y-0 start-0 z-[60] hidden w-64 -translate-x-full transform overflow-y-auto border-e border-default-200 bg-amber-800  transition-all duration-300 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-30 lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]">
                <div class="sticky top-0 flex h-16 items-center  justify-between px-6">
                    <h1 class="text-xl text-white">
                        {{ !Auth::user() ? 'Robado guest' : Auth::user()->nama }}
                    </h1>
                    @if (Auth::user())
                    <a href="{{ route('profile.edit') }}" class="text-white">Ubah</a>
                    @endif
                </div>

                <div class="hs-accordion-group p-4 ps-0" data-simplebar>
                    <ul class="admin-menu flex w-full flex-col gap-1.5 h-full">

                        @if (!Auth::user() || Auth::user() && Auth::user()->role !== 'baker')
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ Auth::user() ?  route('dashboard.index') : route('dashboard.guest') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">home</i>
                                Home
                            </a>
                        </li>
                        @endif
                        @if(Auth::user() && Auth::user()->role === 'admin')
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('produksi.index') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">Inventory_2</i>
                                Produk
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('users.index') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">group</i>
                                Users
                            </a>
                        </li>

                        @endif


                        @if (Auth::user() && (Auth::user()->role === 'owner'))
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('pemesanan.laporanOwner') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">summarize</i>
                                Rencana Produksi
                            </a>
                        </li>


                        @endif


                        @if (Auth::user() && (Auth::user()->role === 'baker'))
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('laporan.rencanaProduksi') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">summarize</i>
                                Rencana Produksi
                            </a>
                        </li>
                        @endif


                        @if (Auth::user() && (Auth::user()->role !== 'owner' && Auth::user()->role !== 'baker'))
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('pemesanan.riwayat') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1"><span
                                        class="material-symbols-rounded">
                                        work_history
                                    </span></i>
                                Pesanan
                            </a>
                        </li>
                        @endif

                        @if (Auth::user())
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('pemesanan.riwayatPesanan') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">Inventory_2</i>
                                Riwayat {{ Auth::user() && Auth::user()->role == 'admin' ? 'Transaksi' : '' }}
                            </a>
                        </li>
                        @if ((Auth::user()->role !== 'pelanggan'))
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('laporan.index') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">summarize</i>
                                Laporan Produksi
                            </a>
                        </li>
                        @endif
                        @endif

                        @if (!Auth::user())
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('dashboard.aturanBerlangganan') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">handshake</i>
                                Aturan Berlangganan
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('dashboard.tentangKami') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">store</i>
                                Tentang Kami
                            </a>
                        </li>
                        @endif

                        @if (!Auth::user())
                        <!-- Menu item lainnya tetap di atas -->
                        <li class="menu-item">
                            <!-- Tambahkan mt-auto untuk push ke bawah -->
                            <a class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700"
                                href="{{ route('login') }}">
                                <i
                                    class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">
                                    <span class="material-symbols-rounded">login</span>
                                </i>
                                Login
                            </a>
                        </li>
                        @else
                        <!-- Menu item lainnya tetap di atas -->
                        <li class="menu-item mt-auto">
                            <!-- Tambahkan mt-auto untuk push ke bawah -->
                            <div
                                class="group flex items-center gap-x-3.5 rounded-e-full px-4 py-2 text-sm font-medium text-default-700 transition-all hover:bg-default-100 text-white hover:text-gray-700">
                                <form action="{{ route('logout') }}" method="POST" class="w-full">
                                    @method('POST')
                                    @csrf
                                    <button type="submit" class="flex items-center gap-x-3.5 w-full">
                                        <i
                                            class="material-symbols-rounded font-light text-2xl transition-all group-hover:fill-1">
                                            <span class="material-symbols-rounded">logout</span>
                                        </i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endif

                    </ul>
                </div>
            </aside>
            <!-- End Sidebar -->
            <!-- Start Page Content here -->
            <div class="page-content">

                <!-- Topbar Start -->
                <header class="sticky top-0 bg-amber-800  h-16 flex items-center px-5 gap-4 z-50">
                    <!-- Topbar Brand Logo -->
                    <a href="/" class="md:hidden flex">
                        <img src="assets/images/logo-sm.png" class="h-6" alt="Small logo">
                    </a>

                    <!-- Sidenav Menu Toggle Button -->
                    <button id="button-toggle-menu" class="text-white p-2 rounded-full cursor-pointer"
                        data-hs-overlay="#app-menu" aria-label="Toggle navigation">
                        <i class="ti ti-menu-2 text-2xl"></i>
                    </button>

                    <!-- Language Dropdown Button -->
                    <div class="ms-auto hs-dropdown relative inline-flex [--placement:bottom-right]">

                    </div>

                    <!-- Fullscreen Toggle Button -->
                    <div class="md:flex hidden">
                        <button data-toggle="fullscreen" type="button" class="nav-link p-2">
                            <span class="sr-only">Fullscreen Mode</span>
                            <span class="flex items-center justify-center size-6">
                                <i class="ti ti-maximize text-2xl text-white"></i>
                            </span>
                        </button>
                    </div>

                    <!-- Profile Dropdown Button -->
                    <div class="relative">

                        <div id="notif"></div>

                        <a data-toggle="fullscreen" type="button" class="nav-link p-2" href="{{ route('cart.index') }}">
                            <span class="flex items-center justify-center size-6">
                                <i class="ti ti-shopping-cart text-2xl text-white"></i>
                            </span>
                        </a>
                    </div>
                </header>
                <!-- Topbar End -->
                {{ $slot }}
                <!-- End Page content -->
                <!-- Footer Start -->
                <footer class="footer h-16 flex items-center px-6 bg-amber-800  border-t border-gray-200 shadow">
                    <div class="flex md:justify-between justify-center w-full gap-4">
                        <div class="text-white">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Robado
                        </div>
                        <div class="md:flex hidden gap-2 item-center md:justify-end text-white">
                            Design &amp; Develop by<a href="#">Robado Team</a>
                        </div>
                    </div>
                </footer>
                <!-- Footer End -->
            </div>
        </div>
    </main>

    <!-- Plugin Js (Mandatory in All Pages) -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/preline/preline.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/iconify-icon/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- App Js (Mandatory in All Pages) -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('js')
</body>

</html>