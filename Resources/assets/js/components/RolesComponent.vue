<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No roles added yet &middot; <a href="/admin/roles/create">Create one</a></b-alert>

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
            <tr v-for="role in roles">
                <td>{{ role.id }}</td>
                <td>{{ role.name }}</td>
                <td>{{ role.guard_name }}</td>
                <td>{{ role.created_at }}</td>
                <td>{{ role.updated_at }}</td>
                <td>
                    <b-button @click="confirmDelete(role)" variant="danger" size="sm" class="float-right">Delete</b-button>
                    <a :href="'/admin/roles/edit/' + role.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
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
                roles: [],
            }
        },
        methods: {
            load() {
                axios.get('/api/roles').then(response => {
                    this.roles = response.data.data;

                    if (this.roles.length > 0) {
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
                        let index = this.roles.indexOf(role);
                        let self = this;

                        axios.delete('/api/roles/' + role.id).then(response => {
                            self.roles.splice(index, 1);

                            if (self.roles.length < 1) {
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
