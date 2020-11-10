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
            <div class="col-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Order Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ order.unique_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><b-badge variant="success"><i class="fas fa-check-circle"></i> Accepted</b-badge></td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Order Basket</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ order.unique_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><b-badge variant="success"><i class="fas fa-check-circle"></i> Accepted</b-badge></td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-12 mt-2">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-user"></i> Payment Details</h5>

                        Payment details to appear here
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
                showResults: false
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
