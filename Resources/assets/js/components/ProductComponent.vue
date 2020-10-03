<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No products added yet &middot; <a href="/admin/product/create">Create product</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th width="5%"></th>
                <th width="30%">Name</th>
                <th width="25%">URL</th>
                <th>Category</th>
                <th>Price</th>
                <th></th>
            </tr>
            <tr v-for="product in products">
                <td class="align-middle">
                    <a href="#" :style="{'background-image': 'url(' + product.variants[0].image + ')'}" class="variant-image-placeholder dark-link"></a>
                </td>
                <td class="align-middle"><a class="table-link" :href="'/admin/product/edit/' + product.id">{{ product.name }}</a></td>
                <td class="align-middle">/{{ product.slug }}</td>
                <td class="align-middle">{{ product.category !== null ? product.category.name : 'Uncategorised' }}</td>
                <td class="align-middle">${{ product.variants[0].pricing.price }}</td>
                <td class="align-middle">
                    <b-button @click="confirmDelete(product)" variant="default" class="float-right"><i class="far fa-trash-alt"></i></b-button>
                    <a :href="'/admin/product/edit/' + product.id" class="btn btn-default float-right mr-1"><i class="far fa-edit"></i></a>
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