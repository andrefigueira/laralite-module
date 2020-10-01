<nav class="col-md-2 d-none d-md-block pr-0">
    <div id="accordion" class="mt-4 sidebar accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <a href="#" data-toggle="collapse" data-target="#collapseOne">Site Management</a>
            </div><!-- End card header -->

            <div id="collapseOne" class="collapse {{ request()->is('admin/', 'admin/pages*', 'admin/home', 'admin/users*', 'admin/templates*', 'admin/navigation*') ? 'show' : '' }}" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/home') ? 'active' : '' }}" href="/admin/home">
                                <i class="fas fa-columns"></i>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="/admin/users">
                                <i class="fas fa-users"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}" href="/admin/pages">
                                <i class="fas fa-stream"></i>
                                Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/templates*') ? 'active' : '' }}" href="/admin/templates">
                                <i class="fas fa-border-style"></i>
                                Templates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/navigation*') ? 'active' : '' }}" href="/admin/navigation">
                                <i class="fas fa-directions"></i>
                                Navigation
                            </a>
                        </li>
                    </ul>
                </div><!-- End card body -->
            </div><!-- End collapse -->
        </div><!-- End card -->
        <div class="card">
            <div class="card-header" id="headingTwo">
                <a href="#" data-toggle="collapse" data-target="#collapseTwo">Commerce</a>
            </div><!-- End card header -->
            <div id="collapseTwo" class="collapse {{ request()->is('admin/product', 'admin/product/edit/*', 'admin/product/create', 'admin/product-category*', 'admin/customers*') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product', 'admin/product/edit/*', 'admin/product/create') ? 'active' : '' }}" href="/admin/product">
                                <i class="fas fa-shopping-cart"></i>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product-category*') ? 'active' : '' }}" href="/admin/product-category">
                                <i class="fas fa-tag"></i>
                                Product Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="/admin/orders">
                                <i class="fas fa-ticket-alt"></i>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/discounts*') ? 'active' : '' }}" href="/admin/discounts">
                                <i class="fas fa-percent"></i>
                                Discounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/reporting*') ? 'active' : '' }}" href="/admin/reporting">
                                <i class="fas fa-chart-area"></i>
                                Reporting
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}" href="/admin/customers">
                                <i class="fas fa-user-friends"></i>
                                Customers
                            </a>
                        </li>
                    </ul>
                </div><!-- End card body -->
            </div><!-- End collapse -->
        </div><!-- End card -->
        <div class="card">
            <div class="card-header" id="headingThree">
                <a href="#" data-toggle="collapse" data-target="#collapseThree">Advanced</a>
            </div><!-- End card header -->
            <div id="collapseThree" class="collapse {{ request()->is('admin/components*', 'admin/variables*', 'admin/authentication*', 'admin/settings*') ? 'show' : '' }}" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/components*') ? 'active' : '' }}" href="/admin/components">
                                <i class="fas fa-wrench"></i>
                                Components
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/variables*') ? 'active' : '' }}" href="/admin/variables">
                                <i class="fas fa-code"></i>
                                Variables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/authentication*') ? 'active' : '' }}" href="/admin/authentication">
                                <i class="fas fa-lock"></i>
                                Authentication
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="/admin/settings">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                    </ul>
                </div><!-- End card body -->
            </div><!-- End collapse -->
        </div><!-- End card -->
    </div><!-- End accordion sidebar -->
</nav>
