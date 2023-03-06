<template>
  <div class="customer-details">
    <div class="row">
    </div>
    <b-modal ref="issueRefund" id="issueRefund" title="Issue a Refund" hide-footer>
      <div v-if="refundProcessing === false && refundError === false && refundSuccess !== true">
        <label> Please select a reason for the refund:</label>
        <b-form-select v-model="reason" :options="reasonOptions"></b-form-select>
        <b-form-checkbox v-if="customerSubscription.status !== 'CANCELED'"  v-model="cancelSubscriptionOnRefund"> Cancel Subscription on Refund</b-form-checkbox>
        <button class="btn btn-default mt-2 float-right" @click="hideRefund">Exit</button>
        <button class="btn btn-danger mt-2 mr-1 float-right" block @click="toggleRefund">Issue Refund</button>
      </div>
      <div v-if="refundError === true">
        <p>{{ refundErrorMessage.code }}</p>
      </div>
      <div v-if="refundSuccess === true">
        <p>Successfully refunded order {{ customerSubscription.unique_id }}</p>
      </div>
      <div v-show="refundProcessing" class="text-center">
        <b-spinner label="Spinning"></b-spinner>
      </div>
    </b-modal>

    <div v-show="loading" class="text-center">
      <b-spinner label="Spinning"></b-spinner>
    </div>

    <div class="row mt-2">
      <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <h1 class="h6 mt-1">Customer Subscription &rarr; <strong>{{ customerSubscription.unique_id }}</strong></h1>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-12">
        <b-button size="sm" class="w-100 mr-1" @click="goBack">&larr; Back to members</b-button>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-sm-12 col-md-6 mb-2">
        <b-card>
          <b-card-text>
            <h5 class="heading-style"><i class="fas fa-user"></i> Subscription Details</h5>
            <table class="table table-striped">
              <tr>
                <td width="40%"><strong>ID</strong></td>
                <td>{{ customerSubscription.unique_id }}</td>
              </tr>
              <tr>
                <td><strong>Customer</strong></td>
                <td>{{ customerSubscription?.customer?.email }}</td>
              </tr>
              <tr>
                <td><strong>Status</strong></td>
                <td>
                  <b-badge :class="'badge-soft-' + getStatusClass(_.get(customerSubscription, 'status'))">{{
                      titleCase(customerSubscription.status)
                    }}
                  </b-badge>
                </td>
              </tr>
              <tr>
                <td><strong>Start Date</strong></td>
                <td>{{ formatDate(customerSubscription.start_date) }}</td>
              </tr>
              <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{ formatDate(customerSubscription.expiry_date) }}</td>
              </tr>
            </table>
          </b-card-text>
        </b-card>
      </div><!-- End col -->
      <div class="col-sm-12 col-md-6 mt-2">
                <b-card>
                  <b-card-text>
                    <div class="row heading-style">
                      <div class="col-8 col-sm-10 col-lg-10 align-self-end">
                        <h5>
                          <i class="ri-bank-card-fill" style="font-size: 20px"></i> Payments
                        </h5>
                      </div>
                    </div>

                    <b-table :fields="paymentFields"
                             ref="table"
                             :busy.sync="isBusy"
                             :items="tableDataProvider"
                             :per-page="perPage"
                             :current-page="currentPage">
                      <template v-slot:cell(id)="data">
                        {{ data.item.id }}
                      </template>
                      <template v-slot:cell(ext_id)="data">
                        {{ data.item.ext_id }}
                      </template>
                      <template v-slot:cell(status)="data">
                        <span v-if="data.item.status === 'COMPLETED'" class="badge badge-success">{{ data.item.status }}</span>
                        <span v-if="data.item.status === 'REFUNDED'" class="badge badge-secondary">{{ data.item.status }}</span>
                      </template>
                      <template v-slot:cell(actions)="data">
                        <b-button size="sm"
                                  class="float-right mr-3"
                                  disabled="true"
                                  variant="info">
                          Logs
                        </b-button>
                        <b-button v-if="data.item.status === 'COMPLETED'"
                                  size="sm"
                                  class="float-right mr-3"
                                  variant="primary" @click="showRefund(data.item.id)">
                          Refund
                        </b-button>
                      </template>
                    </b-table>

                  </b-card-text>
                </b-card>
      </div><!-- End col -->
    </div><!-- End row -->
  </div>
</template>

<script>
import _ from 'lodash';
import moment from "moment";
import helpers from '../helpers';

export default {
  mounted() {
    console.log('Component mounted.');
    this._ = _;
    this.orderConfirmationEmail = this.customerSubscription.customer.email;
    this.loading = false;
  },
  props: {
    customerSubscription: {
      type: Object
    },
  },
  data() {
    return {
      helpers: helpers,
      _: _,
      orderConfirmationEmail: '',
      disabled: false,
      isBusy: false,
      refundProcessing: false,
      refundError: false,
      refundSuccess: false,
      refundErrorMessage: '',
      refundSuccessMessage: '',
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15],
      reason: 'duplicate',
      cancelSubscriptionOnRefund: false,
      loading: true,
      showResults: false,
      paymentFields: [
        {key: 'id', label: 'ID'},
        {key: 'ext_id', label: 'External ID'},
        {key: 'status', label: 'Status'},
        {key: 'actions', label: ''}
      ],
      payments: {
        paymentId: null,
      },
      productFields: [
        {key: 'image', label: 'Image'},
        {key: 'sku', label: 'SKU'},
        {key: 'price', label: 'Price'},
        {key: 'quantity', label: 'Quantity'},
        {key: 'ticket', label: 'Ticket'},
      ],
      reasonOptions: [
        {value: 'duplicate', text: 'Duplicate'},
        {value: 'fraudulent', text: 'Fraudulent'},
        {value: 'requested_by_customer', text: 'Requested by Customer'},
      ],
    }
  },
  methods: {
    goBack() {
      window.location = '/admin/members';
    },
    getStatusClass(status) {
      switch (status) {
        case 'CANCELLED':
          return 'warning';
        case 'ACTIVE':
          return 'primary';
      }
    },
    tableDataProvider(context) {
      this.isBusy = true;
      const promise = axios.get(
          '/api/members/' + this.customerSubscription.id +'/payments?page=' + context.currentPage + '&perPage=' +
          context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
          { withCredentials: true }
      );

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
    showRefund(payId) {
      this.payments.paymentId = payId;
      this.$refs['issueRefund'].show();
    },
    hideRefund() {
      this.payments.paymentId = null;
      this.$refs['issueRefund'].hide();
      location.reload();
    },
    orderComplete(order) {
      return order.order_status === 'complete' || order.order_status === 'fulfilled';
    },
    titleCase(status) {
      return _.startCase(_.toLower(status));
    },
    toggleRefund() {
      let self = this;
      self.refundProcessing = true;
      const requestData = {
        reason: this.reason,
        cancelSubscription: this.cancelSubscriptionOnRefund,
      };

      axios.patch(
          '/api/members/' + this.customerSubscription.id + '/payments/' + this.payments.paymentId,
          requestData
      ).then(function (result) {
        self.refundError = false;
        self.refundSuccess = true;
        self.refundProcessing = false;
        location.reload();
        // this.$root.emit('bv::refresh::table', 'issueRefund');
      }).catch(function (error) {
        console.log('Refund Order failed', {
          error: error.response.errors
        });

        self.refundError = true;
        self.refundSuccess = false;
        self.refundErrorMessage = error.response.data.message;
        self.refundProcessing = false;
      });
    },
    formatDate(date) {
      return moment(date).format('MMMM Do YYYY');
    },
  }
}
</script>

<style lang="scss">
.customer-details {
  font-size: .9rem;

  .heading-style {
    border-bottom: 2px solid #CCC;
    padding-bottom: 1rem;
    margin-bottom: 0;
    font-size: 1.1rem;
  }
}

@media screen and (min-width: 800px) {
  .btnStyle {
    position: absolute;
    right: 0;
    top: 0;
  }
}

@media screen and (max-width: 800px) {
  .btnStyle {
    position: absolute;
    right: 0;
    top: 68 !important;
  }
}

.collapsed > .when-opened,
:not(.collapsed) > .when-closed {
  display: none;
}
</style>
