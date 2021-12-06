<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No navigation added yet &middot; <a href="/admin/navigation/create">Create one</a></b-alert>

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
            <template v-slot:cell(name)="data">
              {{ data.item.name }}
            </template>
            <template v-slot:cell(description)="data">
              {{ data.item.description }}
            </template>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item.id)" class="float-right mr-2" style="width: 5%; text-decoration: none !important;"><i class="ri-delete-bin-6-fill"></i></a>
              <a v-b-tooltip:hover title="Edit" :href="'/admin/navigation/edit/' + data.item.id" class="float-right mr-3" style="text-decoration: none !important;"><i class="ri-pencil-fill"></i></a>
            </template>
          </b-table>
        </div>
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
    export default {
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        data() {
            return {
                loading: true,
                showResults: false,
                navigation: [],
              fields: [
                { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                { key: 'description', label: 'Description', sortable: true, sortDirection: 'desc' },
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
                '/api/navigation?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
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
          },

          load() {
                axios.get('/api/navigation').then(response => {
                    this.navigation = response.data.data;

                    if (this.navigation.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(navigationRow) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.navigation.indexOf(navigationRow);
                        let self = this;

                        axios.delete('/api/navigation/' + navigationRow.id).then(response => {
                            self.navigation.splice(index, 1);

                            if (self.navigation.length < 1) {
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
