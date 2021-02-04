<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div v-if="uploadSuccess === false" class="page-section p-4">
                    <p>Upload an exported .csv file from SquareSpace here.</p>
                    <squarespace-import-component @csv-uploaded="csvUploaded" @set-total-rows="setTotalRows"></squarespace-import-component>
                </div>
            </div><!-- End col -->
            <div class="col-12 mt-2" v-if="uploadSuccess === true && importSuccess === false">
                <div class="page-section p-4">
                    <p>Successfully imported into a temporary table, please press the import button below to begin the process.</p>
                    <button :disabled="busy" class="btn btn-warning" v-on:click="handleFileImport()">
                        <b-spinner v-if="busy" small type="grow" style="margin-bottom: 3px;"></b-spinner>
                        <span v-if="busy === false">Import {{ totalRows }} Rows From Temporary Table</span>
                        <span v-if="busy">Please wait, this may take some time...</span>
                    </button>
                </div>
            </div>
            <div class="col-12 mt-2" v-if="importSuccess === true">
                <div class="page-section p-4">
                    <h3>{{ importMessage }}</h3>
                    <p>There were {{ updatedOrders }} created orders.</p>
                    <p>There were {{ updatedCustomers }} created customers.</p>
                </div>
            </div>
        </div><!-- End row -->
    </div>
</template>

<script>
    export default {
        props: {
            
        },
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                maintenance: false,
                currency: '$ US Dollar',
                currencyOptions: [
                    {
                        title: '$ US Dollar',
                        value: 'USD'
                    },
                    {
                        title: '£ UK Sterling',
                        value: 'GBP'
                    }
                ],
                busy: false,
                importSuccess: false,
                imporMessage: '',
                updatedOrders: 0,
                updatedCustomers: 0,
                uploadSuccess: false,
                totalRows: 0,
            }
        },
        methods: {
          csvUploaded: function (value) {
              this.uploadSuccess = value;
          },
          setTotalRows: function (value) {
              this.totalRows = value;
          },
          handleFileImport: function () {
            const vm = this;
            if (confirm("You are about to import data into the Customers and Orders table, do you wish to proceed?")) {
                this.busy = true;
                axios.get('/api/squarespace/import').then(function (result) {
                    vm.busy = false;
                    vm.importSuccess = result.data.success;
                    vm.importMessage = result.data.message;
                    vm.updatedOrders = result.data.data.orders_created;
                    vm.updatedCustomers = result.data.data.customers_created;
                    vm.skipped = result.data.data.skipped_rows;
                }).catch(function (error) {
                    console.log('Import Failed', {
                        error: error
                    });

                    self.uploadError = error.response.data.message;
                    vm.busy = false;
                });
              }
          }
        }
    }
</script>
