<nav class="navbar navbar-dark fixed-top flex-md-nowrap p-1 shadow navbar-bg">
    <div>
        <!-- Nút toggle sidebar -->
        <button class="navbar-toggler d-md-none" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('admin.home.index') }}">
            <img src="{{ asset('images/logo_nbg.png') }}" alt="Ánh Dương Hotel" style="height: 40px;">
            Hope Cafe
        </a>

    </div>

    <!-- Thông tin người dùng -->
    <ul class="navbar-nav px-3 ms-auto">
        @if(Auth::check())
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
</nav>