<header id="header" class="">
    <!-- Fixed navbar -->
        <div>
            <b-navbar toggleable="lg" type="light" variant="light" class="p-2">
                <button onclick="openNav()" v-b-toggle.sidebar-1 class="button" id="open-sidebar" style="background-color: transparent !important; border: initial !important;">
                    <i data-icon="list" class="ri-menu-2-fill align-middle" style="font-size: 28px; color: #5664D2"></i>
                </button>
                <button onclick="closeNav()" v-b-toggle.sidebar-1 class="button pl-3" id="close-sidebar" style="background-color: transparent !important; border: initial !important;">
                    <i data-icon="list" class="ri-menu-3-fill align-middle" style="font-size: 28px; color: #5664D2"></i>
                </button>
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


<script>

</script>
