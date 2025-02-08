    <!-- Sidebar -->
    <div id="overlay"></div>
    <nav class="sidebar" id="sidebarMenu">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">


                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/home*') ? 'active' : '' }}"
                        href="{{ route('admin.home') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>&nbsp;
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-navicon"></i>&nbsp;
                        Thực đơn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-ellipsis-v"></i>&nbsp;
                        Danh mục
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-file-invoice"></i>&nbsp;
                        Bàn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-file-invoice"></i>&nbsp;
                        Nguyên liệu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-calendar"></i>&nbsp;
                        Hóa đơn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-users"></i>&nbsp;
                        Nhân viên
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-address-book"></i>&nbsp;
                        Khách hàng
                    </a>
                </li>
            </ul>
        </div>
    </nav>