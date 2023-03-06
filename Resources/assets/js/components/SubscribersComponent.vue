<template>
  <div>
    <b-alert class="m-3" :show="!loading && !showResults">No subscriptions added yet &middot; <a href="/admin/subscriptions/create">Create one</a></b-alert>

    <div v-show="loading" class="text-center">
      <b-spinner label="Spinning"></b-spinner>
    </div>
    <b-input-group v-if="false" size="sm" class="m-2 mr-0 pr-3">
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
        <template v-slot:cell(customer.name)="data">
          {{ data.item.customer.name }}
        </template>
        <template v-slot:cell(subscription.name)="data">
          {{ data.item.subscription.name }}
        </template>
        <template v-slot:cell(status)="data">
          <b-badge v-if="data.item.status === 'ACTIVE'" variant="primary">{{ data.item.status }}</b-badge>
          <b-badge v-if="data.item.status === 'CANCELED'" variant="danger">{{ data.item.status }}</b-badge>
        </template>
        <template v-slot:cell(created_at)="data">
          {{ timeFormat(data.item.created_at) }}
        </template>
        <template v-slot:cell(updated_at)="data">
          {{ timeFormat(data.item.updated_at) }}
        </template>
        <template v-slot:cell(actions)="data">
          <a v-b-tooltip:hover title="View" :href="'/admin/subscribers/' + data.item.id" class="btn btn-sm btn-primary float-right mr-3" style="font-size: 12px">View</a>
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
  props: {
    subscriptions: {
      required: true,
      default: [],
    }
  },
  computed: {
    filteredFields() {
      if(!this.visible) {
        return [
          { key: 'id', label: 'Id', sortable: true, sortDirection: 'desc' },
          { key: 'customer.name', label: 'Customer Name', sortable: true, sortDirection: 'desc' },
          { key: 'subscription.name', label: 'Subscription Plan', sortable: true, sortDirection: 'desc' },
          { key: 'status', label: 'Status', sortable: true, sortDirection: 'desc' },
          { key: 'created_at', label: 'Created At', sortable: true, sortDirection: 'desc'},
          { key: 'updated_at', label: 'Updated At', sortable: true, sortDirection: 'desc'},
          { key: 'actions', label: '' }
        ]
      } else {
        return [
          { key: 'id', label: 'Id', sortable: true, sortDirection: 'desc' },
          { key: 'customer.name', label: 'Customer Name', sortable: true, sortDirection: 'desc' },
          { key: 'subscription.name', label: 'Subscription Plan', sortable: true, sortDirection: 'desc' },
          { key: 'status', label: 'Status', sortable: true, sortDirection: 'desc' },
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
      subscriptions: [],
      fields: [
        { key: 'id', label: 'Id', sortable: true, sortDirection: 'desc' },
        { key: 'customer.name', label: 'Customer Name', sortable: true, sortDirection: 'desc' },
        { key: 'subscription.name', label: 'Subscription Plan', sortable: true, sortDirection: 'desc' },
        { key: 'status', label: 'Status', sortable: true, sortDirection: 'desc' },
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
    tableDataProvider(context) {
      this.isBusy = true;

      const promise = axios.get(
          '/api/subscribers?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
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
      axios.get('/api/subscriptions').then(response => {
        this.subscriptions = response.data.data;

        if (this.subscriptions.length > 0) {
          this.showResults = true;
        }

        this.loading = false;
      }).catch(error => {
        // handle error
      });
    },
    confirmDelete(subscription) {
      this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
        if (value) {
          let index = this.subscriptions.indexOf(subscription);
          let self = this;

          axios.delete('/api/subscriptions/' + subscription.id).then(response => {
            self.subscriptions.splice(index, 1);

            if (self.subscriptions.length < 1) {
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
