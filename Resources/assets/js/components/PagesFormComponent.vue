<template>
    <div>
        <div class="row">
            <div class="col-md-12">
              <div class="admin-title-section pt-2">
                  <a @click="goBack" class="back-btn p-0">
                    <b-icon icon="arrow-left" font-scale="1"></b-icon>
                  </a>
                <span class="admin-title pl-2">
                    {{ type === 'create' ? 'Create new page' : 'Edit page ' }}
                    <strong v-show="type === 'edit'">{{ page.name }}</strong>
                </span>
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
                                ></b-form-input>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <b-form-group id="page-slug-group" label="Page URL" label-for="page-slug">
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
                          <div class="mt-4">
                            <div class="nav nav-tabs mt-2" id="nav-tab" role="tablist">
                              <button class="nav-link active" id="nav-home-tab" @click="show('pageFeatureTab')" :style="currentTab === 'pageFeatureTab' ? 'background-color: #ffffff' :'background-color: #F0F0F0'">Page Features</button>
                              <button class="nav-link" id="nav-profile-tab" @click="show('metaTab')" :style="currentTab === 'metaTab' ? 'background-color: #ffffff': 'background-color: #F0F0F0'">Meta Information</button>
                            </div>
                            <div class="tab-content" id="nav-tabContent">
                              <div v-show="currentTab === 'pageFeatureTab'" id="pageFeatureTab">
                                <div class="page-section p-4">
                                  <page-components class="m-2" v-model="components" :template="template" :editing="isEditing"></page-components>
                                </div>
                              </div>
                              <div v-show="currentTab === 'metaTab'" id="metaTab">
                                <div class="page-section p-4">
                                  <h5 class="mb-3">Meta Information</h5>
                                  <div class="row">
                                    <div class="col-12 col-md-4 col-xl-4">
                                      <p class="metaHeading text-small float-right">**for Google</p>
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
                                      <b-form-group id="page-image-group" label="Image" label-for="page-image" v-if="!showImageUploadBlock">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <a @click="uploadNewImage = true" class="float-right text-small" style="cursor: pointer">Change image</a>
                                            <b-img :src="meta.image" alt="Responsive image" style="width: 100%; background-size: cover"></b-img>
                                          </div>
                                        </div>
                                      </b-form-group>
                                      <b-form-group id="page-image-group" label="Image" label-for="page-image" v-if="showImageUploadBlock">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <image-upload-component @image-removed="removeUploadedImage" v-model="meta.image" @image-uploaded="setUploadedImage"></image-upload-component>
                                          </div>
                                        </div>
                                      </b-form-group>
                                    </div>
                                    <div class="col-12 col-md-4 col-xl-4">
                                      <p class="metaHeading text-small float-right">**for Facebook</p>
                                      <b-form-group id="page-facebook-title-group" label="Title" label-for="page-facebook-title">
                                        <b-form-input
                                            id="page-facebook-title-input"
                                            required
                                            v-model="meta.facebookTitle"
                                            placeholder="e.g. Homepage"
                                        ></b-form-input>
                                      </b-form-group>
                                      <b-form-group id="page-facebook-keywords-group" label="Keywords" label-for="page-facebook-keywords">
                                        <b-form-input
                                            id="page-facebook-keywords-input"
                                            required
                                            v-model="meta.facebookKeywords"
                                            placeholder="e.g. trap,music,museum"
                                        ></b-form-input>
                                      </b-form-group>
                                      <b-form-group id="page-facebook-author-group" label="Author" label-for="page-facebook-author">
                                        <b-form-input
                                            id="page-facebook-author-input"
                                            required
                                            v-model="meta.facebookAuthor"
                                            placeholder="e.g. Andre Figueira"
                                        ></b-form-input>
                                      </b-form-group>
                                      <b-form-group id="page-facebook-description-group" label="Description" label-for="page-facebook-description">
                                        <b-textarea
                                            id="page-facebook-description-input"
                                            required
                                            v-model="meta.facebookDescription"
                                        ></b-textarea>
                                      </b-form-group>
                                      <b-form-group id="page-facebook-image-group" label="Image" label-for="page-facebook-image" v-if="!showFbImageUploadBlock">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <a @click="uploadNewFbImage = true" class="float-right text-small" style="cursor: pointer">Change image</a>
                                            <b-img :src="meta.facebookImage" alt="Responsive image" style="width: 100%; background-size: cover"></b-img>
                                          </div>
                                        </div>
                                      </b-form-group>
                                      <b-form-group id="page-facebook-image-group" label="Image" label-for="page-facebook-image" v-if="showFbImageUploadBlock">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <image-upload-component @image-removed="removeUploadedFbImage" v-model="meta.facebookImage" @image-uploaded="setUploadedFbImage"></image-upload-component>
                                          </div>
                                        </div>
                                      </b-form-group>
                                    </div>
                                    <div class="col-12 col-md-4 col-xl-4">
                                      <p class="metaHeading text-small float-right">**for Twitter</p>
                                      <b-form-group id="page-twitter-title-group" label="Title" label-for="page-twitter-title">
                                        <b-form-input
                                            id="page-twitter-title-input"
                                            required
                                            v-model="meta.twitterTitle"
                                            placeholder="e.g. Homepage"
                                        ></b-form-input>
                                      </b-form-group>
                                      <b-form-group id="page-twitter-keywords-group" label="Keywords" label-for="page-twitter-keywords">
                                        <b-form-input
                                            id="page-twitter-keywords-input"
                                            required
                                            v-model="meta.twitterKeywords"
                                            placeholder="e.g. trap,music,museum"
                                        ></b-form-input>
                                      </b-form-group>
                                      <b-form-group id="page-twitter-author-group" label="Author" label-for="page-twitter-author">
                                        <b-form-input
                                            id="page-twitter-author-input"
                                            required
                                            v-model="meta.twitterAuthor"
                                            placeholder="e.g. Andre Figueira"
                                        ></b-form-input>
                                      </b-form-group>
                                      <b-form-group id="page-twitter-description-group" label="Description" label-for="page-twitter-description">
                                        <b-textarea
                                            id="page-twitter-description-input"
                                            required
                                            v-model="meta.twitterDescription"
                                        ></b-textarea>
                                      </b-form-group>
                                      <b-form-group id="page-twitter-image-group" label="Image" label-for="page-twitter-image" v-if="!showTwImageUploadBlock">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <a @click="uploadNewTwImage = true" class="float-right text-small" style="cursor: pointer">Change image</a>
                                            <b-img :src="meta.twitterImage" alt="Responsive image" style="width: 100%; background-size: cover"></b-img>
                                          </div>
                                        </div>
                                      </b-form-group>
                                      <b-form-group id="page-twitter-image-group" label="Image" label-for="page-twitter-image" v-if="showTwImageUploadBlock">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <image-upload-component @image-removed="removeUploadedTwImage" v-model="meta.twitterImage" @image-uploaded="setUploadedTwImage"></image-upload-component>
                                          </div>
                                        </div>
                                      </b-form-group>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
<!--                            <b-card no-body>
                                <b-tabs pills card end>
                                    <b-tab title="Page Features" active>
                                        <b-card-text>
                                            <page-components class="m-2" v-model="components" :template="template" :editing="isEditing"></page-components>
                                        </b-card-text>
                                    </b-tab>
                                    &lt;!&ndash;                                    <b-tab title="SEO">
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
                                    </b-tab>&ndash;&gt;
                                    <b-tab title="Advanced">
                                        <b-card-text>
                                            <b-form-checkbox
                                                id="dynamic-url"
                                                v-model="dynamicUrl"
                                                name="dynamic-url"
                                                :value="true"
                                                :unchecked-value="false">
                                                Dynamic URL
                                            </b-form-checkbox>
                                        </b-card-text>
                                    </b-tab>
                                </b-tabs>
                            </b-card>-->
                        </div><!-- End page section -->
                    </div><!-- End col -->
                </div><!-- End row -->
            </div><!-- End col -->
            <div class="col-md-3">
              <div class="page-section p-4 mb-2">
                    <label for="primary-option">Page type</label>
                    <v-select class="mb-3" id="primary-option" label="title" v-model="primary" :options="primaryOptions" :clearable="false"></v-select>

                    <label for="authentication-option">Authentication required</label>
                    <v-select class="mb-3" id="authentication-option" label="title" v-model="authentication" :options="authenticationOptions" :clearable="false"></v-select>

                    <label for="authentication-option">Anonymous only</label>
                    <v-select class="mb-3" id="authentication-option" label="title" v-model="anonymousOnly" :options="authenticationOptions" :clearable="false"></v-select>

                    <label for="parent">Parent</label>
                    <v-select class="mb-3" id="parent" label="name" v-model="parent" :options="pages" :clearable="false"></v-select>

                    <label for="template">Template</label>
                    <v-select class="mb-3" id="template" label="name" v-model="template" :options="templates" :clearable="false"></v-select>

                    <button class="btn btn-primary" :disabled="saving" @click="save()">{{ button }}</button>
                </div><!-- End content sidebar -->
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../admin'
    import helpers from '../helpers'
    import ImageUploadComponent from "./ImageUploadComponent";

    export default {
      components: {ImageUploadComponent},
      mounted() {
        console.log('Component mounted.');
        bus.$on('pageEdited', (value) => {
          this.isEditing = value
        })
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
          currentTab: 'pageFeatureTab',
          saving: false,
          alertShow: false,
          alertType: 'primary',
          alertMessage: '',
          isEditing: false,
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
          anonymousOnly: {
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
          dynamicUrl: false,
          dynamicUrlPattern: '',
          components: {},
          meta: {
            title: '',
            keywords: '',
            author: '',
            description: '',
            image: '',
            facebookTitle: '',
            facebookKeywords: '',
            facebookAuthor: '',
            facebookDescription: '',
            facebookImage: '',
            twitterTitle: '',
            twitterKeywords: '',
            twitterAuthor: '',
            twitterDescription: '',
            twitterImage: ''
          },
          uploadNewImage: false,
          uploadNewFbImage: false,
          uploadNewTwImage: false
        }
      },
      computed: {
        showImageUploadBlock () {
          return this.uploadNewImage || !this.meta.image
        },
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
        },
        showFbImageUploadBlock () {
          return this.uploadNewFbImage || !this.meta.facebookImage
        },
        showTwImageUploadBlock () {
          return this.uploadNewTwImage || !this.meta.twitterImage
        },
      },
      methods: {
        show(id) {
          this.currentTab = id
        },
        editUpdated(event) {
          // console.log('InPAGESFORM', event.target.value)
          this.isEditing = event.target.value
        },
        leaving: function () {
        },
        goBack() {
          window.history.back();
        },
        loadParentOptions(defaultOption) {
          axios.get('/api/page', { params: { all: true } }).then(response => {
            this.pages = response.data;
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
            this.anonymousOnly = this.authenticationOptions.filter((option) => {
              return option.value === this.page.anonymousOnly;
            })[0];
            this.name = this.page.name;
            this.slug = this.page.slug;
            this.components = this.page.components;
            this.meta = this.page.meta;
            this.dynamicUrl = this.page.settings.dynamic_url;
          }
        },
        loadTemplateOptions() {
          axios.get('/api/template', { params: { all: true } }).then(response => {
            this.templates = response.data;

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
        save() {
          this.saving = true;

          axios({
            method: this.formMethod,
            url: this.formEndpoint,
            data: {
              primary: this.primary.value,
              authentication: this.authentication.value,
              anonymousOnly: this.anonymousOnly.value,
              parent_id: this.parent.id,
              template_id: this.template.id,
              name: this.name,
              slug: this.slug,
              settings: {
                dynamic_url: this.dynamicUrl,
              },
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
            this.isEditing = false
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
        },
        setUploadedImage(path) {
          this.meta.image = path;
        },
        removeUploadedImage() {
          this.meta.image = '';
        },
        setUploadedFbImage(path) {
          this.meta.facebookImage = path;
        },
        removeUploadedFbImage() {
          this.meta.facebookImage = '';
        },
        setUploadedTwImage(path) {
          this.meta.twitterImage = path;
        },
        removeUploadedTwImage() {
          this.meta.twitterImage = '';
        },
      },
      watch: {
        name(newValue, oldValue) {
          if (oldValue !== '')
            this.isEditing = true
        },
        slug(newValue, oldValue) {
          if (oldValue !== '')
            this.isEditing = true
        },
        primary(newValue, oldValue) {
          if (oldValue !== '')
            this.isEditing = true
        },
        authentication(newValue, oldValue) {
          if (oldValue !== '')
            this.isEditing = true
        },
        parent(newValue, oldValue) {
          if (oldValue !== '')
            this.isEditing = true
        },
        template(newValue, oldValue) {
          if (oldValue !== '')
            this.isEditing = true
        }
      }
    }
</script>

<style scoped>
.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  background: #dfe5fb;
  border: none;
  color: #394066;
  text-transform: lowercase;
  font-variant: small-caps;
}

.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
  fill: #394066 !important;
}
.page-section {
  border-radius: initial !important;
}

.metaHeading {
  color: #5664D2;
}
</style>

