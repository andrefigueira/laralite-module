<template>
    <div class="customer-details">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Order &rarr; <strong>{{ order.unique_id }}</strong></h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">

                </div>
            </div><!-- End toolbar -->
        </div><!-- End content bar -->

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Order Details</h5>
                      <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ order.unique_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Customer</strong></td>
                                <td>{{ order.payment_processor_result.receipt_email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><b-badge variant="success"><i class="fas fa-check-circle"></i> Accepted</b-badge></td>
                            </tr>
                            <tr>
                                <td><strong>Date</strong></td>
                                <td>{{ order.created_at }}</td>
                            </tr>
                        </table>
                      </div>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-sm-12 col-md-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Order Basket</h5>
                        <b-table striped :fields="productFields" :items="order.basket.products" responsive="sm">
                            <template #cell(image)="data">
                                 <b-img thumbnail fluid :src="data.item.image" :alt="data.item.sku" style="max-width: 80px;"></b-img>
                            </template>

                            <template #cell(sku)="data">
                                {{ data.item.sku }}
                            </template>

                            <template #cell(price)="data">
                                {{ data.item.price }}
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
                        <h5 class="heading-style"><i class="fas fa-user"></i> Payment Details</h5>
                      <div class="table-responsive-sm">
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
                                <td>{{ order.payment_processor_result.amount }}</td>
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
                      </div>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-sm-12 col-md-6 mt-2">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Payment Method</h5>
                      <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ order.payment_processor_result.payment_method }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Number</strong></td>
                                <td>**** **** **** {{ order.payment_processor_result.payment_method_details.card.last4 }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Fingerprint</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.fingerprint }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Expires</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.exp_month }} / {{ order.payment_processor_result.payment_method_details.card.exp_year }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Type</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.brand }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Owner</strong></td>
                                <td>{{ order.payment_processor_result.billing_details.name }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Post Code</strong></td>
                                <td>{{ order.payment_processor_result.billing_details.address.postal_code }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Origin</strong></td>
                                <td>{{ order.payment_processor_result.billing_details.address.country }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>CVC Check</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.checks.cvc_check }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Postal Check</strong></td>
                                <td>{{ order.payment_processor_result.payment_method_details.card.checks.address_postal_code_check }}</td>
                            </tr>
                        </table>
                      </div>
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
                loading: true,
                showResults: false,
                productFields: [
                    { key: 'image', label: 'Image' },
                    { key: 'sku', label: 'SKU' },
                    { key: 'price', label: 'Price' },
                    { key: 'quantity', label: 'Quantity' },
                ],
            }
        },
        methods: {

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
