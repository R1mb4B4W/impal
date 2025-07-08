<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Deltizen Corner</title>
    <!-- Yummy CSS dan Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/css/main.css') }}" rel="stylesheet">
</head>
<body class="index-page">
    <!-- Header dari Yummy -->
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="/" class="logo d-flex align-items-center me-auto me-xl-0">
                <img src="{{ asset('logo_deltizen.png') }}" alt="Deltizen Corner" style="max-height: 40px;">
                <h1 class="sitename">Deltizen Corner</h1>
                <span>.</span>
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="/" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="/menu" class="{{ Request::is('menu*') ? 'active' : '' }}">Menu</a></li>
                    <li><a href="{{ route('invoice.list') }}" class="{{ Request::is('invoice*') ? 'active' : '' }}">Status Pesanan</a></li>
                    <li><a href="{{ route('cart.list') }}" class="{{ Request::is('cart*') ? 'active' : '' }}">Keranjang</a></li>
                        @auth
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                    <a href="{{ route('logout.perform') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                                @endauth
                                @guest
                                    <li><a href="{{ route('login') }}">Masuk</a></li>
                                @endguest
                            </ul>
                            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                        </nav>
            <a class="btn-getstarted" href="/menu">Buat Pesanan</a>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    <!-- Footer dari Yummy -->
    <footer id="footer" class="footer dark-background">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt icon"></i>
                    <div class="address">
                        <h4>Alamat</h4>
                        <p>Sitoluama, Kec. Balige, Toba, Sumatera Utara 22381</p>
                        <p>Indonesia</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-telephone icon"></i>
                    <div>
                        <h4>Kontak</h4>
                        <p>
                            <strong>Phone:</strong> <span>+6281360912900</span><br>
                            <strong>Email:</strong> <span>delitzencorner@gmail.com</span><br>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-clock icon"></i>
                    <div>
                        <h4>Jam Buka</h4>
                        <p>
                            <strong>Sen-Jum:</strong> <span>10:00 - 22:00</span><br>
                            <strong>Sab-Ming:</strong> <span>10:00 - 23:00</span>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links d-flex">
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Deltizen Corner</strong> <span>All Rights Reserved</span></p>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('template_front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template_front/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('template_front/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('template_front/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('template_front/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <!-- Main JS File -->
    <script src="{{ asset('template_front/assets/js/main.js') }}"></script>
</body>
</html>