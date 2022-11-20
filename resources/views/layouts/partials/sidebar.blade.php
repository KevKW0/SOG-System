<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ url($setting->path_logo) }}" alt="Company Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light company-name">{{ $setting->company_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url(auth()->user()->photo ?? '') }}" class="img-circle elevation-2 img-profile"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('user.profile') }}" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ Request::is('/dashboard/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- @if (auth()->user()->role == 'Administrator') --}}
                @can('master')
                    <li class="nav-header">MASTER</li>
                    <li class="nav-item">
                        <a href="{{ route('category.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                                Category
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('product.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Product
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>
                                Customer
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('supplier.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-people-carry"></i>
                            <p>
                                Supplier
                            </p>
                        </a>
                    </li>
                @endcan
                <li class="nav-header">TRANSACTION</li>
                <li class="nav-item">
                    <a href="{{ route('inbound.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-download"></i>
                        <p>
                            Inbound
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sales.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Sales
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('retur.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>
                            Return
                        </p>
                    </a>
                </li>
                @can('master')
                    <li class="nav-header">REPORT</li>
                    <li class="nav-item">
                        <a href="{{ route('report.index') }}" class="nav-link">
                            <i class="nav-icon fa fa-file-export"></i>
                            <p>Report</p>
                        </a>
                    </li>
                @endcan
                @can('setting')
                    <li class="nav-header">SYSTEM</li>
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link">
                            <i class="nav-icon fa fa-users"></i>
                            <p>User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}" class="nav-link">
                            <i class="nav-icon fa fa-cogs"></i>
                            <p>Setting</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
