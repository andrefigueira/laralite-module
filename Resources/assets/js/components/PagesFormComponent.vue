<template>
    <div>
        <div class="row">
            <div class="col-md-12">
              <div class="admin-title-section">
                <h2 class="admin-title">
                    {{ type === 'create' ? 'Create new page' : 'Edit page ' }}
                    <strong v-show="type === 'edit'">{{ page.name }}</strong>
                </h2>
              </div>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="page-section p-4 mb-4">
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
                </div><!-- End row -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="page-section p-4">
                            <b-card no-body>
                                <b-tabs pills card end>
                                    <b-tab title="Components" active>
                                        <b-card-text>
                                            <page-components v-model="components" :template="template"></page-components>
                                        </b-card-text>
                                    </b-tab>
                                    <b-tab title="Meta">
                                        <b-card-text>
                                            <b-form-group id="page-title-group" label="Title" label-for="page-title">
                                                <b-form-input
                                                    id="page-title-input"
                                                    required
                                                    v-model="meta.title"
                                                    placeholder="e.g. Homepage"
                                                ></b-form-input>
                                            </b-form-group>

                                            <b-form-group id="page-keywords-group" label="Keywords" label-for="page-keywords">
                                                <b-form-input
                                                    id="page-keywords-input"
                                                    required
                                                    v-model="meta.keywords"
                                                    placeholder="e.g. trap,music,museum"
                                                ></b-form-input>
                                            </b-form-group>

                                            <b-form-group id="page-author-group" label="Author" label-for="page-author">
                                                <b-form-input
                                                    id="page-author-input"
                                                    required
                                                    v-model="meta.author"
                                                    placeholder="e.g. Andre Figueira"
                                                ></b-form-input>
                                            </b-form-group>

                                            <b-form-group id="page-description-group" label="Description" label-for="page-description">
                                                <b-textarea
                                                    id="page-description-input"
                                                    required
                                                    v-model="meta.description"
                                                ></b-textarea>
                                            </b-form-group>
                                        </b-card-text>
                                    </b-tab>
                                </b-tabs>
                            </b-card>
                        </div><!-- End page section -->
                    </div><!-- End col -->
                </div><!-- End row -->
            </div><!-- End col -->
            <div class="col-md-3">
                <div class="page-section p-4">
                    <label for="primary-option">Page type</label>
                    <v-select class="mb-3" id="primary-option" label="title" v-model="primary" :options="primaryOptions" :clearable="false"></v-select>

                    <label for="authentication-option">Authentication required</label>
                    <v-select class="mb-3" id="authentication-option" label="title" v-model="authentication" :options="authenticationOptions" :clearable="false"></v-select>

                    <label for="parent">Parent</label>
                    <v-select class="mb-3" id="parent" label="name" v-model="parent" :options="pages" :clearable="false"></v-select>

                    <label for="template">Template</label>
                    <v-select class="mb-3" id="template" label="name" v-model="template" :options="templates" :clearable="false"></v-select>

                    <b-button variant="success" :disabled="saving" @click="save()">{{ button }}</b-button>
                </div><!-- End content sidebar -->
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../admin'
    import helpers from '../helpers'

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
                        value: 1
                    },
                    {
                        title: 'Standard',
                        value: 0
                    }
                ],
                authentication: {
                    title: 'No',
                    value: 0
                },
                authenticationOptions: [
                    {
                        title: 'Yes',
                        value: 1
                    },
                    {
                        title: 'No',
                        value: 0
                    }
                ],
                primary: {
                    title: 'Standard',
                    value: 0
                },
                pages: [],
                template: {},
                templates: [],
                id: '',
                parent: {},
                name: '',
                slug: '',
                components: {},
                meta: {
                    title: '',
                    keywords: '',
                    author: '',
                    description: ''
                }
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
        watch: {
            slug() {
                if (this.slug !== '') {
                    let lastPart = this.slug.split("/").pop();
                    this.slug = '/' + lastPart;
                }
            }
        },
        methods: {
            loadParentOptions(defaultOption) {
                axios.get('/api/page').then(response => {
                    this.pages = response.data.data;
                    this.pages.unshift(defaultOption);

                    if (this.page.id !== undefined) {
                        this.parent = this.pages.filter((parentPage) => {
                            return parentPage.id === this.page.parent_id;
                        })[0];

                        this.pages = this.pages.filter((result) => {
                            return result.id !== this.page.id;
                        });
                    }
                }).catch(error => {
                    // handle error
                });
            },
            loadDefaultFormValues(defaultParentValue) {
                if (this.page.id === undefined) {
                    this.parent = defaultParentValue;
                } else {
                    this.id = this.page.id;
                    this.primary = this.primaryOptions.filter((option) => {
                        return option.value === this.page.primary;
                    })[0];
                    this.authentication = this.authenticationOptions.filter((option) => {
                        return option.value === this.page.authentication;
                    })[0];
                    this.name = this.page.name;
                    this.slug = this.page.slug;
                    this.components = this.page.components;
                    this.meta = this.page.meta;
                }
            },
            loadTemplateOptions() {
                axios.get('/api/template').then(response => {
                    this.templates = response.data.data;

                    if (this.page.template_id === undefined) {
                        this.template = this.templates[0];
                    } else {
                        this.template = this.templates.filter((pageTemplate) => {
                            return pageTemplate.id === this.page.template_id;
                        })[0];
                    }
                }).catch(error => {
                    // handle error
                });
            },
            load() {
                let defaultParentValue = {
                    id: null,
                    name: 'No parent'
                };

                this.loadParentOptions(defaultParentValue);
                this.loadTemplateOptions();
                this.loadDefaultFormValues(defaultParentValue);
            },
            generateSlug() {
                this.slug = '/' + this.name.toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            },
            save() {
                this.saving = true;

                axios({
                    method: this.formMethod,
                    url: this.formEndpoint,
                    data:  {
                        primary: this.primary.value,
                        authentication: this.authentication.value,
                        parent_id: this.parent.id,
                        template_id: this.template.id,
                        name: this.name,
                        slug: this.slug,
                        components: this.components,
                        meta: this.meta
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
                        this.alertMessage = helpers.createErrorsList(error.response.data.errors);

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
