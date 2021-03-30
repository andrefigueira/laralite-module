<template>
  <div>
    <b-button v-b-toggle.sidebar-1 class="button">
      <b-icon icon="list" font-scale="1"></b-icon>
    </b-button>
    <b-sidebar id="sidebar-1" backdrop class="sidebar" sidebar-class="border-right border-dark" style="border: none !important;">
      <div class="py-2">
        <nav class="col-md-2 d-block pr-0">
          <div id="accordion" class="mt-4 sidebar accordion" style="width: 290px !important;">
            <div class="card" v-if="role">
              <div class="card-header" id="headingOne">
                <a href="#" data-toggle="collapse" data-target="#collapseOne">Site Management</a>
              </div><!-- End card header -->

              <div id="collapseOne" class="collapse" :class="{ 'show': request.match('admin/') || request.match('admin/pages*') || request.match('admin/pages*') || request.match('admin/users*') || request.match('admin/templates*') || request.match('admin/permissions*') || request.match('admin/navigation*') || request.match('admin/roles*') }" aria-labelledby="headingOne" data-parent="#accordion">
                <!--              ['admin/', 'admin/pages*', 'admin/home', 'admin/users*', 'admin/templates*', 'admin/navigation*', 'admin/permissions*', 'admin/roles*'].indexOf(request)-->
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/home') }" href="/admin/home">
                        <i class="fas fa-columns"></i>
                        Dashboard
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/users*') }" href="/admin/users">
                        <i class="fas fa-users"></i>
                        Users
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/roles*') }" href="/admin/roles">
                        <i class="fas fa-user-circle"></i>
                        Roles
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/permissions*') }" href="/admin/permissions">
                        <i class="fas fa-lock"></i>
                        Permissions
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/pages*') }" href="/admin/pages">
                        <i class="fas fa-stream"></i>
                        Pages
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/templates*') }" href="/admin/templates">
                        <i class="fas fa-border-style"></i>
                        Templates
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/navigation*') }" href="/admin/navigation">
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
              <div id="collapseTwo" class="collapse" :class="{ 'show': request.match('admin/product') || request.match('admin/product/edit/*') || request.match('admin/product/create') || request.match('admin/product-category*') || request.match('admin/customers*') || request.match('admin/orders*') || request.match('admin/discounts*') || request.match('admin/reporting*') || request.match('admin/scanner*') }" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <!--                    @if (Auth::user()->hasRole('admin'))-->
                    <li class="nav-item" v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/product') || request.match('admin/product/edit/*') || request.match('admin/product/create') }" href="/admin/product">
                        <i class="fas fa-shopping-cart"></i>
                        Products
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/product-category*') }" href="/admin/product-category">
                        <i class="fas fa-tag"></i>
                        Product Categories
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/orders*') }" href="/admin/orders">
                        <i class="fas fa-ticket-alt"></i>
                        Orders
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/discounts*') }" href="/admin/discounts">
                        <i class="fas fa-percent"></i>
                        Discounts
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/reporting*') }" href="/admin/reporting">
                        <i class="fas fa-chart-area"></i>
                        Reporting
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/customers*') }" href="/admin/customers">
                        <i class="fas fa-user-friends"></i>
                        Customers
                      </a>
                    </li>
                    <li class="nav-item" v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/scanner*') }" href="/admin/scanner">
                        <i class="fas fa-qrcode"></i>
                        Scanner
                      </a>
                    </li>
                  </ul>
                </div><!-- End card body -->
              </div><!-- End collapse -->
            </div><!-- End card -->
            <div class="card" v-if="role">
              <div class="card-header" id="headingThree">
                <a href="#" data-toggle="collapse" data-target="#collapseThree">Advanced</a>
              </div><!-- End card header -->
              <div id="collapseThree" class="collapse" :class="{ 'show': request.match('admin/components') || request.match('admin/variables') || request.match('admin/authentication') || request.match('admin/settings') }" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/components') }" href="/admin/components">
                        <i class="fas fa-wrench"></i>
                        Components
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/variables') }" href="/admin/variables">
                        <i class="fas fa-code"></i>
                        Variables
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/authentication') }" href="/admin/authentication">
                        <i class="fas fa-lock"></i>
                        Authentication
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/settings') }" href="/admin/settings">
                        <i class="fas fa-cog"></i>
                        Settings
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/import') }" href="/admin/import">
                        <i class="fas fa-file-import"></i>
                        Data Import
                      </a>
                    </li>
                  </ul>
                </div><!-- End card body -->
              </div><!-- End collapse -->
            </div><!-- End card -->
            <div class="card" v-if="role">
              <div class="card-header" id="headingFour">
                <a href="#" data-toggle="collapse" data-target="#collapseFour">Account</a>
              </div><!-- End card header -->
              <div id="collapseFour" class="collapse" :class="{ 'show': request.match('admin/components') || request.match('admin/variables') || request.match('admin/authentication') || request.match('admin/settings') }" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/components') }" href="/admin/settings">
                        <i class="fas fa-cog"></i>
                        Settings
                      </a>
                    </li>
                    <li class="nav-item">
                      <!--                      <a class="nav-link" href="{{ route('logout') }}"
                                                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                 </a>-->
                      <a class="nav-link" @click="$refs.logout_form.submit()">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                      </a>
                    </li>
                  </ul>
                </div><!-- End card body -->
              </div><!-- End collapse -->
            </div><!-- End card -->
          </div><!-- End accordion sidebar -->
          <form ref="logout_form" action="/admin/logout" method="POST" style="display: none;">
            {{ csrfToken }}
          </form>
        </nav>
      </div>
    </b-sidebar>
  </div>
</template>

<script>
export default {
  name: "Sidebar",
  props: {
    request: {
      type: String,
      required: true
    },
    role: {
      type: String,
      required: true
    },
  },
  computed: {
    csrfToken() {
      return window.Laravel.csrfToken;
    }
  },
  methods: {
    logout() {
      event.preventDefault();
      axios.post('/logout').then(response => {

      }).catch(error => {
        location.reload();
      });
    }
  },
}
</script>

<style scoped>
.b-sidebar {

}
</style>
