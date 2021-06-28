<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No permissions added yet &middot; <a href="/admin/permissions/create">Create one</a></b-alert>

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
            <template v-slot:cell(id)="data">
              {{ data.item.id }}
            </template>
            <template v-slot:cell(name)="data">
              {{ data.item.name }}
            </template>
            <template v-slot:cell(guard_name)="data">
              {{ data.item.guard_name }}
            </template>
            <template v-slot:cell(date_created)="data">
              {{ timeFormat(data.item.created_at) }}
            </template>
            <template v-slot:cell(date_updated)="data">
              {{ timeFormat(data.item.updated_at) }}
            </template>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item.id)" class="float-right" style="text-decoration: none !important;"><i class="ri-delete-bin-6-fill"></i></a>
              <a v-b-tooltip:hover title="Edit" :href="'/admin/permissions/edit/' + data.item.id" class="float-right mr-3" style="text-decoration: none !important;"><i class="ri-pencil-fill"></i></a>
            </template>
          </b-table>

<!--          <table class="table" v-show="showResults">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
            </tr>
            <tr v-for="permission in permissions">
                <td>{{ permission.id }}</td>
                <td>{{ permission.name }}</td>
                <td>{{ permission.guard_name }}</td>
                <td>{{ timeFormat(permission.created_at) }}</td>
                <td>{{ timeFormat(permission.updated_at) }}</td>
                <td>
                    <a v-b-tooltip:hover title="Delete" @click="confirmDelete(permission)" size="" class="float-right"><i class="ri-delete-bin-6-fill"></i></a>
                    <a v-b-tooltip:hover title="Edit" :href="'/admin/permissions/edit/' + permission.id" class="float-right mr-3"><i class="ri-pencil-fill"></i></a>
                </td>
            </tr>
        </table>-->
        </div>
    </div>
</template>

<script>
    import * as moment from "moment";

    export default {
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        data() {
            return {
                loading: true,
                showResults: false,
                permissions: [],
                fields: [
                  { key: 'id', label: 'Name', sortable: true, sortDirection: 'desc' },
                  { key: 'name', label: 'Discount Type', sortable: true, sortDirection: 'desc' },
                  { key: 'guard_name', label: 'Discount Value', sortable: true, sortDirection: 'desc' },
                  { key: 'date_created', label: 'Created At', sortable: true, sortDirection: 'desc'},
                  { key: 'date_updated', label: 'Updated At', sortable: true, sortDirection: 'desc'},
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
                '/api/permissions?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc
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
          timeFormat(time) {
            return moment(time).format("Do MMM, YYYY");
          },
            load() {
                axios.get('/api/permissions').then(response => {
                    this.permissions = response.data.data;

                    if (this.permissions.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(role) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.permissions.indexOf(role);
                        let self = this;

                        axios.delete('/api/permissions/' + permission.id).then(response => {
                            self.permissions.splice(index, 1);

                            if (self.permissions.length < 1) {
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
