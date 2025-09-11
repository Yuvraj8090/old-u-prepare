<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link  rel="stylesheet"  href="{{ url('build/assets/app-972c31f0.css') }}" />
    <script  src="{{ url('build/assets/app-67c1fef2.js') }}" /></script>
    <link href="{{ asset('asset/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    
    
      <!-- Vendor CSS Files -->
      <link href="{{  asset('web/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{  asset('web/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
      <link href="{{  asset('web/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
      <link href="{{  asset('web/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
      <link href="{{  asset('web/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
      <link href="{{  asset('web/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{  asset('web/assets/css/style.css') }}" rel="stylesheet">

</head>

</head>

<body class="font-sans text-gray-900 antialiased">

    <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo">
          <a href="{{ asset('/') }}">
            U-PREPARE <br>
            <p style="font-size:15px;" >(UTTARAKHAND DISASTER PREPAREDNESS AND RESILIENCE PROJECT)</p>
         </a>
     </h1><br>

      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
            <li><a @if(request()->segment(1) == "") class="active"  @endif " href="{{ url('/') }}">Home</a></li>
            <li><a @if(request()->segment(1) == "about") class="active"  @endif  href="{{ url('/about') }}">About Us</a></li>
            <li><a @if(request()->segment(1) == "project-status") class="active"  @endif  href="#">Project Status</a></li>
            <li><a @if(request()->segment(1) == "login") class="active"  @endif  href="{{ url('/login') }}">MIS Login</a></li>
            <li><a @if(request()->segment(1) == "contact") class="active"  @endif  href="{{ url('/contact') }}">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->


    </div>
  </header>
  
    <main style="margin-top:90px;" id="main">
        <div class="conatiner-fluid bg-light">
    <div class="row">
        <div class="col-md-6 col-sm-12 bg-light">
            <img src="{{ asset('asset/web/home_left.jpeg') }}" class="img-responsive" style="height:750px !important"  width="120%" />
        </div>
        <div class="col-md-6 col-sm-12 bg-light">
                <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    

        <div>
            <center>
            <h1 style="font-size:20px !important;font-weight:400;text-transform:uppercase;">   Uttarakhand Disaster Preparedness and Resilience Project  </h1>
            <h1 style="font-size:30px !important;font-weight:400;">(U-PREPARE) </h1>
            </center>
        </div>
       
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

        <div style="margin-top:30px;" >
            <center>

                 <h5 style="font-size:15px;" > Department Of Disaster Management & Rehabilitation </h5>
                <p style="font-size:15px;" >© {{ date('Y') }} All Rights Reserved. </p>
            </center>
            </div> 
       
    </div>
        </div>
    </div>
</div>
    </main>



</body>

</html>