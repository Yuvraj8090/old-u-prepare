<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin-Dashboard | U-PREPARE </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="{{asset('frontend/admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Toastr CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <!-- Icons -->
        <link href="{{asset('frontend/admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <script src="{{asset('frontend/admin/assets/js/head.js')}}"></script>

        <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote.min.js"></script>

        <!-- Datatables css -->
        <link href="{{asset('frontend/admin/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/admin/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/admin/assets/libs/datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/admin/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('frontend/admin/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />

        @yield('head')
    </head>

    <!-- body start -->
    <body data-menu-color="light" data-sidebar="default">
        <!-- Begin page -->
        <div id="app-layout">
            <!-- Topbar Start -->
            @include('frontend.layout.header');
            <!-- end Topbar -->

            <!-- Left Sidebar Start -->
            @include('frontend.layout.sidebar');
            <!-- Left Sidebar End -->


            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                @yield('content')
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

            <!-- Footer Start -->
            @include('frontend.layout.footer');
            <!-- end Footer -->
        </div>
        <!-- END wrapper -->

        <!-- Vendor -->
        <script src="{{asset('frontend/admin/assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/feather-icons/feather.min.js')}}"></script>

        <!-- Apexcharts JS -->
        <script src="{{asset('frontend/admin/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- for basic area chart -->
        <script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>

        <!-- Widgets Init Js -->
        <script src="{{asset('frontend/admin/assets/js/pages/analytics-dashboard.init.js')}}"></script>

        <!-- App js-->
        <script src="{{asset('frontend/admin/assets/js/app.js')}}"></script>

        <!-- Datatables js -->
        <script src="{{asset('frontend/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>

        <!-- dataTables.bootstrap5 -->
        <script src="{{asset('frontend/admin/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
        <script src="{{asset('frontend/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>

        <script>
            $('.btn-logout').on('click', function(e) {
                e.preventDefault();

                $(this).find('form').get(0).submit();
            })
        </script>

        @yield('js')
    </body>
</html>
