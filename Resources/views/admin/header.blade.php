<header>
    <!-- Fixed navbar -->
        <div>
            <b-navbar toggleable="lg" type="light" variant="light" class="p-2">
                @include('laralite::admin.sidebar')
                <b-navbar-brand href="/admin/">
                    {{ env('APP_NAME') }} CMS
                    <span class="version-tag">v{{ env('APP_VERSION') }}
                </span></b-navbar-brand>

                <!-- Right aligned nav items -->
<!--                <b-navbar-nav class="ml-auto">
                    <b-nav-text>Logged in as <strong>{{ Auth::user()->name }}</b-nav-text>
                </b-navbar-nav>-->
            </b-navbar>
        </div>
</header>
