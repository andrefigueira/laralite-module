<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No users added yet &middot; <a href="/admin/users/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th></th>
            </tr>
            <tr v-for="user in users">
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                    <b-button @click="confirmDelete(user)" variant="default" class="float-right"><i class="far fa-trash-alt"></i></b-button>
                    <a :href="'/admin/users/edit/' + user.id" class="btn btn-default float-right mr-1"><i class="far fa-edit"></i></a>
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
                users: []
            }
        },
        methods: {
            load() {
                axios.get('/api/user').then(response => {
                    this.users = response.data.data;

                    if (this.users.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(user) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.users.indexOf(user);
                        let self = this;

                        axios.delete('/api/user/' + user.id).then(response => {
                            self.users.splice(index, 1);

                            if (self.users.length < 1) {
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
