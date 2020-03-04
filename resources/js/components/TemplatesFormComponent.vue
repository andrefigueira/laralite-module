<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="admin-title">
                    {{ type === 'create' ? 'Create new template' : 'Edit template ' }}
                    <strong v-show="type === 'edit'">{{ template.name }}</strong>
                </h2>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-12">
                            <b-form-group id="template-name-group" label="Template name" label-for="template-name">
                                <b-form-input
                                    id="template-name-input"
                                    required
                                    v-model="name"
                                    placeholder="Enter template name"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group id="template-description-group" label="Template description" label-for="template-description">
                                <b-form-textarea id="template-name-input" v-model="description" required></b-form-textarea>
                            </b-form-group>

                            <div class="row">
                                <div class="col-md-6">
                                    <b-form-group id="section-name-group" label="Section name" label-for="section-name">
                                        <b-form-input
                                            id="section-name-input"
                                            required
                                            v-model="sectionName"
                                            placeholder="Enter section name"
                                            @keyup="generateSlug()"
                                        ></b-form-input>
                                    </b-form-group>
                                </div><!-- End col -->
                                <div class="col-md-6">
                                    <b-form-group id="section-slug-group" label="Section slug" label-for="section-slug">
                                        <b-form-input
                                            id="section-slug-input"
                                            required
                                            v-model="sectionSlug"
                                            placeholder="Enter section slug"
                                        ></b-form-input>
                                    </b-form-group>
                                </div><!-- End col -->
                            </div><!-- End row -->

                            <b-button class="mb-2" variant="success" @click="addSection()">Add section</b-button>

                            <b-list-group>
                                <b-list-group-item v-for="section in sections" v-bind:key="section.slug">
                                    {{ section.name }} ({{ section.slug }})
                                    <b-button class="btn-sm float-right" variant="danger" @click="removeSection(section)">&times;</b-button>
                                </b-list-group-item>
                            </b-list-group>

                            <b-button class="mt-2" variant="success" :disabled="saving" @click="save()">{{ button }}</b-button>
                        </div><!-- End col -->
                    </div><!-- End row -->
                </div><!-- End row -->
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../app'
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
            template: {
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
                id: '',
                name: '',
                description: '',
                sections: [],
                sectionName: '',
                sectionSlug: ''
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
                let endpoint = '/api/template';

                if (this.type === 'edit') {
                    endpoint = '/api/template/' + this.template.id;
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
                if (this.template.id !== undefined) {
                    this.id = this.template.id;
                    this.name = this.template.name;
                    this.description = this.template.description;
                    this.sections = this.template.sections;
                }
            },
            save() {
                this.saving = true;

                axios({
                    method: this.formMethod,
                    url: this.formEndpoint,
                    data:  {
                        name: this.name,
                        description: this.description,
                        sections: this.sections,
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('template-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/templates');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to template';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Template already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Template already exists!';

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
                    this.alertMessage = 'Failed to create template try again later';
                });
            },
            generateSlug() {
                this.sectionSlug = this.sectionName.toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            },
            addSection () {
                let sectionExists = this.sections.filter(section => {
                    return (section.name === this.sectionName && section.slug === this.sectionSlug);
                });

                if (this.sectionName === '' || this.sectionSlug === '') {
                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Section name and slug must not be empty';

                    return;
                }

                if (sectionExists.length > 0) {
                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Section with name or slug already exists, pick a unique name';

                    return;
                }

                this.sections.push({
                    name: this.sectionName,
                    slug: this.sectionSlug,
                });

                this.sectionName = '';
                this.sectionSlug = '';
                this.alertShow = false;
            },
            removeSection(section) {
                let index = this.sections.indexOf(section);

                this.sections.splice(index, 1);
            }
        }
    }
</script>
