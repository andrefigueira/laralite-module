<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No permissions added yet &middot; <a href="/admin/permissions/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th></th>
            </tr>
            <tr v-for="permission in permissions">
                <td>{{ permission.id }}</td>
                <td>{{ permission.name }}</td>
                <td>{{ permission.guard_name }}</td>
                <td>{{ permission.created_at }}</td>
                <td>{{ permission.updated_at }}</td>
                <td>
                    <b-button @click="confirmDelete(permission)" variant="danger" size="sm" class="float-right">Delete</b-button>
                    <a :href="'/admin/permissions/edit/' + permission.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
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
                permissions: [],
            }
        },
        methods: {
            load() {
                axios.get('/api/permissions').then(response => {
                    this.permissions = response.data.data;

                    if (this.permissions.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(role) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.permissions.indexOf(role);
                        let self = this;

                        axios.delete('/api/permissions/' + permission.id).then(response => {
                            self.permissions.splice(index, 1);

                            if (self.permissions.length < 1) {
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
