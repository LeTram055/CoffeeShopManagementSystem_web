<nav class="navbar navbar-dark navbar-expand-lg fixed-top flex-md-nowrap p-1 shadow navbar-bg">
    <div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
            aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('staff_baristas.order.index') }}">
            <img src="{{ asset('images/logo_nbg.png') }}" alt="Ánh Dương Hotel" style="height: 40px;">
            Hope Cafe
        </a>

    </div>
    <div class="collapse navbar-collapse m-1" id="navbarToggler">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('staff_baristas.order.index') }}">Đơn hàng</a>
            </li>
        </ul>
        <!-- Thông tin người dùng -->
        <ul class="navbar-nav pr-3 ms-auto">
            @if(Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Xin chào, {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('password.change') }}">Đổi mật khẩu</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
            </li>
            @endif
        </ul>
    </div>
</nav>