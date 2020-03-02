<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="admin-title">
                    {{ type === 'create' ? 'Create new page' : 'Edit page ' }}
                    <strong v-show="type === 'edit'">{{ page.name }}</strong>
                </h2>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <b-form-group id="page-name-group" label="Page name" label-for="page-name">
                            <b-form-input
                                id="page-name-input"
                                required
                                v-model="name"
                                placeholder="Enter page name"
                                @keyup="generateSlug()"
                            ></b-form-input>
                        </b-form-group>
                    </div><!-- End col -->
                    <div class="col-md-6">
                        <b-form-group id="page-slug-group" label="Page slug" label-for="page-slug">
                            <b-form-input
                                id="page-slug-input"
                                required
                                v-model="slug"
                                placeholder="e.g. /home"
                            ></b-form-input>
                        </b-form-group>
                    </div><!-- End col -->
                </div><!-- End row -->

                <div class="row">
                    <div class="col-md-12">
                        <b-card no-body>
                            <b-tabs pills card end>
                                <b-tab title="Components" active>
                                    <b-card-text>
                                        <page-components v-model="components"></page-components>
                                    </b-card-text>
                                </b-tab>
                                <b-tab title="Meta">
                                    <b-card-text>
                                        Tab contents 2
                                    </b-card-text>
                                </b-tab>
                            </b-tabs>
                        </b-card>
                    </div><!-- End col -->
                </div><!-- End row -->
            </div><!-- End col -->
            <div class="col-md-3">
                <div class="content-sidebar">
                    <label for="primary-option">Page type</label>
                    <v-select class="mb-3" id="primary-option" label="title" v-model="primary" :options="primaryOptions" :clearable="false"></v-select>

                    <label for="parent">Parent</label>
                    <v-select class="mb-3" id="parent" label="name" v-model="parent" :options="pages" :clearable="false"></v-select>

                    <label for="template">Template</label>
                    <v-select class="mb-3" id="template" label="title" v-model="template" :options="templates" :clearable="false"></v-select>

                    <b-button variant="success" :disabled="saving" @click="save()">{{ button }}</b-button>
                </div><!-- End content sidebar -->
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../app'

    export default {
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        props: {
            type: {
                type: String,
                default: 'create'
            },
            page: {
                type: Object,
                default: {}
            }
        },
        data() {
            return {
                saving: false,
                alertShow: false,
                alertType: 'primary',
                alertMessage: '',
                primaryOptions: [
                    {
                        title: 'Primary',
                        value: true
                    },
                    {
                        title: 'Standard',
                        value: false
                    }
                ],
                pages: [],
                template: {},
                templates: [],
                id: '',
                primary: {
                    title: 'Standard',
                    value: false
                },
                parent: {},
                name: '',
                slug: '',
                components: {}
            }
        },
        computed: {
            button() {
                if (this.type === 'create') {
                    return 'Create';
                }

                return 'Save changes'
            },
            formEndpoint() {
                let endpoint = '/api/page';

                if (this.type === 'edit') {
                    endpoint = '/api/page/' + this.page.id;
                }

                return endpoint;
            },
            formMethod() {
                let method = 'post';

                if (this.type === 'edit') {
                    method = 'patch';
                }

                return method;
            }
        },
        methods: {
            load() {
                axios.get('/api/page').then(response => {
                    this.pages = response.data.data;
                }).catch(error => {
                    // handle error
                });

                if (this.page) {
                    this.id = this.page.id;
                    this.name = this.page.name;
                    this.slug = this.page.slug;
                    this.components = this.page.components;
                }
            },
            generateSlug() {
                this.slug = this.name.toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            },
            save() {
                this.saving = true;

                axios({
                    method: this.formMethod,
                    url: this.formEndpoint,
                    data:  {
                        name: this.name,
                        slug: this.slug,
                        components: this.components
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('page-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/pages');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to page';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Page already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Page already exists!';

                        return;
                    }

                    if (error.response.status === 422) {
                        console.log('Validation failed');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        let errors = '<ul class="errors">';
                        for (let [key, value] of Object.entries(error.response.data.errors)) {
                            errors += '<li>' + value + '</li>';
                        }
                        errors += '</ul>';
                        this.alertMessage = errors;

                        return;
                    }

                    console.log(error);

                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Failed to create page try again later';
                });
            }
        }
    }
</script>
