<template>
    <div class="customer-details">
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center mb-2 border-bottom">
          <a @click="goBack" class="back-btn p-0 mr-3">
            <b-icon icon="arrow-left" font-scale="1"></b-icon>
          </a>
            <h1 class="h2 mt-1">Customer &rarr; <strong>{{ customer.name }}</strong></h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                </div>
            </div><!-- End toolbar -->
        </div><!-- End content bar -->

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <div class="row">
            <div class="col-md-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Customer Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ customer.unique_id }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><strong>Email</strong></td>
                                <td>{{ customer.email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><b-badge class="badge-soft-primary"><i class="fas fa-check-circle"></i> Account Active</b-badge></td>
                            </tr>
                            <tr>
                                <td><strong>Verified</strong></td>
                                <td>
                                    <b-badge v-if="customer.verification_guid === ''" class="badge-soft-primary"><i class="fas fa-check-circle"></i> Account Verified</b-badge>
                                    <b-badge v-if="customer.verification_guid !== ''" class="badge-soft-danger"><i class="fas fa-times-circle"></i> Not Verified</b-badge>
                                </td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-md-12 mt-2">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Customer Orders</h5>

                        <b-table striped :fields="orderFields" :items="customer.orders" responsive="sm" sortDesc>
                            <template #cell(unique_id)="data">
                                 {{ data.item.unique_id }}
                            </template>
                            <template v-slot:cell(date_created)="data">
                                {{ timeFormat(data.item.created_at) }}
                            </template>
                            <template v-slot:cell(actions)="data">
                                <a v-b-tooltip:hover title="View Order" :href="'/admin/orders/view/' + data.item.unique_id" class="float-right mr-1"><i class="ri-eye-fill"></i></a>
                            </template>
                        </b-table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import * as moment from "moment";

    export default {
        mounted() {
            console.log('Component mounted.');

            this.loading = false;
        },
        props: {
            customer: {
                type: Object
            }
        },
        data() {
            return {
                loading: true,
                showResults: false,
                orderFields: [
                    { key: 'unique_id', label: 'Order ID', sortable: true, sortDirection: 'desc' },
                    { key: 'date_created', label: 'Order Date', sortDirection: 'desc'},
                    { key: 'actions', label: '' }
                ],
              sortBy: '',
              sortDesc: false,
              sortDirection: 'asc',
            }
        },
        methods: {
          goBack() {
            window.history.back();
          },
            timeFormat(time) {
                return moment(time).fromNow();
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
</style>
