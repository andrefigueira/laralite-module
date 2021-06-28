<template>
    <div>
        <div class="row">
            <div class="col-md-12">
              <div class="admin-title-section">
                <b-button @click="goBack" variant="link" class="p-0 mr-3">
                  <b-icon icon="arrow-left" font-scale="1"></b-icon>
                </b-button>
                  <span class="admin-title">
                      {{ type === 'create' ? 'Create new navigation' : 'Edit navigation ' }}
                      <strong v-show="type === 'edit'">{{ navigation.name }}</strong>
                  </span>
              </div>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-12">
                            <b-form-group id="navigation-name-group" label="Navigation name" label-for="navigation-name">
                                <b-form-input
                                    id="navigation-name-input"
                                    required
                                    v-model="name"
                                    placeholder="Enter navigation name"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group id="navigation-description-group" label="Navigation description" label-for="navigation-description">
                                <b-form-textarea id="navigation-name-input" v-model="description" required></b-form-textarea>
                            </b-form-group>
                        </div><!-- End col -->
                    </div><!-- End row -->
                </div><!-- End row -->
                <div class="page-section p-4 mb-4">
                    <div class="row" v-if="!showAddLinkButton">
                        <div class="col-md-6">
                            <h3>Pages</h3>
                            <p>Drag pages from left to right to create navigation...</p>
                            <ul class="list-group draggable-list-group div:empty">
                                <draggable v-model="pageOptions" group="people" @start="drag=true" @end="drag=false">
                                    <li class="list-group-item" v-for="element in pageOptions" :key="element.id">
                                        {{element.name}} &middot; {{element.slug}}
                                    </li>
                                </draggable>
                                <p class="alert alert-info mt-2" v-if="pageOptions.length < 1 && pageOptionsLoaded">There are no pages available to display &middot; <a href="/admin/pages/create">Create one</a></p>
                            </ul>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <h3>Selected Pages</h3>
                            <p>Drag pages from right to left to remove from navigation...</p>
                            <ul class="list-group draggable-list-group">
                                <draggable v-model="selectedPages"  group="people" @start="drag=true" @end="drag=false">
                                    <li class="list-group-item" v-for="element in selectedPages" :key="element.id">
                                        {{element.name}} &middot; {{element.slug}}
                                        <input class="form-control mt-2" placeholder="Enter class names..." type="text" v-model="element.className">
                                    </li>
                                </draggable>
                            </ul>
                        </div><!-- End col -->

                        <div class="col-md-12">
                            <p class="alert alert-info mt-2">Or use the button below to add a custom link into the navigation...</p>
                        </div><!-- End col -->
                    </div><!-- End row -->

                    <b-button class="mt-2" variant="success" :disabled="saving" @click="showAddLinkButton = !showAddLinkButton" v-html="!showAddLinkButton ? 'Add Link' : 'Cancel'"></b-button>

                    <div class="row">
                        <div class="link-button-form col-md-3" v-if="showAddLinkButton">
                            <b-form-group class="mt-3" id="navigation-link-name-group" label="Link nav name" label-for="link-name">
                                <b-form-input type="text" class="form-control" placeholder="Enter name" v-model="link.name"></b-form-input>
                            </b-form-group>

                            <b-form-group id="navigation-link-target-group" label="Link nav name" label-for="link-target">
                                <b-form-input type="text" class="form-control" placeholder="Enter target url" v-model="link.slug"></b-form-input>
                            </b-form-group>

                            <b-form-group id="navigation-link-class-group" label="Link nav class name" label-for="link-class">
                                <b-form-input type="text" class="form-control" placeholder="Enter class name" v-model="link.className"></b-form-input>
                            </b-form-group>

                            <b-button class="mt-2" variant="success" :disabled="saving" @click="addLink">Add to navigation</b-button>
                        </div><!-- End col -->
                    </div><!-- End row -->

                    <hr class="mt-4">

                    <b-button class="mt-2 float-right" variant="success" :disabled="saving" @click="save()">{{ button }}</b-button>

                    <div class="clearfix"></div>
                </div><!-- End row -->
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../admin'
    import helpers from '../helpers'
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        props: {
            type: {
                type: String,
                default: 'create'
            },
            navigation: {
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
                pageOptions: [],
                pageOptionsLoaded: false,
                selectedPages: [],
                showAddLinkButton: false,
                link: {
                    name: '',
                    slug: '',
                    className: ''
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
                let endpoint = '/api/navigation';

                if (this.type === 'edit') {
                    endpoint = '/api/navigation/' + this.navigation.id;
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
          goBack() {
            window.history.back();
          },
            load() {
                if (this.navigation.id !== undefined) {
                    this.id = this.navigation.id;
                    this.name = this.navigation.name;
                    this.description = this.navigation.description;
                    this.selectedPages = this.navigation.navigation;
                }

                axios.get('/api/page').then(response => {
                    this.pageOptions = response.data.data.filter(navigationItem => {
                        let existsInSelectedPages = this.selectedPages.filter(selectedPage => {
                            return selectedPage.id === navigationItem.id;
                        });

                        return !(existsInSelectedPages.length > 0);
                    });

                    this.pageOptionsLoaded = true;

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            },
            save() {
                this.saving = true;

                if (this.selectedPages.length < 1) {
                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'No pages added to navigation...';
                    this.saving = false;

                    return;
                }

                axios({
                    method: this.formMethod,
                    url: this.formEndpoint,
                    data:  {
                        name: this.name,
                        description: this.description,
                        navigation: this.selectedPages
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('navigation-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/navigation');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to navigation';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Navigation already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Navigation already exists!';

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
                    this.alertMessage = 'Failed to create navigation try again later';
                });
            },
            addClass(element) {

            },
            addLink() {
                let navigationItemExistsInSelectedPages = this.selectedPages.filter(navigationItem => {
                    return (navigationItem.name.toLowerCase() === this.link.name.toLowerCase() && navigationItem.slug.toLowerCase() === this.link.slug.toLowerCase());
                });

                let navigationItemExistsInPageOptions = this.pageOptions.filter(navigationItem => {
                    return (navigationItem.name.toLowerCase() === this.link.name.toLowerCase() && navigationItem.slug.toLowerCase() === this.link.slug.toLowerCase());
                });

                if (this.link.name === '' || this.link.slug === '') {
                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Navigation name and slug must not be empty';

                    return;
                }

                if (navigationItemExistsInSelectedPages.length > 0 || navigationItemExistsInPageOptions.length > 0) {
                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Navigation item with name or slug already exists, pick a unique name and slug';

                    return;
                }

                this.selectedPages.push({
                    name: this.link.name,
                    slug: this.link.slug,
                    className: this.link.className
                });

                this.link = {
                    name: '',
                    slug: '',
                    className: ''
                };

                this.showAddLinkButton = false;
            }
        }
    }
</script>
