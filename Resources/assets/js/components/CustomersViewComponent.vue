<template>
    <div class="customer-details">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Customer &rarr; <strong>{{ customer.name }}</strong></h1>
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
                        <h5 class="heading-style"><i class="fas fa-user"></i> Customer Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>ID</strong></td>
                                <td>{{ customer.unique_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>External ID</strong></td>
                                <td>
                                    {{ customer.external_id }}
                                    <b-badge v-if="customer.external_id === null" variant="danger"><i class="fas fa-times-circle"></i> No Association</b-badge>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><b-badge variant="success"><i class="fas fa-check-circle"></i> Account Active</b-badge></td>
                            </tr>
                            <tr>
                                <td><strong>Verified</strong></td>
                                <td>
                                    <b-badge v-if="customer.verification_guid === ''" variant="success"><i class="fas fa-check-circle"></i> Account Verified</b-badge>
                                    <b-badge v-if="customer.verification_guid !== ''" variant="danger"><i class="fas fa-times-circle"></i> Not Verified</b-badge>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Logins</strong></td>
                                <td>48</td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-address-card"></i> Personal Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>NI Number</strong></td>
                                <td>{{ customer.personal_details.ni_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date of Birth</strong></td>
                                <td>{{ customer.personal_details.date_of_birth }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nationality</strong></td>
                                <td>{{ customer.personal_details.nationality }}</td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
        </div><!-- End row -->
        <div class="row mt-3">
            <div class="col-6">
                <b-card class="mb-2">
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-map-marker-alt"></i> Customer Address</h5>

                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>Number or Name</strong></td>
                                <td>{{ customer.address.house_number_or_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Street</strong></td>
                                <td>{{ customer.address.street }}</td>
                            </tr>
                            <tr>
                                <td><strong>City</strong></td>
                                <td>{{ customer.address.city }}</td>
                            </tr>
                            <tr>
                                <td><strong>Postcode</strong></td>
                                <td>{{ customer.address.postcode }}</td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
            <div class="col-6">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-envelope-open-text"></i> Contact Details</h5>
                        <table class="table table-striped">
                            <tr>
                                <td width="40%"><strong>Mobile</strong></td>
                                <td>{{ customer.contact_details.mobile_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ customer.email }}</td>
                            </tr>
                        </table>
                    </b-card-text>
                </b-card>
            </div><!-- End col -->
        </div><!-- End row -->
        <div class="row">
            <div class="col-12">
                <b-card>
                    <b-card-text>
                        <h5 class="heading-style"><i class="fas fa-pound-sign"></i> Accounts</h5>
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Beneficiary</th>
                                <th>Open Date</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>44a5c03d-b143-43e0-840b-6dbb32a99a68</td>
                                <td>Jane Doe</td>
                                <td>2 Months Ago</td>
                                <td><a href="" class="btn btn-success btn-sm float-right">Manage</a></td>
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
            customer: {
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
