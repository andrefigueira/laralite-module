<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No roles added yet &middot; <a href="/admin/roles/create">Create one</a></b-alert>

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
              :fields="filteredFields"
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
            <template v-slot:cell(created_at)="data">
              {{ timeFormat(data.item.created_at) }}
            </template>
            <template v-slot:cell(updated_at)="data">
              {{ timeFormat(data.item.updated_at) }}
            </template>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="Delete" class="float-right mr-2" style="width: 10%; cursor: pointer" @click="doDelete(data.item)"><i class="ri-delete-bin-6-fill"></i></a>
              <confirm-dialogue-component ref="confirmDialogue"></confirm-dialogue-component>
<!--              <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item.id)" class="float-right mr-2" style="width: 10%; text-decoration: none !important;"><i class="ri-delete-bin-6-fill"></i></a>-->
              <a v-b-tooltip:hover title="Edit" :href="'/admin/roles/edit/' + data.item.id" class="float-right mr-4" style="width: 10%; text-decoration: none !important;"><i class="ri-pencil-fill"></i></a>
            </template>
          </b-table>
        </div>
      <div class="float-right m-2">
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
import ConfirmDialogueComponent from "./ConfirmDialogueComponent";
export default {
  components: {ConfirmDialogueComponent},
  computed: {
    filteredFields() {
      if(!this.visible) {
        return [
          { key: 'id', label: 'Id', sortable: true, sortDirection: 'desc' },
          { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
          { key: 'guard_name', label: 'Guard Name', sortable: true, sortDirection: 'desc' },
          { key: 'created_at', label: 'Created At', sortable: true, sortDirection: 'desc'},
          { key: 'updated_at', label: 'Updated At', sortable: true, sortDirection: 'desc'},
          { key: 'actions', label: '' }
        ]
      } else {
        return [
          { key: 'id', label: 'Id', sortable: true, sortDirection: 'desc' },
          { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
          { key: 'actions', label: '' }
        ]
      }
    }
  },
  mounted() {
            console.log('Component mounted.');
            this.load();
        },
  data() {
      return {
        visible: true,
        loading: true,
        showResults: false,
        roles: [],
        fields: [
          { key: 'id', label: 'Id', sortable: true, sortDirection: 'desc' },
          { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
          { key: 'guard_name', label: 'Guard Name', sortable: true, sortDirection: 'desc' },
          { key: 'created_at', label: 'Created At', sortable: true, sortDirection: 'desc'},
          { key: 'updated_at', label: 'Updated At', sortable: true, sortDirection: 'desc'},
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
    async doDelete(role) {
      const ok = await this.$refs.confirmDialogue.show({
        title: 'Delete Role: ' + role.name,
        message: 'Are you sure you want to delete this role? It cannot be undone.',
        okButton: 'Delete',
      })
      // If you throw an error, the method will terminate here unless you surround it wil try/catch
      if (ok) {
        console.log(role);
        axios.delete('/api/roles/' + role.id).then(response => {
          this.role = response.data
          if (this.role.length > 0) {
            this.showResults = true;
          }
          location.reload();
        }).catch(error => {
          alert("Error in deleting Role: " + role.name)
        });
      } else {
        /*alert('You chose not to delete this page. Doing nothing now.')*/
        console.log(role)
      }
    },
    tableDataProvider(context) {
      this.isBusy = true;

      const promise = axios.get(
          '/api/roles?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
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
    timeFormat(time) {
      return moment(time).format("Do MMM, YYYY");
    },
      load() {
          axios.get('/api/roles').then(response => {
              this.roles = response.data.data;

              if (this.roles.length > 0) {
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
                  let index = this.roles.indexOf(role);
                  let self = this;

                  axios.delete('/api/roles/' + role.id).then(response => {
                      self.roles.splice(index, 1);

                      if (self.roles.length < 1) {
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
