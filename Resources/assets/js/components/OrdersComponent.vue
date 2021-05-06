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
              ></b-form-input>
              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
          <div class="col-md-6" v-if="checkedOrders.length">
            <b-button class="mt-2 float-right mr-4" size="sm" @click="uncheckAll" :disabled="checkedOrders.length == 0">Clear All</b-button>
            <b-button class="mt-2 float-right mr-4" variant="danger" size="sm" :disabled="checkedOrders.length == 0" v-b-modal.issueRefund>Process Bulk Refunds</b-button>
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
            :fields="fields"
            :per-page="perPage"
            :current-page="currentPage"
            :filter="filter"
            sortDesc>
            <template v-slot:cell(unique_id)="data" class="min-width-0" style="width: 10%">
              <input type="checkbox" class="" :value="data.item" :id="data.item.unique_id" v-model="checkedOrders">&nbsp;&nbsp;
              <span>{{ data.item.unique_id }}</span>
              <b-badge variant="secondary" v-if="data.item.refunded"><i class="fas fa-check-circle"></i>Refunded</b-badge>
            </template>
            <template v-slot:cell(customer_name)="data">
              <span>{{ data.item.customer.name }}</span>
            </template>
            <template v-slot:cell(customer_email)="data">
              <span>{{ data.item.customer.email }}</span>
            </template>
            <!--            <template v-slot:cell(customer_name)="data">
                          <span v-if="data.item.customer">{{ data.item.customer.name }}</span>
                          <span v-else>N/A</span>
                        </template>
                        <template v-slot:cell(customer_email)="data">
                          <span v-if="data.item.customer">{{ data.item.customer.email }}</span>
                          <span v-else>N/A</span>
                        </template>-->
            <template v-slot:cell(date_created)="data">
              {{ timeFormat(data.item.created_at) }}
            </template>
            <template v-slot:cell(actions)="data">
              <a :href="'/admin/orders/view/' + data.item.unique_id" class="btn btn-sm btn-success float-right mr-1">View</a>
            </template>
          </b-table>
        </div>

        <hr class="pagination-rem">
        <b-pagination
          class="ml-2"
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
        ></b-pagination>
      </div><!-- End col -->
    </div><!-- End row -->
  </div>
</template>

<script>
import * as moment from "moment";
import AlertComponent from "./AlertComponent";

export default {
  components: {AlertComponent},
  data() {
    return {
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
        { key: 'unique_id', label: 'Order ID', sortable: true, sortDirection: 'desc' },
        { key: 'customer_name', label: 'Customer Name' },
        { key: 'customer_email', label: 'Customer Email' },
        { key: 'date_created', label: 'Order Date', sortDirection: 'desc' },
        { key: 'actions', label: '' }
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
    uncheckAll () {
      this.checkedOrders = []
    },
    check: function(e) {
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
        '/api/order?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc
      );

      return promise.then((data) => {
        const items = data.data.data;

        this.totalRows = data.data.total;

        this.isBusy = false;

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
}

</script>

<style scoped>
input[type="checkbox"]{
  width: 18px; /*Desired width*/
  height: 18px; /*Desired height*/
  cursor: pointer;
}
</style>
