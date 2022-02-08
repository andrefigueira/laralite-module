<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <alert-component :alert="alert"></alert-component>
      </div><!-- End col -->
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <b-input-group size="sm" class="m-2 mr-0 pr-3">
              <b-form-input
                v-model="filter"
                type="search"
                id="filterInput"
                placeholder="Type to Search"
                style="padding: 18px 10px"
                class="mr-2"
              ></b-form-input>
              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
          <div class="col-md-6" v-if="checkedOrders.length">
            <b-button class="mt-2 float-right mr-4" @click="uncheckAll" :disabled="checkedOrders.length == 0">Clear All</b-button>
            <a class="btn btn-danger mt-2 float-right mr-4" :disabled="checkedOrders.length == 0" v-b-modal.issueRefund>Process Bulk Refunds</a>
          </div>
        </div>
<!--        {{ checkedOrders }}-->
        <b-modal ref="issueRefund" id="issueRefund" title="Issue Bulk Refunds" hide-footer no-close-on-backdrop>
          <div v-if="refundProcessing === false && refundError === false && refundSuccess !== true">
            <label>{{ checkedOrders.length }} Orders to be Refunded</label>
            <p>Are You Sure?</p>
            <b-button class="mt-2" variant="warning" block @click="toggleRefund">Issue Refund</b-button>
          </div>
          <div v-if="refundError === true">
            <p>{{ refundErrorMessage }}</p>
          </div>
          <div v-if="refundSuccess === true">
            <p>{{ checkedOrders.length }} successfully refunded.</p>
          </div>
          <div v-show="refundProcessing" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
          </div>
          <b-button class="mt-3" block @click="hideRefund">Exit</b-button>
        </b-modal>
      </div>
      <div class="col-md-12">
        <div class="table-responsive-sm">
          <b-table
            hover
            show-empty
            ref="table"
            :busy.sync="isBusy"
            :items="tableDataProvider"
            :fields="filteredFields"
            :per-page="perPage"
            :current-page="currentPage"
            :filter="filter"
            sortDesc>
            <template v-slot:cell(unique_id)="data" class="min-width-0">
              <div class="row">
                <div class="col-1"><input type="checkbox" class="" :value="data.item" :id="data.item.unique_id" v-model="checkedOrders"></div>
<!--                <div class="col-10">
                  <span style="font-size: 0.7rem; display: none">{{ data.item.unique_id }}</span><br>
                  <b-badge class="badge-soft-info">{{ data.item.customer.email }}</b-badge>
                  <b-badge class="badge-soft-danger" v-if="data.item.refunded"><i class="fas fa-check-circle"></i>Refunded</b-badge>
                </div>-->
              </div>
            </template>
            <template v-slot:cell(confirmation_code)="data">
              <span>{{ data.item.confirmation_code }}</span>
              <b-badge class="badge-soft-danger" v-if="data.item.refunded"><i class="fas fa-check-circle"></i>Refunded</b-badge>
            </template>
            <template v-slot:cell(created_at)="data">
              <span>{{ timeFormat(data.item.created_at) }}</span>
            </template>
            <template v-slot:cell(customer_id)="data">
              <span>{{ data.item.customer.name }}</span>
            </template>
            <template v-slot:cell(customer_email)="data">
              <span>{{ data.item.customer.email }}</span>
            </template>
            <template v-slot:cell(basket)="data">
              <span>{{ data.item.basket.subtotals[0].total}}/-</span>
<!--              <b-badge :class="data.item.tickets.validated ? 'badge-soft-primary' : 'badge-soft-danger'"><i class="fas fa-check-circle"></i>{{ data.item.tickets.validated ? 'Reedemed' : 'Pending' }}</b-badge>-->
            </template>
            <template v-slot:cell(status)="data">
<!--              <span>{{ data.item.tickets.validated ? 'Reedemed' : 'Pending' }}</span>-->
              <b-badge :class="data.item.tickets.status === 'REDEEMED' ? 'badge-soft-primary' : 'badge-soft-danger'"><i class="fas fa-check-circle"></i>{{ data.item.tickets.validated === 'REDEEMED' ? 'Reedemed' : 'Pending' }}</b-badge>
            </template>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="View Order" :href="'/admin/orders/view/' + data.item.unique_id" class="btn btn-sm btn-primary float-right mr-3" style="font-size: 12px">View</a>
            </template>
          </b-table>
        </div>
      </div><!-- End col -->
    </div><!-- End row -->
    <div class="float-right m-2">
      <ul class="pagination pagination-rounded mb-0">
        <b-pagination
            class="ml-2"
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
        ></b-pagination>
      </ul>
    </div>
  </div>
</template>

<script>
import * as moment from "moment";
import AlertComponent from "./AlertComponent";

export default {
  components: {AlertComponent},
  computed: {
    filteredFields() {
      if(!this.visible) {
        return [
          {key: 'unique_id', label: '', sortDirection: 'desc'},
          {key: 'confirmation_code', label: 'Confirmation Code', sortable: true, sortDirection: 'desc'},
          {key: 'created_at', label: 'Order Date', sortable: true, sortDirection: 'desc'},
          {key: 'customer_id', label: 'Customer Name', sortable: true, sortDirection: 'desc'},
          {key: 'customer_email', label: 'Customer Email'},
          {key: 'basket', label: 'Total', sortable: true, sortDirection: 'desc'},
          {key: 'status', label: 'Status', sortable: true, sortDirection: 'desc'},
          {key: 'actions', label: ''}
        ]
      } else {
        return [
          {key: 'unique_id', label: '', sortDirection: 'desc'},
          {key: 'confirmation_code', label: 'Confirmation Code', sortable: true, sortDirection: 'desc'},
          {key: 'actions', label: ''}
        ]
      }
    }
  },
  data() {
    return {
      visible: true,
      disabled: false,
      refundProcessing: false,
      refundError: false,
      refundSuccess: false,
      refundErrorMessage: '',
      refundSuccessMessage: '',
      checkedOrders: [],
      // Alert settings
      alert: {
        show: false,
        dismissible: true,
        message: '',
        variant: 'success',
        dismissCountDown: 0,
        dismissSecs: 3
      },

      // Table settings
      fields: [
        {key: 'unique_id', label: 'Order ID', sortable: true, sortDirection: 'desc'},
        {key: 'confirmation_code', label: 'Confirmation Code', sortable: true, sortDirection: 'desc'},
        {key: 'customer_id', label: 'Customer Name', sortable: true, sortDirection: 'desc'},
        {key: 'customer_email', label: 'Customer Email'},
        {key: 'created_at', label: 'Order Date', sortable: true, sortDirection: 'desc'},
        {key: 'actions', label: ''}
      ],
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15],
      sortBy: '',
      sortDesc: false,
      sortDirection: 'asc',
      filter: null,
      filterOn: [],
      isBusy: false,
    }
  },
  methods: {
    onResize() {
      this.visible = window.innerWidth <= 700;
    },
    uncheckAll() {
      this.checkedOrders = []
    },
    check: function (e) {
      if (e.target.checked) {
        console.log(e.target.value)
      }
    },
    timeFormat(time) {
      return moment(time).fromNow();
    },
    tableDataProvider(context) {
      this.isBusy = true;

      const promise = axios.get(
          '/api/order?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
          {withCredentials: true});

      return promise.then((data) => {
        const items = data.data.data;

        this.totalRows = data.data.total;

        this.isBusy = false;

        // console.log(items);
        return items;
      }).catch(error => {
        this.isBusy = false;
        return [];
      })
    },
    hideRefund() {
      this.$refs['issueRefund'].hide();
      // location.reload();
      /*this.$root.$emit('bv::refresh::table', 'orders-table')*/
      // this.tableDataProvider(this)
    },
    toggleRefund() {
      let self = this;
      self.refundProcessing = true;

      let formData = {
        orders: this.checkedOrders,
      };

      axios.post('/api/order/bulk-refunds', formData).then(function (result) {
        self.refundError = false;
        self.refundSuccess = true;
        self.refundProcessing = false;
        self.$root.$emit('bv::refresh::table', 'orders-table')
        // location.reload();
      }).catch(function (error) {
        console.log('Bulk Refund for Orders Failed', {
          error: error.response.data
        });
        self.refundError = true;
        self.refundSuccess = false;
        self.refundErrorMessage = 'We are sorry! Something unexpected happened. Please contact admin.';
        self.refundProcessing = false;
      });
    },
  },

  created() {
    this.onResize();
    // window.addEventListener('resize', this.onResize)
  },

  beforeDestroy() {
    !this.onResize();
    // window.removeEventListener('resize', this.onResize)
  },
}

</script>

<style scoped>
input[type="checkbox"]{
  width: 18px; /*Desired width*/
  height: 18px; /*Desired height*/
  cursor: pointer;
}

@media screen and (max-width: 800px) {
  .hideDiv {
    display: none!important;
  }
}
</style>
