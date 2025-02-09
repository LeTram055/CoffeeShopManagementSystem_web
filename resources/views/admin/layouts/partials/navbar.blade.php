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
    <ul class="navbar-nav px-3 ml-auto">



        <li class="nav-item">
            <a class="nav-link" href="">Đăng nhập</a>
        </li>

    </ul>
</nav>