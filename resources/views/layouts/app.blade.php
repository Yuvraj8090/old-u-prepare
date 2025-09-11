<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'U-prepare') }}</title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>U-PREPARE</title>
        <!--<link  href="https://fonts.googleapis.com">-->
        <!--<link  href="https://fonts.gstatic.com" crossorigin>-->
        <link  rel="stylesheet" href="{{ asset('asset/web/css/css.css') }}" >
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .active{
                font-weight:700;
                font-size:20px;
            }
        </style>
    </head>
    <body>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 top-header">
                       <h2>U-PREPARE</h2>
                       <h6>(UTTARAKHAND DISASTER PREPAREDNESS AND RESILIENCE PROJECT)</h6>
                    </div>
                    <div class="col-md-12 bg-light">
                        <nav class="navbar navbar-expand-lg navbar-light ">
                            <div class="container-fluid">
                                <!-- Brand -->
                               
                                <!-- Toggle button -->
                                <button
                                    class="navbar-toggler"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#navbarNav"
                                    aria-controls="navbarNav"
                                    aria-expanded="false"
                                    aria-label="Toggle navigation"
                                >
                                    <span class="navbar-toggler-icon"></span>
                                </button>
    
                                <!-- Collapsible content -->
                                <div
                                    class="collapse navbar-collapse"
                                    id="navbarNav"
                                >
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item {{ (request()->segment('1') == '' ? 'active' : '') }}">
                                        <a class="nav-link" href="{{ url('/') }}"
                                            >HOME <span class="sr-only"></span
                                        ></a>
                                    </li>
                                    <li class="nav-item {{ (request()->segment('1') == 'about' ? 'active' : '') }}">
                                        <a class="nav-link" href="{{ url('/about') }}">ABOUT </a>
                                    </li>
                                    <li class="nav-item {{ (request()->segment('1') == '#' ? 'active' : '') }}">
                                        <a class="nav-link" href="#"
                                            >PROJECT STATUS</a
                                        >
                                    </li>
                                    <li class="nav-item {{ (request()->segment('1') == 'login' ? 'active' : '') }}">
                                        <a class="nav-link" href="{{ url('/login') }}">MIS</a>
                                    </li>
                                    <li class="nav-item {{ (request()->segment('1') == 'contact' ? 'active' : '') }}">
                                        <a class="nav-link" href="{{ url('/contact') }}">CONTACT</a>
                                    </li>
                                </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div>
               @yield('content') 
            </div>   
            <div class="footer container-fluid text-center bg-light">
                copyright by U-prepare @ {{ date('Y') }}
            </div>
            
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
