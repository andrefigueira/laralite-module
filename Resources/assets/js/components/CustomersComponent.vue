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
                        class="mr-2"
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
                    :fields="filteredFields"
                    :per-page="perPage"
                    :current-page="currentPage"
                    :filter="filter"
                    sortDesc>
                    <template v-slot:cell(verified)="data">
                        <b-badge v-if="data.item.verification_guid === ''" class="badge-soft-primary"><i class="fas fa-check-circle"></i> Account Verified</b-badge>
                        <b-badge v-if="data.item.verification_guid !== ''" class="badge-soft-danger"><i class="fas fa-times-circle"></i> Not Verified</b-badge>
                    </template>
                    <template v-slot:cell(updated_at)="data">
                        <b-badge class="badge-soft-primary"><i class="fas fa-check-circle"></i> Account Active</b-badge>
                    </template>
                    <template v-slot:cell(created_at)="data">
                        {{ timeFormat(data.item.created_at) }}
                    </template>
                    <template v-slot:cell(actions)="data">
                      <a v-b-tooltip:hover title="View Customer" :href="'/admin/customers/view/' + data.item.unique_id" class="btn btn-sm btn-primary float-right mr-3" style="font-size: 12px">View</a>
                    </template>
                </b-table>
                </div>
            </div><!-- End col -->
        </div><!-- End row -->

      <div class="float-right mb-3 mt-2">
        <ul class="pagination pagination-rounded mb-0">
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
      computed: {
        filteredFields() {
          if(!this.visible) {
            return [
              { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
              { key: 'email', label: 'Email', sortable: true, sortDirection: 'desc' },
              { key: 'verified', label: 'Verified' },
              { key: 'updated_at', label: 'Status', sortable: true, sortDirection: 'desc' },
              { key: 'created_at', label: 'Registered',sortable: true, sortDirection: 'desc'},
              { key: 'actions', label: '' }
            ]
          } else {
            return [
              { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
              { key: 'email', label: 'Email', sortable: true, sortDirection: 'desc' },
              { key: 'actions', label: '' }
            ]
          }
        }
      },
      data() {
          return {
            visible: true,
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
                  { key: 'verified', label: 'Verified' },
                  { key: 'updated_at', label: 'Status', sortable: true, sortDirection: 'desc' },
                  { key: 'created_at', label: 'Registered',sortable: true, sortDirection: 'desc'},
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
        onResize() {
          this.visible = window.innerWidth <= 700;
        },
        timeFormat(time) {
            return moment(time).fromNow();
        },
        tableDataProvider(context) {
            this.isBusy = true;

            const promise = axios.get(
                '/api/customer?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
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
        }
      },
      created() {
        this.onResize();
        // window.addEventListener('resize', this.onResize)
      },

      beforeDestroy() {
        !this.onResize();
        // window.removeEventListener('resize', this.onResize)
      },
    }
</script>
