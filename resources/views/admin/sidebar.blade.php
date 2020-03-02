<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
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
        </ul>
    </div>
</nav>
