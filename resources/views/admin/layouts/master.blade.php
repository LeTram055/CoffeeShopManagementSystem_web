<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Prata&display=swap" rel="stylesheet">


    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">

    <title>Hope Cafe |
        @yield('title')</title>

    <!-- Các custom style dành riêng cho từng view -->
    <!-- @yield('custom-css') -->
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    @include('admin.layouts.partials.navbar')
    <!-- End Navbar -->

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('admin.layouts.partials.sidebar')
            <!-- End sidebar -->

            <!-- Content -->
            <div class="px-4" id="main-content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2 feature-title">@yield('feature-title')</h1>

                </div>
                @yield('content')
            </div>
            <!-- End content -->
        </div>
    </div>
    <!-- End main content -->

    <!-- Optional JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin/script.js') }}"></script>

    <!-- Các custom script dành riêng cho từng view -->
    @yield('custom-scripts')
</body>

</html>