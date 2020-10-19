<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No navigation added yet &middot; <a href="/admin/navigation/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th></th>
            </tr>
            <tr v-for="navigationRow in navigation">
                <td>{{ navigationRow.name }}</td>
                <td>{{ navigationRow.description }}</td>
                <td>
                    <b-button @click="confirmDelete(navigationRow)" variant="danger" size="sm" class="float-right">Delete</b-button>
                    <a :href="'/admin/navigation/edit/' + navigationRow.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
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
                navigation: []
            }
        },
        methods: {
            load() {
                axios.get('/api/navigation').then(response => {
                    this.navigation = response.data.data;

                    if (this.navigation.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(navigationRow) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.navigation.indexOf(navigationRow);
                        let self = this;

                        axios.delete('/api/navigation/' + navigationRow.id).then(response => {
                            self.navigation.splice(index, 1);

                            if (self.navigation.length < 1) {
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
