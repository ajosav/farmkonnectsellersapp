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
            @can('Logistic Company')
            <li class="nav-item has-treeview {{ Request::is('logistics') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>
                        Deliveries
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('logistics.requests')}}"
                            class="nav-link {{ Request::is('logistics/requests') ? 'active' : '' }}">
                            <i class="fas fa-dolly"></i>
                            <p>Latest Requests</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('logistics.pending')}}"
                            class="nav-link {{ Request::is('logistics/pending') ? 'active' : '' }}">
                            <i class="fas fa-dolly-flatbed"></i>
                            <p>Pending Deliveries</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('logistics.history')}}"
                            class="nav-link {{Request::is('logistics/history') ? 'active' : ''}}">
                            <i class="fas fa-shipping-fast"></i>
                            <p>Delivery History</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @if(auth()->user()->can('Commodity Retailer') || auth()->user()->can('Commodity Distributor') ||
            auth()->user()->can('Farm Manager'))
            <li class="nav-item has-treeview {{Request::is('product*') ? 'menu-open' : ''}}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Sale
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('Farm Manager')
                        <li class="nav-item">
                            <a href="{{route('product.create')}}"
                                class="nav-link {{Request::is('product/create') ? 'active' : ''}}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Product</p>
                            </a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{route('product.index')}}"
                            class="nav-link {{Request::is('product') ? 'active' : ''}}">
                            <i class="fa fa-tasks nav-icon"></i>
                            <p>Products</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('requests') }}" class="nav-link">
                            <i class="fa fa-cart-arrow-down"></i>
                            <p>Orders</p>
                            <span class="badge badge-info right">6</span>
                        </a>
                    </li>

                </ul>
            </li>
            @endcan

            @if(auth()->user()->can('Commodity Retailer') || auth()->user()->can('Commodity Distributor') ||
            auth()->user()->can('Commodity Consumer'))
            <li class="nav-item has-treeview {{Request::is('order*') ? 'menu-open' : ''}}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-store"></i>
                    <p>
                        Market Place
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('get.allProducts')}}"
                            class="nav-link {{Request::is('orders/make_order') ? 'active' : ''}}">
                            <i class="fas fa-plus nav-icon"></i>Make Order
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders') }}" class="nav-link {{Request::is('orders') ? 'active' : ''}}">
                            <i class="fa fa-cart-arrow-down nav-icon"></i>My Order History
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="nav-item">
                <a href="{{ url('/bank-account') }}" class="nav-link {{Request::is('bank-account') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-landmark"></i>
                    <p>
                        Bank Account
                    </p>
                </a>
            </li>
            <li class="nav-item has-treeview {{Request::is('wallet*') ? 'menu-open' : ''}}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-wallet"></i>
                    <p>
                        My Wallet
                        <i class="fas fa-angle-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/wallet') }}" class="nav-link {{Request::is('wallet') ? 'active' : ''}}">
                            <i class="fas fa-credit-card nav-icon"></i>
                            <p>Credit Wallet</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/wallet/withdraw') }}"
                            class="nav-link {{Request::is('wallet/withdraw') ? 'active' : ''}}">
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

        </ul>

    </nav>

    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
