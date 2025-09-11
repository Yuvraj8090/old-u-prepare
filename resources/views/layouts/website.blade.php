<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ env('APP_NAME','UK-PREPARE') }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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
<body>
    
    
    <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">

      <h1 class="logo">
          <a href="{{ asset('/') }}">
            U-PREPARE <br>
            <p style="font-size:15px;" >(UTTARAKHAND DISASTER PREPAREDNESS AND RESILIENCE PROJECT)</p>
         </a>
     </h1><br>
     
     <nav id="navbar" class="navbar">
        <ul>
            <li><a @if(request()->segment(1) == "") class="active"  @endif " href="{{ url('/') }}">Home</a></li>
            
            <li class="dropdown"><a href="#"><span>About </span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{ url('/about') }}">About Us</a></li>
              <li><a href="{{ url('/project-components') }}">Project Components</a></li>
              <li><a href="{{ url('/project-structure') }}">Project Structure</a></li>
            </ul>
          </li>
          
            <li><a @if(request()->segment(1) == "project-status") class="active"  @endif  href="#">Project Status</a></li>
            <li><a @if(request()->segment(1) == "login") class="active"  @endif  href="{{ url('/login') }}">MIS Login</a></li>
              
            <li class="dropdown"><a href="#"><span>Grievances </span> <i class="bi bi-chevron-down"></i></a>
                <ul>
                  <li><a href="#">Register</a></li>
                  <li><a href="#">Status</a></li>
                </ul>
          </li>
          
            <li><a @if(request()->segment(1) == "contact") class="active"  @endif  href="{{ url('/contact') }}">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
    </div>

  </header>
  
 
 <!-- End Header -->
    
       
    <main id="main">
            @yield('content')
    </main>
      
            
<!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <h3>U-PREPARE</h3>
      <p>(UTTARAKHAND DISASTER PREPAREDNESS AND RESILIENCE PROJECT)</p>
      <div class="copyright">
        &copy; Copyright  by <strong><span> U-PREPARE</span></strong>. All Rights Reserved
      </div>
    </div>
  </footer><!-- End Footer -->
      

    
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('web/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('web/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('web/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('web/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- Template Main JS File -->
  <script src="{{ asset('web/assets/js/main.js') }}"></script>
  @yield('script')
</body>

</html>