<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <alert-component :alert="alert"></alert-component>
            </div><!-- End col -->
            <div class="col-md-12">
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
                    <template v-slot:cell(verified)="data">
                        <b-badge v-if="data.item.verification_guid === ''" variant="success"><i class="fas fa-check-circle"></i> Account Verified</b-badge>
                        <b-badge v-if="data.item.verification_guid !== ''" variant="danger"><i class="fas fa-times-circle"></i> Not Verified</b-badge>
                    </template>
                    <template v-slot:cell(status)="data">
                        <b-badge variant="success"><i class="fas fa-check-circle"></i> Account Active</b-badge>
                    </template>
                    <template v-slot:cell(registered)="data">
                        {{ timeFormat(data.item.created_at) }}
                    </template>
                    <template v-slot:cell(actions)="data">
                        <a :href="'/admin/customers/view/' + data.item.unique_id" class="btn btn-sm btn-success float-right mr-1">View</a>
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

    export default {
        data() {
            return {
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
                    { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                    { key: 'email', label: 'Email', sortable: true, sortDirection: 'desc' },
                    { key: 'verified', label: 'Verified'},
                    { key: 'status', label: 'Status'},
                    { key: 'registered', label: 'Registered', sortDirection: 'desc'},
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
            timeFormat(time) {
                return moment(time).fromNow();
            },

            tableDataProvider(context) {
                this.isBusy = true;

                const promise = axios.get(
                    '/api/customer?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc
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
            }
        }
    }
</script>
