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

    <title>Hope Cafe | Đăng nhập</title>

    <!-- Các custom style dành riêng cho từng view -->
    <style>
    body {
        display: flex;
        min-height: 100vh;
        margin: 0;
        background: url('{{ asset("images/login_bg.png") }}') no-repeat center center fixed;
        background-size: cover;
    }

    .feature-title {
        font-family: 'Prata', cursive;
        font-weight: bold;
    }

    #main-content {
        padding-top: 60px;
        transition: margin-left 0.3s ease;
    }

    /*navbar*/
    .navbar-bg {
        background-color: #0049ab;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 100;
        color: white;
        font-weight: 600;
    }

    .navbar .dropdown-menu {
        position: absolute;
        top: 50px;
    }

    .navbar-brand {
        font-family: 'Prata', cursive;
        font-weight: bold;
        font-size: 1.5rem;
    }

    /*  */
    .navbar-nav {
        position: relative;
    }

    /*Tiêu đề*/
    .title2 {
        font-weight: 600;
        color: #0049ab;
    }

    /* Style cho card đăng nhập */
    .login-form {
        background: rgba(255, 255, 255, 0.6);
        border-radius: 8px;
        padding: 20px;
        border: solid 1px #0049ab;
        box-shadow: 0 4px 10px #0049ab;
        transition: all 0.3s ease-in-out;
    }

    .login-form:hover {
        box-shadow: 0 6px 15px #053370;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid #ccc;
    }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top flex-md-nowrap p-1 shadow navbar-bg">
        <div>
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('login') }}">
                <img src="{{ asset('images/logo_nbg.png') }}" alt="Ánh Dương Hotel" style="height: 40px;">
                Hope Cafe
            </a>

        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Content -->
            <div class="px-4" id="main-content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2 feature-title">Đăng nhập</h1>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }} position-relative">
                        {{ Session::get('alert-' . $msg) }}
                        <button type="button" class="btn-close position-absolute end-0 me-2" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </p>
                    @endif
                    @endforeach
                </div>
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 col-md-4 login-form">
                        <h3 class="text-center title2 mb-4">Đăng nhập</h3>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Nhập tên đăng nhập" value="{{ old('username') }}" required>
                                @error('username')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Nhập mật khẩu" required>
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
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
    <script>
    $(document).ready(function() {
        // Tự động đóng thông báo sau 5 giây
        setTimeout(function() {
            $('.flash-message .alert').fadeOut('slow');
        }, 5000);
    });
    </script>

</body>

</html>