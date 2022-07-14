<template>
  <div>
    <!--    <b-button v-b-toggle.sidebar-1 class="button" style="background-color: transparent !important; border: initial !important;">
          <i data-icon="list" class="ri-menu-2-fill align-middle" style="font-size: 28px; color: #5664D2"></i>
        </b-button>-->
    <b-sidebar id="sidebar-1" :visible="visible" no-close-on-route-change class="sidebar hide-menu" no-header  :title="appName" sidebar-class="border-right border-dark" aria-expanded="true" style="border: none !important; display: block !important;">
      <div class="py-2">
        <div id="sidebar-no-header-title" class="mt-4 mb-4" style="text-align: center">
          <img src="/images/logo.png" class="logoImage">
        </div>
        <nav class="d-block pr-3 pl-2">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" :class="{ 'active': request === 'admin' || request.match('admin/home') }" href="/admin/home">
                <i class="bx ri-dashboard-line"></i>
                Dashboard
              </a>
            </li>
          </ul>
          <div id="accordion" class="sidebar accordion" style="width: 230px !important;">
            <div class="card" v-if="">
              <div class="card-header" id="headingThree">
                <a href="#" data-toggle="collapse" data-target="#collapseThree">Ecommerce
                  <i class="ri-arrow-down-s-line float-right"></i></a>
              </div>
              <!--              End card header-->
              <div id="collapseThree" class="collapse" :class="{ 'show': request.match('admin/scanner*') || request.match('admin/product') || request.match('admin/product/edit/*') || request.match('admin/product/create') || request.match('admin/product-category*') || request.match('admin/customers*') || request.match('admin/orders*') || request.match('admin/discounts*') || request.match('admin/subscriptions*') || request.match('admin/reporting*') }" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav pl-2">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/scanner*') }" href="/admin/scanner">
                        <i class="ri-qr-code-line"></i>
                        Scanner
                      </a>
                    </li>
                    <li class="nav-item" v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/product') || request.match('admin/product/edit/*') || request.match('admin/product/create') }" href="/admin/product">
                        <i class="ri-shopping-cart-2-line"></i>
                        Products
                      </a>
                    </li>
                    <li class="nav-item"  v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/product-category*') }" href="/admin/product-category">
                        <i class="ri-price-tag-3-line"></i>
                        Product Categories
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/orders*') }" href="/admin/orders">
                        <i class="ri-ticket-line"></i>
                        Orders
                      </a>
                    </li>
                    <li class="nav-item"  v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/discounts*') }" href="/admin/discounts">
                        <i class="ri-percent-line"></i>
                        Discounts
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/customers*') }" href="/admin/customers">
                        <i class="ri-group-line"></i>
                        Customers
                      </a>
                    </li>
                    <li class="nav-item"  v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/reporting*') }" href="/admin/reporting">
                        <i class="ri-file-chart-line"></i>
                        Reporting
                      </a>
                    </li>
                    <li class="nav-item"  v-if="role">
                      <a class="nav-link" :class="{ 'active': request.match('admin/subscriptions*') }" href="/admin/subscriptions">
                        <i class="ri-cloud-fill"></i>
                        Subscriptions
                      </a>
                    </li>
                  </ul>
                </div><!-- End card body -->
              </div><!-- End collapse -->
            </div>
            <!-- End card -->
            <div class="card" v-if="role">
              <div class="card-header" id="headingtwo">
                <a href="#" data-toggle="collapse" data-target="#collapseTwo">Users
                  <i class="ri-arrow-down-s-line float-right"></i></a>
              </div>
              <!--End card header-->
              <div id="collapseTwo" class="collapse" :class="{ 'show': request.match('admin/users*') || request.match('admin/permissions*') || request.match('admin/roles*') }" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/users*') }" href="/admin/users">
                        <i class="bx ri-user-line"></i>
                        Users
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/roles*') }" href="/admin/roles">
                        <i class="ri-map-pin-user-line"></i>
                        Roles
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/permissions*') }" href="/admin/permissions">
                        <i class="bx ri-lock-2-line"></i>
                        Permissions
                      </a>
                    </li>
                  </ul>
                </div>
                <!--                End card body-->
              </div>
              <!--             End collapse-->
            </div>
            <!--End card-->
<!--            <div class="menu-title">Pages</div>-->
            <div class="card" v-if="role">
              <div class="card-header" id="headingFour">
                <a href="#" data-toggle="collapse" data-target="#collapseFour">Advanced
                  <i class="ri-arrow-down-s-line float-right"></i></a>
              </div><!-- End card header -->
              <div id="collapseFour" class="collapse" :class="{ 'show': request.match('admin/variables') || request.match('admin/authentication') || request.match('admin/import') || request.match('admin/settings') }" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/variables') }" href="/admin/variables">
                        <i class="ri-code-s-slash-line"></i>
                        Variables
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/authentication') }" href="/admin/authentication">
                        <i class="ri-lock-line"></i>
                        Authentication
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/import') }" href="/admin/import">
                        <i class="ri-login-box-line"></i>
                        Data Import
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/settings') }" href="/admin/settings">
                        <i class="ri-settings-3-line"></i>
                        Settings
                      </a>
                    </li>
                  </ul>
                </div><!-- End card body -->
              </div><!-- End collapse -->
            </div>
            <!-- End card -->
            <div class="card" v-if="role">
              <div class="card-header" id="headingOne">
                <a href="#" data-toggle="collapse" data-target="#collapseOne">Site Management
                  <i class="ri-arrow-down-s-line float-right"></i></a>
              </div>
              <!--              End card header-->
              <div id="collapseOne" class="collapse" :class="{ 'show': request.match('admin/pages*') || request.match('admin/templates*') || request.match('admin/components') || request.match('admin/navigation*') }" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/pages*') }" href="/admin/pages">
                        <i class="bx ri-pages-line"></i>
                        Pages
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/templates*') }" href="/admin/templates">
                        <i class="bx ri-slideshow-line"></i>
                        Templates
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/components') }" href="/admin/components">
                        <i class="ri-tools-fill"></i>
                        Components
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" :class="{ 'active': request.match('admin/navigation*') }" href="/admin/navigation">
                        <i class="ri-compass-3-line"></i>
                        Navigation
                      </a>
                    </li>
                  </ul>
                </div>
                <!--                End card body-->
              </div>
              <!--             End collapse-->
            </div>
            <!--End card-->
            <div class="card">
              <div class="card-header" id="headingFive">
                <a href="#" data-toggle="collapse" data-target="#collapseFive" class="accordion-button">
                  Account
                  <i class="ri-arrow-down-s-line float-right"></i></a>
              </div><!-- End card header -->

              <div id="collapseFive" class="collapse" :class="{ 'show': request.match('admin/logout') }" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                  <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link logoutBtn" @click="$refs.logout_form.submit()">
                        <i class="ri-logout-circle-r-line"></i>
                        Logout ({{ user }})
                      </a>
                    </li>
                  </ul>
                </div><!-- End card body -->
              </div><!-- End collapse -->
            </div>
            <!-- End card -->
          </div><!-- End accordion sidebar -->
          <form ref="logout_form" action="/admin/logout" method="POST" style="display: none;">
            <input type="hidden" name="_token" :value="csrfToken">
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
    user: {
      type: String,
      required: true
    },
    permissions: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      visible: true
    }
  },
  computed: {
    csrfToken() {
      return window.Laravel.csrfToken;
    },
    appName() {
      return `${process.env.MIX_APP_NAME} Portal`
    }
  },
  methods: {
    onResize() {
      this.visible = window.innerWidth >= 800;
    },
    logout() {
      event.preventDefault();
      axios.post('/logout').then(response => {

      }).catch(error => {
        location.reload();
      });
    }
  },
  created() {
    this.onResize()
    // window.addEventListener('resize', this.onResize)
  },

  beforeDestroy() {
    !this.onResize()
    // window.removeEventListener('resize', this.onResize)
  },
}
</script>

<style scoped>
.logoutBtn {
  cursor: pointer;
}
.logoImage {
  max-width: 100%;
  width: 100px;
}
</style>
