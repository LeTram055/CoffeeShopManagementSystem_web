    <!-- Sidebar -->
    <div id="overlay"></div>
    <nav class="sidebar" id="sidebarMenu">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">


                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/home*') ? 'active' : '' }}"
                        href="{{ route('admin.home.index') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/category*') ? 'active' : '' }}"
                        href="{{ route('admin.category.index') }}">
                        <i class="fa-solid fa-ellipsis-v"></i>
                        Quản lý danh mục
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/menuitem*') ? 'active' : '' }}"
                        href="{{ route('admin.menuitem.index') }}">
                        <i class="fa-solid fa-navicon"></i>
                        Quản lý thực đơn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/table*') ? 'active' : '' }}"
                        href="{{ route('admin.table.index') }}">
                        <i class="fa-solid fa-table"></i>
                        Quản lý bàn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/ingredient*') ? 'active' : ''}}"
                        data-bs-toggle="collapse" href="#ingredientMenu" role="button" aria-expanded="false">
                        <i class="fa-solid fa-table"></i>
                        Quản lý nguyên liệu
                        <i class="fa-solid fa-caret-down"></i>
                    </a>
                    <div class="collapse" id="ingredientMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/ingredient*') && !request()->is('admin/ingredientlog*') ? 'active' : '' }}"
                                    href="{{ route('admin.ingredient.index') }}">
                                    <i class="fa-solid fa-table-cells-large"></i>Nguyên liệu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/ingredientlog*') ? 'active' : '' }}"
                                    href="{{ route('admin.ingredientlog.index') }}">
                                    <i class="fa-solid fa-table-list"></i>Log nguyên liệu
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/promotion*') ? 'active' : '' }}"
                        href="{{ route('admin.promotion.index') }}">
                        <i class="fa-solid fa-percent"></i>
                        Quản lý quảng cáo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/payment*') ? 'active' :'' }}"
                        href=" {{ route('admin.payment.index') }}">
                        <i class="fa-solid fa-file-invoice"></i>
                        Quản lý hóa đơn
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  {{ request()->is('admin/employee*') ? 'active' : '' }}"
                        href="{{ route('admin.employee.index') }}">
                        <i class="fa-solid fa-users"></i>
                        Quản lý nhân viên
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}"
                        href="{{ route('admin.customer.index') }}">
                        <i class="fa-solid fa-address-book"></i>
                        Quản lý khách hàng
                    </a>
                </li>
            </ul>
        </div>
    </nav>