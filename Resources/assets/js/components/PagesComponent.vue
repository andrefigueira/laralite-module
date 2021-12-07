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
              <a class="text-dark" :href="'/admin/pages/edit/' + data.item.id">{{ data.item.name }}</a>
            </template>
            <template v-slot:cell(primary)="data">
              <b-badge :class="data.item.primary === 1 ? 'badge badge-soft-primary' : 'badge badge-soft-warning'">{{ data.item.primary === 1 ? 'Primary' : 'Standard' }}</b-badge>
            </template>
            <template v-slot:cell(template_id)="data">
              <b-badge class="badge badge-soft-primary">{{ data.item.template.name }}</b-badge>
            </template>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="Delete" class="float-right mr-2" style="width: 10%; cursor: pointer" @click="doDelete(data.item)"><i class="ri-delete-bin-6-fill"></i></a>
              <confirm-dialogue-component ref="confirmDialogue"></confirm-dialogue-component>
<!--              <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item.id)" class="float-right row-button mr-2" style="width: 10%"><i class="ri-delete-bin-6-fill"></i></a>-->
              <a v-b-tooltip:hover title="Edit" :href="'/admin/pages/edit/' + data.item.id" class="float-right mr-4 row-button" style="width: 10%"><i class="ri-pencil-fill"></i></a>
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
import ConfirmDialogueComponent from "./ConfirmDialogueComponent";

export default {
  components: {ConfirmDialogueComponent},
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
        { key: 'slug', label: 'URL', sortable: true, sortDirection: 'desc' },
        { key: 'primary', label: 'Type', sortable: true, sortDirection: 'desc' },
        { key: 'template_id', label: 'Template', sortable: true, sortDirection: 'desc'},
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
    async doDelete(page) {
      const ok = await this.$refs.confirmDialogue.show({
        title: 'Delete Page: ' + page.name,
        message: 'Are you sure you want to delete this page? It cannot be undone.',
        okButton: 'Delete',
      })
      // If you throw an error, the method will terminate here unless you surround it wil try/catch
      if (ok) {
        console.log(page);
        axios.delete('/api/page/' + page.id).then(response => {
          this.page = response.data
          if (this.page.length > 0) {
            this.showResults = true;
          }
          location.reload();
        }).catch(error => {
          alert("Error in deleting Page: " + page.name)
        });
      } else {
        /*alert('You chose not to delete this page. Doing nothing now.')*/
        console.log(page)
      }
    },
    timeFormat(time) {
      return moment(time).fromNow();
    },

    tableDataProvider(context) {
      this.isBusy = true;

      const promise = axios.get(
          '/api/page?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
          { withCredentials: true}
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
    confirmDelete(page) {
      if (page.primary === 1) {
        this.$bvModal.msgBoxOk('Cannot delete primary page').then(value => {
          return false;
        }).catch(error => {
          return false;
        });
        return false;
      }

      if (typeof page.children !== "undefined" &&  page.children.length > 0) {
        this.$bvModal.msgBoxOk('Unable to delete page, children exist').then(value => {
          return false;
        }).catch(error => {
          return false;
        });

        return false;
      }

      this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
        if (value) {
          let self = this;

          axios.delete('/api/page/' + page.id).then(response => {
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


<!--<template>
    <div>
        <b-alert :show="!loading && !showResults" class="m-3">No pages added yet &middot; <a href="/admin/pages/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>
        <div class="table-responsive-sm">
        <table class="table table-top-border-0" v-show="showResults">
            <thead>
            <tr>
              <th class="col-md-3 col-sm-3" style="width: 20%">Name</th>
              <th class="col-md-3 col-sm-3" style="width: 20%">URL</th>
              <th class="col-md-2 col-sm-2" style="width: 20%">Type</th>
              <th class="col-md-2 col-sm-2" style="width: 20%">Template</th>
              <th class="col-md-2 col-sm-2" style="width: 20%"></th>
            </tr>
            </thead>&lt;!&ndash; End row &ndash;&gt;
            <recursive-table-row :data="pages"></recursive-table-row>
        </table>
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
                pages: []
            }
        },
        methods: {
            load() {
                axios.get('/api/page?with=children').then(response => {
                    this.pages = response.data.data;

                    if (this.pages.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            }
        }
    }
</script>-->
