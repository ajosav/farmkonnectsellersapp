<!-- Brand Logo -->
<a href="#" class="brand-link navbar-teal">
    <img src="/images/logo/logo.jpeg" alt="FarmKonnect" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">FarmKonnect</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/images/logo/human_avatar.png" class="img-circle elevation-2" alt="image">
        </div>
        <div class="info">
            <a href="#" class="d-block text-truncate"></a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2" id="myNavMenu">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
            data-accordion="false">
            {{-- <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact " data-widget="treeview" role="menu" data-accordion="false"> --}}

            <li class="nav-item" id="dashboard">
                <a href="{{route('admin.home')}}" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            <li class="nav-item has-treeview {{ Request::is('admin/view-orders*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-comments-dollar"></i>
                    <p>Orders
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('distributors.orders') }}"
                            class="nav-link {{Request::is('admin/view-orders/distributors') ? 'active' : ''}}">
                            <i class="fas fa-user-check nav-icon"></i>
                            <p>Commodity Distributors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('retailers.orders') }}"
                            class="nav-link {{ Request::is('admin/view-orders/retailers') ? 'active' : '' }}">
                            <i class="fas fa-users-cog nav-icon"></i>
                            <p>Commodity Retailers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('consumers.orders') }}"
                            class="nav-link {{Request::is('admin/view-orders/consumers') ? 'active' : ''}}">
                            <i class="fas fa-user-tag nav-icon"></i>
                            <p>Commodity Consumers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('logistics.requests') }}"
                            class="nav-link {{Request::is('admin/view-requests/logistics') ? 'active' : ''}}">
                            <i class="fas fa-shipping-fast nav-icon"></i>
                            <p>Logistic Companies</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-treeview {{ Request::is('products*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-apple-alt"></i>
                    <p>
                        Products
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('view-managers') }}"
                            class="nav-link {{ Request::is('/farm-managers') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i>
                            <p>Farm Managers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-distributors') }}"
                            class="nav-link {{ Request::is('admin/view-users/view-distributors') ? 'active' : '' }}">
                            <i class="fas fa-user-check nav-icon"></i>
                            <p>Commodity Distributors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-retailers') }}"
                            class="nav-link {{ Request::is('view-users/view-retailers') ? 'active' : '' }}">
                            <i class="fas fa-users-cog nav-icon"></i>
                            <p>Commodity Retailers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/wallet/withdraw') }}"
                            class="nav-link {{ Request::is('view-users/view-consumers') ? 'active' : '' }}">
                            <i class="fas fa-user-tag nav-icon"></i>
                            <p>Commodity Consumers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/wallet/withdraw') }}"
                            class="nav-link {{Request::is('wallet/withdraw') ? 'active' : ''}}">
                            <i class="fas fa-shipping-fast nav-icon"></i>
                            <p>Logistic Companies</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-treeview {{ Request::is('admin/view-users/*') ? 'menu-open' : '' }} ">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Users
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('new-user') }}"
                            class="nav-link {{ Request::is('admin/add-new-user') ? 'active' : '' }}">
                            <i class="fas fa-user-plus nav-icon"></i>
                            <p>Add New User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-managers') }}"
                            class="nav-link {{ Request::is('admin/view-users/farm-managers') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i>
                            <p>Farm Managers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-distributors') }}"
                            class="nav-link {{ Request::is('admin/view-users/commodity-distributors') ? 'active' : '' }}">
                            <i class="fas fa-user-check nav-icon"></i>
                            <p>Commodity Distributors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-retailers') }}"
                            class="nav-link {{ Request::is('admin/view-users/commodity-retailers') ? 'active' : '' }}">
                            <i class="fas fa-users-cog nav-icon"></i>
                            <p>Commodity Retailers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-consumers') }}"
                            class="nav-link {{ Request::is('admin/view-users/commodity-consumers') ? 'active' : ''}}">
                            <i class="fas fa-user-tag nav-icon"></i>
                            <p>Commodity Consumers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('view-logistics') }}"
                            class="nav-link {{ Request::is('admin/view-users/logistics-companies') ? 'active' : ''}}">
                            <i class="fas fa-shipping-fast nav-icon"></i>
                            <p>Logistic Companies</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item {{ Request::is('admin/view-transactions') ? 'active' : '' }}">
                <a href="{{ route('view-transactions') }}" class="nav-link">
                    <i class="nav-icon fas fa-history"></i>
                    <p>
                        Transaction History
                    </p>
                </a>
            </li>

        </ul>

    </nav>

    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
