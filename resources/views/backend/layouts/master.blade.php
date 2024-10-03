<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Laravel Role Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('backend.layouts.partials.styles')
    @yield('styles')
    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 10px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .flatpickr-calendar {
            border-radius: 15px;
        }

        .form-control, .select2-selection--single {
            border: 1px solid #ced4da;
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">

       @include('backend.layouts.partials.sidebar')

        <!-- main content area start -->
        <div class="main-content">
            @include('backend.layouts.partials.header')
            @yield('admin-content')
        </div>
        <!-- main content area end -->
        @include('backend.layouts.partials.footer')
    </div>
    <!-- page container area end -->

    @include('backend.layouts.partials.offsets')
    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>

</html>
