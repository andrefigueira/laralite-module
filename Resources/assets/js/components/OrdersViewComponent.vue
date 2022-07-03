<template>
  <div class="customer-details">
    <div class="row">
    </div>
    <b-modal ref="issueRefund" id="issueRefund" title="Issue a Refund" hide-footer>
      <div v-if="refundProcessing === false && refundError === false && refundSuccess !== true">
        <label> Please select a reason for the refund:</label>
        <b-form-select v-model="reason" :options="reasonOptions"></b-form-select>
        <button class="btn btn-default mt-2 float-right" @click="hideRefund">Exit</button>
        <button class="btn btn-danger mt-2 mr-1 float-right" block @click="toggleRefund">Issue Refund</button>
      </div>
      <div v-if="refundError === true">
        <p>{{ refundErrorMessage.code }}</p>
      </div>
      <div v-if="refundSuccess === true">
        <p>Successfully refunded order {{ order.unique_id }}</p>
      </div>
      <div v-show="refundProcessing" class="text-center">
        <b-spinner label="Spinning"></b-spinner>
      </div>
    </b-modal>
    <b-modal ref="cancelOrder" id="cancelOrder" title="Cancel Order" hide-footer>
      <div v-if="cancelProcessing === false && cancelError === false && cancelSuccess !== true">
        <p>Are You Sure?? <br/> <strong>Order Id </strong>:- {{ order.unique_id }}</p>
        <button class="btn btn-default mt-2 float-right" block @click="$bvModal.hide('cancelOrder')">Exit</button>
        <button class="btn btn-danger mt-2 mr-1 float-right" variant="warning" block @click="toggleCancel">Cancel
          Order
        </button>
      </div>
      <div v-if="cancelError === true">
        <p>{{ cancelErrorMessage }}</p>
      </div>
      <div v-if="cancelSuccess === true">
        <p>Successfully canceled order {{ order.unique_id }}</p>
      </div>
      <div v-show="cancelProcessing" class="text-center">
        <b-spinner label="Spinning"></b-spinner>
      </div>
    </b-modal>
    <b-modal ref="redeemOrder" id="redeemOrder" title="Redeem Order" hide-footer>
      <div v-if="redeemProcessing === false && redeemError === false && redeemSuccess !== true">
        <p>Are You Sure?? <br/> <strong>Order Id </strong>:- {{ order.unique_id }}</p>
        <button class="btn btn-default mt-2 float-right" block @click="$bvModal.hide('redeemOrder')">Exit</button>
        <button class="btn btn-danger mt-2 mr-1 float-right" variant="warning" block @click="toggleRedeem">Redeem
          Order
        </button>
      </div>
      <div v-if="redeemError === true">
        <p>{{ redeemErrorMessage }}</p>
      </div>
      <div v-if="redeemSuccess === true">
        <p>Successfully redeemed order {{ order.unique_id }}</p>
      </div>
      <div v-show="redeemProcessing" class="text-center">
        <b-spinner label="Spinning"></b-spinner>
      </div>
    </b-modal>
    <b-modal ref="unredeemOrder" id="unredeemOrder" title="Unredeem Order" hide-footer>
      <div v-if="unredeemProcessing === false && unredeemError === false && unredeemSuccess !== true">
        <p>Are You Sure?? <br/> <strong>Order Id </strong>:- {{ order.unique_id }}</p>
        <button class="btn btn-default mt-2 float-right" block @click="$bvModal.hide('unredeemOrder')">Exit</button>
        <button class="btn btn-danger mt-2 mr-1 float-right" variant="warning" block @click="toggleUnredeem">Unredeem
          Order
        </button>
      </div>
      <div v-if="unredeemError === true">
        <p>{{ unredeemErrorMessage }}</p>
      </div>
      <div v-if="unredeemSuccess === true">
        <p>Successfully redeemed order {{ order.unique_id }}</p>
      </div>
      <div v-show="unredeemProcessing" class="text-center">
        <b-spinner label="Spinning"></b-spinner>
      </div>
    </b-modal>
    <b-modal ref="resendOrderConfirmationEmail" id="resendOrderConfirmationEmail" title="Send Order Confirmation Email" hide-footer>
      <div>
        <p>Are You Sure?? <br/> <strong>Order Id </strong>:- {{ order.unique_id }}</p>
        <b-form-group label-cols="4" label-cols-lg="2" label="Send To" label-for="order-confirmation-email">
          <b-form-input id="order-confirmation-email" v-model="orderConfirmationEmail" ></b-form-input>
        </b-form-group>
        <button class="btn btn-default mt-2 float-right" block @click="$bvModal.hide('resendOrderConfirmationEmail')">Exit</button>
        <button class="btn btn-danger mt-2 mr-1 float-right" variant="warning" block @click="sendOrderConfirmationEmail">
          Send
        </button>
      </div>
      <div v-if="orderConfirmationEmailError === true">
        <p>{{ orderConfirmationEmailErrorMessage }}</p>
      </div>
      <div v-if="orderConfirmationEmailSuccess === true">
        <p>Successfully sent order confirmation email {{ order.unique_id }}</p>
      </div>
      <div v-show="orderConfirmationEmailProcessing" class="text-center">
        <b-spinner label="Spinning"></b-spinner>
      </div>
    </b-modal>

    <div v-show="loading" class="text-center">
      <b-spinner label="Spinning"></b-spinner>
    </div>

    <div class="row mt-2">
      <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <h1 class="h6 mt-1">Order &rarr; <strong>{{ order.unique_id }}</strong></h1>
      </div>
      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
        <b-button size="sm" class="mr-1 float-right mt-1 mt-lg-0"
                  :disabled="order.refunded === 1 || (_.get(order, 'tickets[0].status') === 'REDEEMED' && _.get(order, 'tickets[0].visited_counts', null) != null)"
                  variant="warning" v-b-modal.issueRefund>Issue Refund
        </b-button>
        <b-button size="sm" class="mr-1 float-right mt-1 mt-lg-0"
                  :disabled="order.order_status === 'cancel' || (_.get(order, 'tickets[0].status') === 'REDEEMED' && _.get(order, 'tickets[0].visited_counts', null) != null)"
                  variant="warning" v-b-modal.cancelOrder>Cancel Order
        </b-button>
        <br class="d-lg-none"/><br class="d-lg-none"/>
        <b-button size="sm" class="mr-1 float-right mt-1 mt-lg-0" variant="warning"
                  :disabled="order.refunded === 1 || order.order_status === 'cancel' || (_.get(order, 'tickets[0].status') === 'REDEEMED' && _.get(order, 'tickets[0].visited_counts', null) != null)"
                  v-b-modal.redeemOrder>Redeem Order
        </b-button>
        <b-button size="sm" class="mr-1 float-right mt-1 mt-lg-0"
                  :disabled="order.refunded === 1 || _.get(order, 'tickets[0].status') !== 'REDEEMED'"
                  variant="warning" v-b-modal.unredeemOrder>Unredeem Order
        </b-button>
        <br class="d-lg-none"/><br class="d-lg-none"/>
        <b-button size="sm" class="mr-1 float-right mt-1 mt-lg-0"
                  :disabled="order.refunded === 1 || order.order_status === 'cancel' || order.order_status !== 'complete'"
                  variant="warning" v-b-modal.resendOrderConfirmationEmail>Send Order Confirmation Email
        </b-button>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-12">
        <b-button size="sm" class="w-100 mr-1" @click="goBack">&larr; Back to orders</b-button>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-sm-12 col-md-6 mb-2">
        <b-card>
          <b-card-text>
            <h5 class="heading-style"><i class="fas fa-user"></i> Order Details</h5>
            <table class="table table-striped">
              <tr>
                <td width="40%"><strong>ID</strong></td>
                <td>{{ order.confirmation_code }}</td>
              </tr>
              <tr>
                <td><strong>Customer</strong></td>
                <td>{{ order.customer.email }}</td>
              </tr>
              <tr v-if="order.refunded">
                <td><strong>Status</strong></td>
                <td>
                  <b-badge class="badge-soft-warning"><i class="fas fa-check-circle"></i>Refunded</b-badge>
                </td>
              </tr>
              <tr v-if="order.order_status === 'complete' || order.order_status === null">
                <td><strong>Status</strong></td>
                <td>
                  <b-badge class="badge-soft-primary"
                           v-if="order.order_status === 'complete' || order.order_status === null"><i
                      class="fas fa-check-circle"></i>Accepted
                  </b-badge>
                </td>
              </tr>
              <tr>
                <td><strong>Date</strong></td>
                <td>{{ formatDate(order.created_at) }}</td>
              </tr>
            </table>
          </b-card-text>
        </b-card>
      </div><!-- End col -->
      <div class="col-sm-12 col-md-6">
        <b-card>
          <b-card-text>
            <h5 class="heading-style"><i class="ri-shopping-basket-fill" style="font-size: 20px"></i> Order Basket</h5>
            <b-table striped :fields="productFields" :items="order.basket.products" responsive="sm">
              <template #cell(image)="data">
                <b-img thumbnail fluid :src="data.item.image" :alt="data.item.sku" style="max-width: 80px;"></b-img>
              </template>

              <template #cell(sku)="data">
                {{ data.item.sku }}
              </template>

              <template #cell(price)="data">
                ${{ helpers.priceFormat((data.item.price) * data.item.quantity) }}
              </template>

              <template #cell(quantity)="data">
                {{ data.item.quantity }}
              </template>
            </b-table>
          </b-card-text>
        </b-card>
      </div><!-- End col -->
      <div class="col-sm-12 col-md-6 mt-2">
        <b-card>
          <b-card-text>
            <div class="row heading-style">
              <div class="col-8 col-sm-10 col-lg-10 align-self-end">
                <h5>
                  <i class="ri-bank-card-fill" style="font-size: 20px"></i> Payment Details
                </h5>
              </div>
              <div class="col-4 col-sm-2 col-lg-2">
                <b-button class="float-right" v-b-toggle.payment-details variant="default">
                  <span class="when-opened">
                     <i class="ri-subtract-line"></i>
                  </span>
                  <span class="when-closed">
                     <i class="ri-add-line"></i>
                  </span>
                </b-button>
              </div>
            </div>

            <b-collapse id="payment-details">
              <table class="table table-striped">
                <tr v-if="!order.payment_processor_result">
                  <td><strong>Credits Used</strong></td>
                  <td>{{ order.basket.products[0].credits}}</td>
                </tr>
                <tr v-if="!order.payment_processor_result">
                  <td><strong>Credit Balance</strong></td>
                  <td>N/A</td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td><strong>ID</strong></td>
                  <td>{{ (order.payment_processor_result) ? (order.payment_processor_result.id) : 'na'  }}</td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td width="40%"><strong>Payment Descriptor</strong></td>
                  <td>{{ (order.payment_processor_result) ? (order.payment_processor_result.calculated_statement_descriptor) : 'na' }}</td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td width="40%"><strong>Description</strong></td>
                  <td>{{ (order.payment_processor_result) ? (order.payment_processor_result.description) : 'na' }}</td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td><strong>Total Amount</strong></td>
                  <td>${{ (order.payment_processor_result) ? (order.payment_processor_result.amount / 100) :'na' }}</td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td><strong>Tax Applied</strong></td>
                  <td>
                    {{ (order.basket.subtotals[0]) ? '$' + helpers.priceFormat(order.basket.taxAmount) : 'n/a' }}
                  </td>
                </tr>
                <tr v-if="order.basket.discountAmount > 0">
                  <td><strong>Discount Amount</strong></td>
                  <td>
                    ({{ order.basket.discounts[0].code }}) {{  helpers.priceFormat(order.basket.discountAmount) }}
                  </td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td><strong>Service Fee</strong></td>
                  <td>
                    {{ (order.basket.serviceFee) ? helpers.priceFormat(order.basket.serviceFee) : 'n/a' }}
                  </td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td><strong>Fee</strong></td>
                  <td>
                    {{ order.payment_processor_result ? order.payment_processor_result.application_fee_amount / 100 : 'n/a' }}
                  </td>
                </tr>
                <tr v-if="order.payment_processor_result">
                  <td><strong>Balance Transaction</strong></td>
                  <td>{{ (order.payment_processor_result) ? (order.payment_processor_result.balance_transaction) : 'na' }}</td>
                </tr>
              </table>
            </b-collapse>

          </b-card-text>
        </b-card>
      </div><!-- End col -->
      <div class="col-sm-12 col-md-6 mt-2">
        <b-card>
          <b-card-text>
            <div class="row heading-style">
              <div class="col-8 col-sm-10 col-lg-10  align-self-end">
                <h5><i class="fas fa-user"></i> Payment Method</h5>
              </div>
              <div class="col-4 col-sm-2 col-lg-2">
                <b-button class="float-right" v-b-toggle.payment-method variant="default">
                  <span class="when-opened">
                     <i class="ri-subtract-line"></i>
                  </span>
                  <span class="when-closed">
                     <i class="ri-add-line"></i>
                  </span>
                </b-button>
              </div>
            </div>
            <b-collapse id="payment-method">
              <table class="table table-striped">
                <tr v-if="!order.payment_processor_result">
                  <td width="40%"><strong>Payment Method Used</strong></td>
                  <td>Credits</td>
                </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>ID</strong></td>
                <td>{{ order.payment_processor_result.payment_method }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Number</strong></td>
                <td>**** **** **** {{ order.payment_processor_result.charges.data[0].payment_method_details.card.last4 }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Fingerprint</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].payment_method_details.card.fingerprint }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Expires</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].payment_method_details.card.exp_month }} /
                  {{ order.payment_processor_result.charges.data[0].payment_method_details.card.exp_year }}
                </td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Type</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].payment_method_details.card.brand }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Owner</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].billing_details.name }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Post Code</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].billing_details.address.postal_code }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Origin</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].billing_details.address.country }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>CVC Check</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].payment_method_details.card.checks.cvc_check }}</td>
              </tr>
              <tr v-if="order.payment_processor_result">
                <td width="40%"><strong>Postal Check</strong></td>
                <td>{{ order.payment_processor_result.charges.data[0].payment_method_details.card.checks.address_postal_code_check }}</td>
              </tr>
            </table>
            </b-collapse>
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
    this.orderConfirmationEmail = this.order.customer.email;
    this.loading = false;
  },
  props: {
    order: {
      type: Object
    },
  },
  data() {
    return {
      helpers: helpers,
      _: _,
      orderConfirmationEmail: '',
      disabled: false,
      refundProcessing: false,
      refundError: false,
      refundSuccess: false,
      refundErrorMessage: '',
      refundSuccessMessage: '',
      reason: 'duplicate',
      loading: true,
      showResults: false,
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
      cancelProcessing: false,
      cancelError: false,
      cancelSuccess: false,
      cancelErrorMessage: '',
      cancelSuccessMessage: '',
      redeemProcessing: false,
      redeemError: false,
      redeemSuccess: false,
      redeemErrorMessage: '',
      redeemSuccessMessage: '',
      unredeemProcessing: false,
      unredeemError: false,
      unredeemSuccess: false,
      unredeemErrorMessage: '',
      unredeemSuccessMessage: '',
      orderConfirmationEmailProcessing: false,
      orderConfirmationEmailSuccess: false,
      orderConfirmationEmailError: false,
      orderConfirmationEmailErrorMessage: ''
    }
  },
  methods: {
    goBack() {
      window.history.back();
    },
    hideRefund() {
      this.$refs['issueRefund'].hide();
      location.reload();
    },
    toggleRefund() {
      let self = this;
      self.refundProcessing = true;

      let formData = {
        orderId: this.order.id,
        reason: this.reason,
      };

      axios.post('/api/order/refund', formData).then(function (result) {
        self.refundError = false;
        self.refundSuccess = true;
        self.refundProcessing = false;
        location.reload();
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
    toggleCancel() {
      let self = this;
      self.cancelProcessing = true;

      let formData = {
        orderId: this.order.id,
      };

      axios.post('/api/order/cancel', formData).then(function (result) {
        self.cancelError = false;
        self.cancelSuccess = true;
        self.cancelProcessing = false;
        location.reload();
      }).catch(function (error) {
        console.log('cancel failed', {
          error: error.response.errors
        });

        self.cancelError = true;
        self.cancelSuccess = false;
        self.cancelErrorMessage = error.response.data.message;
        self.cancelProcessing = false;
      });
    },
    toggleRedeem() {
      let self = this;
      self.redeemProcessing = true;

      let formData = {
        orderId: this.order.id,
      };

      axios.post('/api/order/redeem', formData).then(function (result) {
        self.redeemError = false;
        self.redeemSuccess = true;
        self.redeemProcessing = false;
        location.reload();
      }).catch(function (error) {
        console.log('redeem failed', {
          error: error.response.errors
        });

        self.redeemError = true;
        self.redeemSuccess = false;
        self.redeemErrorMessage = error.response.data.message;
        self.redeemProcessing = false;
      });
    },
    toggleUnredeem() {
      let self = this;
      self.unredeemProcessing = true;

      let formData = {
        orderId: this.order.id,
      };

      axios.post('/api/order/unredeem', formData).then(function (result) {
        self.unredeemError = false;
        self.unredeemSuccess = true;
        self.unredeemProcessing = false;
        location.reload();
      }).catch(function (error) {
        console.log('unredeem failed', {
          error: error.response.errors
        });

        self.unredeemError = true;
        self.unredeemSuccess = false;
        self.unredeemErrorMessage = error.response.data.message;
        self.unredeemProcessing = false;
      });
    },
    sendOrderConfirmationEmail() {
      let self = this;
      self.orderConfirmationEmailProcessing = true;

      let formData = {
        orderId: this.order.id,
        email: this.orderConfirmationEmail
      };

      axios.post('/api/order/send-order-confirmation-email', formData).then(function (result) {
        self.orderConfirmationEmailProcessing = false;
        self.orderConfirmationEmailSuccess = true;
      }).catch(function (error) {
        console.log('send confirmation email failed', {
          error: error.response.errors
        });

        self.orderConfirmationEmailError = false;
        self.orderConfirmationEmailErrorMessage = error.response.data.message;
        self.orderConfirmationEmailProcessing = false;
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
