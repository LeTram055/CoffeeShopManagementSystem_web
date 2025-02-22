    <!-- Sidebar -->
    <div id="overlay"></div>
    <nav class="sidebar" id="sidebarMenu">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">


                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/home*') ? 'active' : '' }}"
                        href="{{ route('admin.home.index') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>&nbsp;
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/category*') ? 'active' : '' }}"
                        href="{{ route('admin.category.index') }}">
                        <i class="fa-solid fa-ellipsis-v"></i>&nbsp;
                        Danh mục
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/menuitem*') ? 'active' : '' }}"
                        href="{{ route('admin.menuitem.index') }}">
                        <i class="fa-solid fa-navicon"></i>&nbsp;
                        Thực đơn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa-solid fa-table"></i>&nbsp;
                        Bàn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/ingredient*') ? 'active' : ''}}"
                        data-bs-toggle="collapse" href="#ingredientMenu" role="button" aria-expanded="false">
                        <i class="fa-solid fa-file-invoice"></i>&nbsp;
                        Quản lý nguyên liệu
                    </a>
                    <div class="collapse" id="ingredientMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/ingredient*') && !request()->is('admin/ingredientlog*') ? 'active' : '' }}"
                                    href="{{ route('admin.ingredient.index') }}">
                                    Nguyên liệu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/ingredientlog*') ? 'active' : '' }}"
                                    href="{{ route('admin.ingredientlog.index') }}">
                                    Log nguyên liệu
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/promotion*') ? 'active' : '' }}"
                        href="{{ route('admin.promotion.index') }}">
                        <i class="fa-solid fa-percent"></i></i>&nbsp;
                        Quảng cáo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/payment*') ? 'active' :'' }}"
                        href=" {{ route('admin.payment.index') }}">
                        <i class="fa-solid fa-calendar"></i>&nbsp;
                        Hóa đơn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  {{ request()->is('admin/employee*') ? 'active' : '' }}"
                        href="{{ route('admin.employee.index') }}">
                        <i class="fa-solid fa-users"></i>&nbsp;
                        Nhân viên
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}"
                        href="{{ route('admin.customer.index') }}">
                        <i class="fa-solid fa-address-book"></i>&nbsp;
                        Khách hàng
                    </a>
                </li>
            </ul>
        </div>
    </nav>