<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No products added yet &middot; <a href="/admin/product/create">Create product</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th></th>
            </tr>
            <tr v-for="product in products">
                <td>{{ product.name }}</td>
                <td>{{ product.price }}</td>
                <td>
                    <b-button @click="confirmDelete(product)" variant="danger" size="sm" class="float-right">Delete</b-button>
                    <a :href="'/admin/product/edit/' + product.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
                </td>
            </tr>
        </table>
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
                products: []
            }
        },
        methods: {
            load() {
                axios.get('/api/product').then(response => {
                    this.products = response.data.data;

                    if (this.products.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
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
        }
    }
</script>
