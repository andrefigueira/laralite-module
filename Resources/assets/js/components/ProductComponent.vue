  <template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No products added yet &middot; <a href="/admin/product/create">Create product</a></b-alert>

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
              :filter="filter"
              sortDesc>
            <template v-slot:cell(image)="data">
              <a href="#" :style="{'background-image': 'url(' + data.item.variants[0].image + ')'}" class="variant-image-placeholder dark-link"></a>
            </template>
            <template v-slot:cell(name)="data">
              <span>{{ data.item.name }}</span>
              <b-badge class="badge-soft-danger ml-2" v-if="!data.item.active"><i class="fas fa-check-circle"></i>InActive</b-badge>
            </template>
            <template v-slot:cell(slug)="data">
              <span>{{ data.item.slug }}</span>
            </template>
            <template v-slot:cell(category_id)="data">
              <span>{{ data.item.category.name }}</span>
            </template>
            <template v-slot:cell(category_price)="data">
              <span>{{ data.item.variants[0].pricing.price }}</span>
            </template>
            <template v-slot:cell(actions)="data">
              <a v-b-tooltip:hover title="Delete" class="float-right mr-2" style="width: 10%; cursor: pointer" @click="doDelete(data.item)"><i class="ri-delete-bin-6-fill"></i></a>
              <confirm-dialogue-component ref="confirmDialogue"></confirm-dialogue-component>
<!--          <a v-b-tooltip:hover title="Delete" :data-id="data.item.id" data-toggle="modal"  data-target="#confirmDelete" class="float-right mr-2" style="width: 10%"><i class="ri-delete-bin-6-fill"></i></a>-->
              <a v-b-tooltip:hover title="Edit" :href="'/admin/product/edit/' + data.item.id" class="float-right mr-4" style="width: 10%"><i class="ri-pencil-fill"></i></a>
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
              { key: 'image', label: '' },
              { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
              { key: 'slug', label: 'URL', sortable: true, sortDirection: 'desc' },
              { key: 'category_id', label: 'Category', sortable: true, sortDirection: 'desc' },
              { key: 'category_price', label: 'Price', sortDirection: 'desc' },
              { key: 'actions', label: '' }
            ]
          } else {
            return [
              { key: 'image', label: '' },
              { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
              {key: 'actions', label: ''}
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
                products: [],
              fields: [
                { key: 'image', label: '' },
                { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                { key: 'slug', label: 'URL', sortable: true, sortDirection: 'desc' },
                { key: 'category_id', label: 'Category', sortable: true, sortDirection: 'desc' },
                { key: 'category_price', label: 'Price', sortDirection: 'desc' },
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
          async doDelete(product) {
            const ok = await this.$refs.confirmDialogue.show({
              title: 'Delete Product: ' + product.name,
              message: 'Are you sure you want to delete this product? It cannot be undone.',
              okButton: 'Delete',
            })
            // If you throw an error, the method will terminate here unless you surround it wil try/catch
            if (ok) {
              console.log(product);
              axios.delete('/api/product/' + product.id).then(response => {
                this.product = response.data
                if (this.product.length > 0) {
                  this.showResults = true;
                }
                location.reload();
              }).catch(error => {
                alert("Error in deleting Product" + product.name)
              });
            } else {
              /*alert('You chose not to delete this page. Doing nothing now.')*/
              console.log(product)
            }
          },
          tableDataProvider(context) {
            this.isBusy = true;

            const promise = axios.get(
                '/api/product?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
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
                axios.get('/api/product', { withCredentials: true }).then(response => {
                    this.products = response.data.data;

                    if (this.products.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
          hideDelete() {
            this.$refs['confirmDelete'].hide();
          },
          confirmDelete(product) {
            this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
              if (value) {
                let index = this.products.indexOf(product);
                let self = this;

                axios.delete('/api/product/' + product.id).then(response => {
                  self.products.splice(index, 1);

                  if (self.products.length < 1) {
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

<style lang="scss">
    .variant-image-placeholder {
        display: block;
        padding: 0.9rem 0;
        border: 1px solid #D9D9D9;
        width: 50px;
        height: 50px;
        text-align: center;
        vertical-align: middle;
        border-radius: 1px;
        background-size: cover;
        background-position: center;
    }
</style>
