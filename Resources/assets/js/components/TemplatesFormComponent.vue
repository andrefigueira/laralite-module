<template>
    <div>
        <div class="row">
            <div class="col-md-12">
              <div class="admin-title-section">
                  <h2 class="admin-title">
                      {{ type === 'create' ? 'Create new template' : 'Edit template ' }}
                      <strong v-show="type === 'edit'">{{ template.name }}</strong>
                  </h2>
              </div>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-6">
                            <b-form-group id="template-name-group" label="Template name" label-for="template-name">
                                <b-form-input
                                    id="template-name-input"
                                    required
                                    v-model="name"
                                    placeholder="Enter template name"
                                ></b-form-input>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-6">
                            <b-form-group id="template-module-name-group" label="Module" label-for="template-module-name">
                                <v-select v-model="moduleItem" :options="moduleOptions"></v-select>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-6">
                              <b-form-group id="template-image-group" label="Template image" label-for="template-image" v-if="!showBackgroundUploadBlock">
                                <div class="row">
                                  <div class="col-md-12">
                                    <a @click="uploadNewImage = true" class="float-right" style="cursor: pointer">Change image</a>
                                    <b-img :src="template.background_image" alt="Responsive image" style="height: 165px !important; width: 100%; background-size: cover"></b-img>
                                  </div>
<!--                                  <div class="col-md-2">
                                    <b-button @click="uploadNewImage = true">Change image</b-button>
                                  </div>-->
                                </div>
                              </b-form-group>
                              <b-form-group id="template-image-group" label="Template image" label-for="template-image" v-if="showBackgroundUploadBlock">
                                <div class="row">
                                  <div class="col-md-12">
                                    <image-upload-component @image-removed="removeUploadedImage" v-model="image" @image-uploaded="setUploadedImage"></image-upload-component>
                                  </div>
                                </div>
                              </b-form-group>

                            </div>
                            <div class="col-md-6">
                              <b-form-group id="template-description-group" label="Template description" label-for="template-description">
                                <b-form-textarea id="template-name-input" v-model="description" rows="8" required></b-form-textarea>
                              </b-form-group>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-md-6">
                                  <b-form-group id="template-header-navigation-group" label="Template header navigation" label-for="template-navigation">
                                      <v-select class="mb-3" id="header-navigation" label="name" v-model="selectedHeaderNavigation" :options="navigation" :clearable="false"></v-select>
                                  </b-form-group>
                              </div>
                              <div class="col-md-6">
                                  <b-form-group id="template-footer-navigation-group" label="Template footer navigation" label-for="template-navigation">
                                      <v-select class="mb-3" id="footer-navigation" label="name" v-model="selectedFooterNavigation" :options="navigation" :clearable="false"></v-select>
                                  </b-form-group>
                              </div>
                          </div>

                            <hr>

                            <h3>Template section management</h3>

                            <div class="page-section p-4 mb-4">
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
                                    <div class="col-md-6">
                                        <b-form-group id="section-column-group" label="Section column size" label-for="section-column">
                                            <b-form-input
                                                id="section-column-input"
                                                required
                                                v-model="sectionColumn"
                                                placeholder="Enter section column size"
                                            ></b-form-input>
                                        </b-form-group>
                                    </div><!-- End col -->
                                    <div class="col-md-6">
                                        <b-form-group id="section-offset-column-group" label="Section column offset size" label-for="section-offset-column">
                                            <b-form-input
                                                id="section-offset-column-input"
                                                required
                                                v-model="sectionOffsetColumn"
                                                placeholder="Enter section column offset size"
                                            ></b-form-input>
                                        </b-form-group>
                                    </div><!-- End col -->
                                    <div class="col-md-6">
                                        <b-form-group id="section-order-group" label="Section order" label-for="section-order">
                                            <b-form-input
                                                id="section-order-input"
                                                required
                                                v-model="sectionOrder"
                                                placeholder="Enter section order"
                                            ></b-form-input>
                                        </b-form-group>
                                    </div><!-- End col -->
                                    <div class="col-md-6">
                                        <b-form-group id="section-wrapper-class-group" label="Section wrapper class" label-for="section-wrapper-class">
                                            <b-form-input
                                                id="section-wrapper-class-input"
                                                required
                                                v-model="sectionWrapperClass"
                                                placeholder="Enter section wrapper class"
                                            ></b-form-input>
                                        </b-form-group>
                                    </div><!-- End col -->
                                </div><!-- End row -->

                                <b-button class="mb-2" variant="success" @click="addSection()">Add section</b-button>
                            </div>

                            <b-list-group>
                                <b-list-group-item v-for="section in sections" v-bind:key="section.slug">
                                    <strong>Name: </strong> {{ section.name }}<br>
                                    <strong>Slug: </strong> {{ section.slug }}<br>
                                    <strong>Column: </strong> {{ section.column }}<br>
                                    <strong>Offset: </strong> {{ section.offset }}<br>
                                    <strong>Order: </strong> {{ section.order }}<br>
                                    <strong>Wrapper class: </strong> {{ section.wrapper }}
                                    <b-button class="btn-sm float-right" variant="danger" @click="removeSection(section)">&times;</b-button>
                                </b-list-group-item>
                            </b-list-group>

                            <hr>

                            <b-button class="mt-2 float-right" variant="success" :disabled="saving" @click="save()">{{ button }}</b-button>

                            <div class="clearfix"></div>
                        </div><!-- End col -->
                    </div><!-- End row -->
                </div><!-- End row -->
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
                moduleItem: '',
                moduleOptions: [],
                description: '',
                image: '',
                sections: [],
                sectionName: '',
                sectionSlug: '',
                sectionColumn: 3,
                sectionOffsetColumn: 3,
                sectionOrder: 0,
                sectionWrapperClass: '',
                navigation: [],
                selectedHeaderNavigation: {},
                selectedFooterNavigation: {},
                uploadNewImage: false
            }
        },
        computed: {
            showBackgroundUploadBlock () {
              return this.uploadNewImage || !this.template.background_image
            },
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
                let defaultOption = {
                    id: null,
                    name: 'No navigation'
                };

                this.loadHeaderNavigationOptions(defaultOption);

                this.loadModuleOptions();

                if (this.template.id !== undefined) {
                    this.id = this.template.id;
                    this.name = this.template.name;
                    this.description = this.template.description;
                    this.image = this.template.image;
                    this.sections = this.template.sections;
                }
            },
            loadHeaderNavigationOptions(defaultOption) {
                axios.get('/api/navigation').then(response => {
                    this.navigation = response.data.data;
                    this.navigation.unshift(defaultOption);

                    if (this.template.header_navigation_id === undefined) {
                        this.selectedHeaderNavigation = this.navigation[0];
                    } else {
                        this.selectedHeaderNavigation = this.navigation.filter((pageNavigation) => {
                            return pageNavigation.id === this.template.header_navigation_id;
                        })[0];
                    }

                    if (this.template.footer_navigation_id === undefined) {
                        this.selectedFooterNavigation = this.navigation[0];
                    } else {
                        this.selectedFooterNavigation = this.navigation.filter((pageNavigation) => {
                            return pageNavigation.id === this.template.footer_navigation_id;
                        })[0];
                    }
                }).catch(error => {
                    // handle error
                });
            },
            loadModuleOptions() {
                axios.get('/api/module').then(response => {
                    this.moduleOptions = response.data.data.modules;

                    if (this.template.module_name === undefined) {
                        this.moduleItem = this.moduleOptions[0];
                    } else {
                        this.moduleItem = this.moduleOptions.filter((moduleItem) => {
                            return moduleItem.label === this.template.module_name;
                        })[0];
                    }
                }).catch(error => {
                    // handle error
                });
            },
            save() {
                this.saving = true;

                let headerNavigationId = null;
                let footerNavigationId = null;

                if (this.selectedHeaderNavigation.id !== undefined) {
                    headerNavigationId = this.selectedHeaderNavigation.id;
                }

                if (this.selectedFooterNavigation.id !== undefined) {
                    footerNavigationId = this.selectedFooterNavigation.id;
                }

                axios({
                    method: this.formMethod,
                    url: this.formEndpoint,
                    data:  {
                        name: this.name,
                        module_name: this.moduleItem.label,
                        description: this.description,
                        image: this.image,
                        sections: this.sections,
                        header_navigation_id: headerNavigationId,
                        footer_navigation_id: footerNavigationId
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
                    location.reload();
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
                    column: this.sectionColumn,
                    offset: this.sectionOffsetColumn,
                    order: this.sectionOrder,
                    wrapper: this.sectionWrapperClass
                });

                this.sectionName = '';
                this.sectionSlug = '';
                this.sectionColumn = 3;
                this.sectionOrder = 0;
                this.alertShow = false;
            },
            removeSection(section) {
                let index = this.sections.indexOf(section);

                this.sections.splice(index, 1);
            },
            setUploadedImage(path) {
              this.image = path;
            },
            removeUploadedImage() {
              this.image = '';
            },
        }
    }
</script>
