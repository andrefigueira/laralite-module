<template>
    <div class="customer-details">
      <div class="d-flex flex-wrap flex-md-nowrap align-items-center mb-2 border-bottom">
        <b-button @click="goBack" variant="link" class="p-0 mr-3">
          <b-icon icon="arrow-left" font-scale="1"></b-icon>
        </b-button>
        <h1 class="h2 mt-1">Order &rarr; <strong>{{ order.unique_id }}</strong></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mt-2 mr-2" style="position: absolute; right: 0; top: 0">
            <b-button class="mr-2" :disabled="order.refunded" variant="warning" v-b-modal.issueRefund>Issue Refund</b-button>
            <b-button :disabled="order.order_status === 'cancel'" variant="warning" v-b-modal.cancelOrder>Cancel Order</b-button>
          </div>
        </div><!-- End toolbar -->
      </div>

        <b-modal ref="issueRefund" id="issueRefund" title="Issue a Refund" hide-footer>
            <div v-if="refundProcessing === false && refundError === false && refundSuccess !== true">
                <label> Please select a reason for the refund:</label>
                <b-form-select v-model="reason" :options="reasonOptions"></b-form-select>
                <button class="btn btn-default mt-2 float-right" @click="hideRefund">Exit</button>
                <button class="btn btn-danger mt-2 mr-1 float-right" block @click="toggleRefund">Issue Refund</button>
            </div>
            <div v-if="refundError === true">
                <p>{{ refundErrorMessage }}</p>
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
          <p>Are You Sure?? <br/> <strong>Order Id </strong>:- {{ order.unique_id}}</p>
          <button class="btn btn-default mt-2 float-right" block @click="hideCancel">Exit</button>
          <button class="btn btn-danger mt-2 mr-1 float-right" variant="warning" block @click="toggleCancel">Cancel Order</button>
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

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Order Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ order.unique_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Customer</strong></td>
                                <td>{{ order.payment_processor_result.receipt_email }}</td>
                            </tr>
                            <tr v-if="order.refunded">
                                <td><strong>Status</strong></td>
                                <td><b-badge class="badge-soft-warning"><i class="fas fa-check-circle"></i>Refunded</b-badge></td>
                            </tr>
                          <tr v-if="order.order_status === 'complete' || order.order_status === null">
                            <td><strong>Status</strong></td>
                            <td><b-badge class="badge-soft-primary" v-if="order.order_status === 'complete' || order.order_status === null"><i class="fas fa-check-circle"></i>Accepted</b-badge></td>
                          </tr>
                            <tr>
                                <td><strong>Date</strong></td>
                                <td>{{ order.created_at }}</td>
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
                                ${{ (data.item.price / 100)*data.item.quantity }}
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
                        <h5 class="heading-style"><i class="ri-bank-card-fill" style="font-size: 20px"></i> Payment Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td><strong>ID</strong></td>
                                <td>{{ order.payment_processor_result.id }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Payment Descriptor</strong></td>
                                <td>{{ order.payment_processor_result.calculated_statement_descriptor }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Description</strong></td>
                                <td>{{ order.payment_processor_result.description }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount</strong></td>
                                <td>${{ order.payment_processor_result.amount / 100 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fee</strong></td>
                                <td>{{ order.payment_processor_result.application_fee_amount === '' ? order.payment_processor_result.application_fee_amount : 'n/a' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Balance Transaction</strong></td>
                                <td>{{ order.payment_processor_result.balance_transaction }}</td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-sm-12 col-md-6 mt-2">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Payment Method</h5>
                        <table class="table table-striped">
                            <tr v-if="order.payment_processor_result.payment_method">
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ order.payment_processor_result.payment_method }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.payment_method_details">
                                <td width="40%"><strong>Number</strong></td>
                                <td>**** **** **** {{ order.payment_processor_result.payment_method_details.card.last4 }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.payment_method_details">
                                <td width="40%"><strong>Fingerprint</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.fingerprint }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.payment_method_details">
                                <td width="40%"><strong>Expires</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.exp_month }} / {{ order.payment_processor_result.payment_method_details.card.exp_year }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.payment_method_details">
                                <td width="40%"><strong>Type</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.brand }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.billing_details">
                                <td width="40%"><strong>Owner</strong></td>
                                <td>{{ order.payment_processor_result.billing_details.name }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.billing_details">
                                <td width="40%"><strong>Post Code</strong></td>
                                <td>{{ order.payment_processor_result.billing_details.address.postal_code }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.billing_details">
                                <td width="40%"><strong>Origin</strong></td>
                                <td>{{ order.payment_processor_result.billing_details.address.country }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.payment_method_details">
                                <td width="40%"><strong>CVC Check</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.checks.cvc_check }}</td>
                            </tr>
                            <tr v-if="order.payment_processor_result.payment_method_details">
                                <td width="40%"><strong>Postal Check</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.checks.address_postal_code_check }}</td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');

            this.loading = false;
        },
        props: {
            order: {
                type: Object
            }
        },
        data() {
            return {
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
                    { key: 'image', label: 'Image' },
                    { key: 'sku', label: 'SKU' },
                    { key: 'price', label: 'Price' },
                    { key: 'quantity', label: 'Quantity' },
                ],
                reasonOptions: [
                    { value: 'duplicate', text: 'Duplicate' },
                    { value: 'fraudulent', text: 'Fraudulent' },
                    { value: 'requested_by_customer', text: 'Requested by Customer' },
                ],
              cancelProcessing: false,
              cancelError: false,
              cancelSuccess: false,
              cancelErrorMessage: '',
              cancelSuccessMessage: '',
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
                    console.log('Canceling Order failed', {
                        error: error.response.data
                    });

                    self.refundError = true;
                    self.refundSuccess = false;
                    self.refundErrorMessage = error.response.data.message;
                    self.refundProcessing = false;
                });
            },
            hideCancel() {
              this.$refs['cancelOrder'].hide()
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
                error: error.response.data
              });

              self.cancelError = true;
              self.cancelSuccess = false;
              self.cancelErrorMessage = error.response.data.message;
              self.cancelProcessing = false;
            });
          }
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
</style>
