<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No product category added yet &middot; <a href="/admin/product-category/create">Create product category</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th></th>
            </tr>
            <tr v-for="productCategory in productCategories">
                <td>{{ productCategory.name }}</td>
                <td>{{ productCategory.slug }}</td>
                <td>
                    <b-button @click="confirmDelete(productCategory)" variant="danger" size="sm" class="float-right">Delete</b-button>
                    <a :href="'/admin/product-category/edit/' + productCategory.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
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
                productCategories: []
            }
        },
        methods: {
            load() {
                axios.get('/api/product-category').then(response => {
                    this.productCategories = response.data.data;

                    if (this.productCategories.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(productCategory) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.productCategories.indexOf(productCategory);
                        let self = this;

                        axios.delete('/api/product-category/' + productCategory.id).then(response => {
                            self.productCategories.splice(index, 1);

                            if (self.productCategories.length < 1) {
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
