<template>
    <div>
        <b-alert class="m-3" :show="!loading && !showResults">No templates added yet &middot; <a href="/admin/templates/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <table class="table" v-show="showResults">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th></th>
            </tr>
            <tr v-for="template in templates">
                <td>{{ template.name }}</td>
                <td>{{ template.description }}</td>
                <td>
                    <b-button @click="confirmDelete(template)" variant="default" size="sm" class="float-right"><i class="far fa-trash-alt"></i></b-button>
                    <a :href="'/admin/templates/edit/' + template.id" class="btn btn-sm btn-default float-right mr-1"><i class="far fa-edit"></i></a>
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
                templates: []
            }
        },
        methods: {
            load() {
                axios.get('/api/template').then(response => {
                    this.templates = response.data.data;

                    if (this.templates.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            confirmDelete(template) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.templates.indexOf(template);
                        let self = this;

                        axios.delete('/api/template/' + template.id).then(response => {
                            self.templates.splice(index, 1);

                            if (self.templates.length < 1) {
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
