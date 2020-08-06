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
            <a href="#" class="d-block text-truncate">{{auth()->user()->name}}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2" id="myNavMenu">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
            data-accordion="false">
            {{-- <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact " data-widget="treeview" role="menu" data-accordion="false"> --}}

            <li class="nav-item" id="dashboard">
                <a href="{{route('home')}}" class="nav-link {{Request::is('home') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            <li id="profile" class="nav-item has-treeview">
                <a href="{{route('profile.index')}}" class="nav-link {{Request::is('profile') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-id-badge"></i>
                    <p>
                        Profile
                    </p>
                </a>

            </li>
            @can('Farm Manager')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Sale
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('product.create')}}"
                            class="nav-link {{Request::is('product/create') ? 'active' : ''}}">
                            <i class="fas fa-plus nav-icon"></i>
                            <p>Add Product</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('product.index')}}"
                            class="nav-link {{Request::is('product') ? 'active' : ''}}">
                            <i class="fa fa-tasks nav-icon"></i>
                            <p>Products</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="layout/fixed-topnav.html" class="nav-link">
                            <i class="fa fa-cart-arrow-down"></i>
                            <p>Orders</p>
                            <span class="badge badge-info right">6</span>
                        </a>
                    </li>

                </ul>
            </li>
            @endcan
            <li class="nav-item">
                <a href="{{ url('/bank-account') }}" class="nav-link">
                    <i class="nav-icon fas fa-landmark"></i>
                    <p>
                        Bank Account
                    </p>
                </a>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-wallet"></i>
                    <p>
                        My Wallet
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/wallet') }}"
                            class="nav-link {{Request::is('product/create') ? 'active' : ''}}">
                            <i class="fas fa-credit-card nav-icon"></i>
                            <p>Credit Wallet</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/wallet/withdraw') }}"
                            class="nav-link {{Request::is('product') ? 'active' : ''}}">
                            <i class="fas fa-money-bill nav-icon"></i>
                            <p>Make Withdrawal</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ url('/transactions') }}" class="nav-link">
                    <i class="nav-icon fas fa-history"></i>
                    <p>
                        Transaction History
                    </p>
                </a>
            </li>

            @can('Commodity Retailer')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Orders
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('get.allProducts')}}"
                            class="nav-link {{Request::is('retailer/make_order') ? 'active' : ''}}">
                            <i class="fas fa-plus nav-icon"></i>Make Order
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="" class="">
                            <i class="fa fa-cart-arrow-down nav-icon"></i>My Orders
                            <span class="badge badge-info right">6</span>
                        </a>
                    </li>

                </ul>
            </li>
            @endcan

        </ul>

    </nav>

    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
