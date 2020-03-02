<template>
    <div>
        <b-form-group id="page-name-group" label="Page name" label-for="page-name">
            <b-form-input
                id="page-name-input"
                required
                v-model="name"
                placeholder="Enter page name"
            ></b-form-input>
        </b-form-group>

        <b-form-group id="page-slug-group" label="Page slug" label-for="page-slug">
            <b-form-input
                id="page-slug-input"
                required
                v-model="slug"
                placeholder="e.g. /home"
            ></b-form-input>
        </b-form-group>

        <b-button variant="success" @click="create()">Create</b-button>
    </div>
</template>

<script>
    import { bus } from '../app'

    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                saving: false,
                name: ''
            }
        },
        computed: {
            slug() {
                return this.name.toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            }
        },
        methods: {
            create() {
                axios.post('/api/page', {
                    name: this.name,
                    slug: this.slug
                }).then(response => {
                    this.saving = false;
                    this.name = '';

                    bus.$emit('page-created', response.data.data);

                    window.location.replace('/admin/pages');
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Page already exists in system');

                        this.alertShow = true;
                        this.alertType = 'alert-danger';
                        this.alertMessage = 'Page already exists!';

                        return;
                    }

                    console.log(error);

                    this.alertShow = true;
                    this.alertType = 'alert-danger';
                    this.alertMessage = 'Failed to create page try again later';
                });
            }
        }
    }
</script>
