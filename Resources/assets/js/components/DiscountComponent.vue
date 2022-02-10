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
                    :filter="filter">
                    <template v-slot:cell(created_at)="data">
                        {{ timeFormat(data.item.created_at) }}
                    </template>
                    <template v-slot:cell(actions)="data">
                      <a v-b-tooltip:hover title="Delete" class="float-right mr-2" style="width: 10%; cursor: pointer" @click="doDelete(data.item)"><i class="ri-delete-bin-6-fill"></i></a>
                      <confirm-dialogue-component ref="confirmDialogue"></confirm-dialogue-component>
<!--                        <a v-b-tooltip:hover title="Delete" @click="confirmDelete(data.item)" class="float-right mr-2" style="width: 10%; text-decoration: none !important;"><i class="ri-delete-bin-6-fill"></i></a>-->
                        <a v-b-tooltip:hover title="Edit" :href="'/admin/discounts/edit/' + data.item.id" class="float-right mr-4" style="width: 10%; text-decoration: none !important;"><i class="ri-pencil-fill"></i></a>
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
      computed: {
        filteredFields() {
          if(!this.visible) {
            return [
              { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
              { key: 'type', label: 'Discount Type', sortable: true, sortDirection: 'desc' },
              { key: 'value', label: 'Discount Value', sortable: true, sortDirection: 'desc' },
              { key: 'created_at', label: 'Created', sortable: true, sortDirection: 'desc'},
              { key: 'actions', label: '' }
            ]
          } else {
            return [
              { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
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
                { key: 'type', label: 'Discount Type', sortable: true, sortDirection: 'desc' },
                { key: 'value', label: 'Discount Value', sortable: true, sortDirection: 'desc' },
                { key: 'created_at', label: 'Created', sortable: true, sortDirection: 'desc'},
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
          async doDelete(discount) {
            const ok = await this.$refs.confirmDialogue.show({
              title: 'Delete Discount: ' + discount.name,
              message: 'Are you sure you want to delete this discount? It cannot be undone.',
              okButton: 'Delete',
            })
            // If you throw an error, the method will terminate here unless you surround it wil try/catch
            if (ok) {
              console.log(discount);
              axios.delete('/api/discount/' + discount.id).then(response => {
                this.discount = response.data
                if (this.discount.length > 0) {
                  this.showResults = true;
                }
                location.reload();
              }).catch(error => {
                alert("Error in deleting Discount: " + discount.name)
              });
            } else {
              /*alert('You chose not to delete this page. Doing nothing now.')*/
              console.log(discount)
            }
          },
            timeFormat(time) {
                return moment(time).fromNow();
            },

            tableDataProvider(context) {
                this.isBusy = true;

                const promise = axios.get(
                    '/api/discount?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
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
