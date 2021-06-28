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
                        style="padding: 18px 10px"
                    ></b-form-input>
                    <b-input-group-append>
                        <a :disabled="!filter" @click="filter = ''" class="btn btn-secondary">Clear</a>
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
                    :filter="filter">
                    <template v-slot:cell(date_created)="data">
                        {{ timeFormat(data.item.created_at) }}
                    </template>
                    <template v-slot:cell(actions)="data">
                        <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item)" class="float-right" style="text-decoration: none !important;"><i class="ri-delete-bin-6-fill"></i></a>
                        <a v-b-tooltip:hover title="Edit" :href="'/admin/discounts/edit/' + data.item.id" class="float-right mr-3" style="text-decoration: none !important;"><i class="ri-pencil-fill"></i></a>
                    </template>
                </b-table>
                </div>
            </div><!-- End col -->
        </div><!-- End row -->
        <div class="float-right mb-3">
          <ul class="pagination pagination-rounded mt-2">
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
                    { key: 'type', label: 'Discount Type', sortable: true, sortDirection: 'desc' },
                    { key: 'value', label: 'Discount Value', sortable: true, sortDirection: 'desc' },
                    { key: 'date_created', label: 'Created', sortable: true, sortDirection: 'desc'},
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
                    '/api/discount?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc
                );

                return promise.then((data) => {
                    const items = data.data.data;

                    this.totalRows = data.data.total;

                    this.isBusy = false;

                    console.log(items);
                    return items;
                }).catch(error => {
                    this.isBusy = false;

                    return [];
                })
            },
            confirmDelete(discount) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let self = this;

                        axios.delete('/api/discount/' + discount.id).then(response => {
                            self.$refs.table.refresh();
                        }).catch(error => {
                            // handle error
                        });
                    }
                }).catch(error => {
                    // An error occurred
                });
            }
        }
    }
</script>
