<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No users added yet &middot; <a href="/admin/users/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>
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
              :fields="fields"
              :per-page="perPage"
              :current-page="currentPage"
              :filter="filter"
              sortDesc>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item.id)" class="float-right mr-2" style="width: 5%"><i class="ri-delete-bin-6-fill"></i></a>
              <a v-b-tooltip:hover title="Edit" :href="'/admin/users/edit/' + data.item.id" class="float-right mr-4" style="width: 5%"><i class="ri-pencil-fill"></i></a>
            </template>
          </b-table>
          <div class="float-right mt-2 mb-3">
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
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        data() {
            return {
                loading: true,
                showResults: false,
                users: [],
                fields: [
                  { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                  { key: 'email', label: 'Email', sortable: true, sortDirection: 'desc' },
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
            tableDataProvider(context) {
            this.isBusy = true;

            const promise = axios.get(
              '/api/user?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc
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
            load() {
                axios.get('/api/user').then(response => {
                    this.users = response.data.data;

                    if (this.users.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(user) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.users.indexOf(user);
                        let self = this;

                        axios.delete('/api/user/' + user.id).then(response => {
                            self.users.splice(index, 1);

                            if (self.users.length < 1) {
                                self.showResults = false;
                            }
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
