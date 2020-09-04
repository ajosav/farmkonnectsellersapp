<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FarmKonnect|SellersApp</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="msapplication-tap-highlight" content="no">

    <link rel="icon" type="image/png" href="{{asset('images/logo/logo.jpeg')}}" />

    <script src="{{ URL::asset('vendor/pace/pace.js') }}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>



    @include('includes.vendor_css')

    <!-- Styles -->
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/bootstrap-tour-standalone.min.css') }}" rel="stylesheet">


    <style>
        .pace {
            -webkit-pointer-events: none;
            pointer-events: none;

            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        .pace-inactive {
            display: none;
        }

        .pace .pace-progress {
            background: rgb(222, 246, 0);
            position: fixed;
            z-index: 2000;
            top: 0;
            right: 100%;
            width: 100%;
            height: 2px;
        }

        .modal-backdrop {
            background: #388E3C;
        }

        [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active,
        [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active {
            background-color: #28a745;
            color: #ffffff;
        }

        .dark-primary-color {
            background: #388E3C;
        }

        .default-primary-color {
            background: #4CAF50;
            color: #ffffff
        }

        .light-primary-color {
            background: #C8E6C9;
        }

        .text-primary-color {
            color: #FFFFFF;
        }

        .accent-color {
            background: #9E9E9E;
        }

        .primary-text-color {
            color: #212121;
        }

        .secondary-text-color {
            color: #757575;
        }

        .divider-color {
            border-color: #BDBDBD;
        }


        .dark-primary-color {
            background: #388E3C;
        }

        .default-primary-color {
            background: #4CAF50;
            color: #ffffff
        }

        .light-primary-color {
            background: #C8E6C9;
        }

        .text-primary-color {
            color: #FFFFFF;
        }

        .accent-color {
            background: #9E9E9E;
        }

        .primary-text-color {
            color: #212121;
        }

        .secondary-text-color {
            color: #757575;
        }

        .divider-color {
            border-color: #BDBDBD;
        }
    </style>

    @stack('styles')

    {{--
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> --}}
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        @if(session()->has('denied'))
        <access-denied message="{{session('denied')}}"></access-denied>
        @elseif(session()->has('success'))
        <success-response message="{{session('success')}}"></success-response>
        @elseif(session()->has('error'))
        <access-denied message="{{session('error')}}"></access-denied>
        @endif

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-teal">
            {{-- Navigation goes here --}}
            @include('layouts.partials.header')
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-light-success">
            {{-- Sidebar starts here --}}
            @include('layouts.partials.sidebar')
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')

        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Notifications</h5>
                <p>Notification content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <form id="logout-form" action="{{route('logout')}}" method="post" style="display: none">
            @csrf
        </form>
        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                FarmKonnect SellersApp
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2019-2020 <a href="https://cloudware.ng">Cloudware Technologies</a>.</strong> All
            rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <script>
        window.csrfToken = '{{ csrf_token() }}';

    </script>
    <script src="{{ mix('js/app.js') }}" rel="stylesheet"></script>

    <script src="{{ URL::asset('js/bootstrap-tour-standalone.min.js') }}" rel="stylesheet"></script>
    @include('includes.vendor_js')



    <script>
        function setCookie(key, value, expiry) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 2000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        }

        function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }

    </script>
    @stack('scripts')

</body>

</html>
