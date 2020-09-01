<nav class="col-md-2 d-none d-md-block">
    <ul class="nav flex-column sidebar">
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
            <a class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}" href="/admin/customers">
                <i class="fas fa-user-friends"></i>
                Customers
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
</nav>
