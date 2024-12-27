<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'ریاست گمرک ولایت بلخ')</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicons -->
        <link href="{{ asset('assets/images/logo.png') }}" rel="icon">
        <link href="{{ asset('assets/images/logo.png') }}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

        <!-- Bootstrap CSS File -->
        <link href="{{ asset('website/plugins/bootstrap-rtl/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Libraries CSS Files -->
        <link href="{{ asset('website/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('website/plugins/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('website/plugins/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('website/plugins/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('website/plugins/magnific-popup/magnific-popup.css') }}" rel="stylesheet">
        <link href="{{ asset('website/plugins/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

        <!-- Main Stylesheet File -->
        <link href="{{ asset('website/css/style.css') }}" rel="stylesheet">

        <!-- =======================================================
          Project Name: Balkh Custom Department Website & MIS
          Site URL: https://bcd.af/
          Author: BCD Office
          Developer: Mahboobulrahman Sabiry-
          License: https://bcd.af/license/
        ======================================================= -->
    </head>

    <body id="body" style="font-family: Calibri !important;">
        <div class="translate fixed-top" id="google_translate_element"></div>

        <script type="text/javascript">
            function googleTranslateElementInit() {  new google.translate.TranslateElement({includedLanguages: 'fa,ps,,uz,en'}, 'google_translate_element');}
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        @include('layouts.website.header')

        <main id="main">
            @yield('content')
        </main>

        @include('layouts.website.footer')

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="{{ asset('website/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('website/plugins/jquery/jquery-migrate.min.js') }}"></script>
        <script src="{{ asset('website/plugins/bootstrap-rtl/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('website/plugins/easing/easing.min.js') }}"></script>
        <script src="{{ asset('website/plugins/superfish/hoverIntent.js') }}"></script>
        <script src="{{ asset('website/plugins/superfish/superfish.min.js') }}"></script>
        <script src="{{ asset('website/plugins/wow/wow.min.js') }}"></script>
        <script src="{{ asset('website/plugins/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('website/plugins/magnific-popup/magnific-popup.min.js') }}"></script>
        <script src="{{ asset('website/plugins/sticky/sticky.js') }}"></script>

        <!-- Contact Form JavaScript File -->
        <script src="{{ asset('website/contactform/contactform.js') }}"></script>

        <!-- Template Main Javascript File -->
        <script src="{{ asset('website/js/main.js') }}"></script>
    </body>
</html>
