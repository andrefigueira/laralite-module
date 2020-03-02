<template>
    <div>
        <b-alert :show="!loading && !showResults">No pages added yet &middot; <a href="/admin/pages/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th></th>
            </tr>
            <tr v-for="page in pages" v-bind:key="page.id">
                <td><a :href="'/admin/pages/edit/' + page.id">{{ page.name }}</a></td>
                <td>/{{ page.slug }}</td>
                <td>
                    <b-button @click="confirmDelete(page)" variant="danger" size="sm" class="float-right">Delete</b-button>
                    <a :href="'/admin/pages/edit/' + page.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
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
                pages: []
            }
        },
        methods: {
            load() {
                axios.get('/api/page').then(response => {
                    this.pages = response.data.data;

                    if (this.pages.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(page) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.pages.indexOf(page);
                        let self = this;

                        axios.delete('/api/page/' + page.id).then(response => {
                            self.pages.splice(index, 1);
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
