<header>
    <!-- Fixed navbar -->
    {{--<nav class="navbar navbar-expand-md fixed-top p-0">
        <a class="navbar-brand" href="/admin/">
            {{ env('APP_NAME') }} CMS
            <span class="version-tag">v{{ env('APP_VERSION') }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Logged in as <strong>{{ Auth::user()->name }}</strong>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/admin/settings">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>--}}
        <div>
            <b-navbar toggleable="lg" type="light" variant="light" class="p-2">
                @include('laralite::admin.sidebar')
                <b-navbar-brand href="/admin/">
                    {{ env('APP_NAME') }} CMS
                    <span class="version-tag">v{{ env('APP_VERSION') }}
                </span></b-navbar-brand>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-text>Logged in as <strong>{{ Auth::user()->name }}</b-nav-text>
                </b-navbar-nav>
            </b-navbar>
        </div>
</header>
