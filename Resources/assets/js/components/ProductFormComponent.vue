<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="admin-title-section">
                  <h2 class="admin-title">
                      {{ type === 'create' ? 'Create new product' : 'Edit product ' }}
                      <strong v-show="type === 'edit'">{{ product.name }}</strong>
                  </h2>
                </div><!-- End admin title section -->

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="row">
            <div class="col-12">
                <div class="page-section">
                    <form-wizard shape="tab" color="#333" error-color="#ff4949" finish-button-text="Save & Publish">
                        <template slot="title">
                            <div></div>
                        </template>
                        <tab-content title="Product Information" icon="fas fa-file" :before-change="()=>validateProductInformation()">
                            <div class="row">
                                <div class="col-3">
                                    <p class="alert alert-danger" v-if="$v.form.images.$error"><i class="fas fa-exclamation-circle"></i> Must upload an image</p>
                                    <image-upload-component @image-removed="removeUploadedImage" @image-uploaded="setUploadedImage"></image-upload-component>

                                    <label class="mt-3" for="primary-option">Product category</label>
                                    <v-select class="mb-3" id="primary-option" label="name" v-model="category" :options="categoryOptions" :clearable="false"></v-select>
                                </div><!-- End col -->
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-6">
                                            <b-form-group id="product-name-group" label="Product name" label-for="product-name">
                                                <b-form-input
                                                    id="product-name-input"
                                                    required
                                                    v-model="form.name"
                                                    :state="validateState('name')"
                                                    placeholder="Enter product name"
                                                ></b-form-input>
                                                <b-form-invalid-feedback>Enter a valid name with more than 3 characters</b-form-invalid-feedback>
                                            </b-form-group>
                                        </div><!-- End col -->
                                        <div class="col-6">
                                            <b-form-group id="product-url-group" label="Product URL" label-for="product-url">
                                                <b-form-input
                                                    id="product-url-input"
                                                    required
                                                    v-model="form.url"
                                                    :state="validateState('url')"
                                                    placeholder="Enter product url"
                                                ></b-form-input>
                                                <b-form-invalid-feedback>Enter a valid url with more than 3 characters</b-form-invalid-feedback>
                                            </b-form-group>
                                        </div><!-- End col -->
                                    </div><!-- End row -->

                                    <b-form-group id="product-description-group" label="Product description" label-for="product-description">
                                        <editor
                                            api-key="1zv9du0onoyl619egrfevih7r7p4p8vawafvqhi5hzzfutmf"
                                            :value="form.description"
                                            :init="config"
                                        />
                                        <b-form-invalid-feedback>Enter a valid description</b-form-invalid-feedback>
                                    </b-form-group>
                                </div><!-- End col -->
                            </div><!-- End row -->
                        </tab-content>
                        <tab-content title="Pricing & Variants" icon="fas fa-dollar-sign">
                            <h3>Product Variants</h3>
                            <p>Define the options of your item, create variants, and track stock levels.
                                Each row in the table below represents a variant on this product. You
                                can add additional options to those variants by adding columns. Subscription
                                products do not have stock levels.</p>

                            <table class="table">
                                <tr>
                                    <th>Image</th>
                                    <th>SKU</th>
                                    <th>Pricing</th>
                                    <th>Stock</th>
                                    <th>Weight</th>
                                    <th>Dimensions</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td><img src="http://placehold.it/40x40" alt=""></td>
                                    <td>ASD349203</td>
                                    <td>$0.00</td>
                                    <td>1</td>
                                    <td>0lb</td>
                                    <td>-</td>
                                    <td><b-button @click="confirmDelete()" variant="default" size="sm" class="float-right"><i class="far fa-trash-alt"></i></b-button></td>
                                </tr>
                            </table>

                            <b-button v-b-tooltip.hover title="Click to add a new product variant" @click="" variant="default" size="sm"><i class="fas fa-plus"></i></b-button>
                        </tab-content>
                        <tab-content title="SEO" icon="fas fa-globe-europe">
                            <h3>Product SEO</h3>

                            <b-form-group id="product-seo-title-group" label="Product name" label-for="product-seo-title">
                                <b-form-input
                                    id="product-seo-title-input"
                                    required
                                    v-model="form.meta.title"
                                    placeholder="Enter SEO title"
                                ></b-form-input>
                            </b-form-group>

                            <b-form-group id="product-seo-keywords-group" label="Product name" label-for="product-seo-keywords">
                                <b-form-input
                                    id="product-seo-keywords-input"
                                    required
                                    v-model="form.meta.keywords"
                                    placeholder="Enter SEO keywords"
                                ></b-form-input>
                            </b-form-group>

                            <b-form-group id="product-seo-description-group" label="Product name" label-for="product-seo-description">
                                <b-form-textarea
                                    id="product-seo-description-input"
                                    required
                                    v-model="form.meta.description"
                                    placeholder="Enter SEO description"
                                ></b-form-textarea>
                            </b-form-group>
                        </tab-content>
                        <tab-content title="Summary" icon="fas fa-clipboard-check">
                            Summary of all information added and a create button

                            trigger @save with the finish button
                        </tab-content>
                        <template slot="footer" slot-scope="props">
                            <div class="wizard-footer-left">
                                <wizard-button v-if="props.activeTabIndex > 0 && !props.isLastStep" @click.native="props.prevTab()" :style="props.fillButtonStyle">Previous</wizard-button>
                            </div>
                            <div class="wizard-footer-right">
                                <wizard-button v-if="!props.isLastStep" @click.native="props.nextTab()" class="wizard-footer-right" :style="props.fillButtonStyle">Next</wizard-button>

                                <wizard-button v-else @click.native="save()" class="wizard-footer-right finish-button" :style="props.fillButtonStyle">{{props.isLastStep ? 'Save & Publish' : 'Next'}}</wizard-button>
                            </div>
                        </template>
                    </form-wizard>
                </div><!-- End page section -->
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import {FormWizard, TabContent} from 'vue-form-wizard'
    import 'vue-form-wizard/dist/vue-form-wizard.min.css'
    import { bus } from '../admin'
    import helpers from '../helpers'
    import { validationMixin } from 'vuelidate'
    import { required, minLength } from 'vuelidate/lib/validators'
    import Editor from '@tinymce/tinymce-vue'

    export default {
        mixins: [validationMixin],
        components: {
            Editor,
            FormWizard,
            TabContent
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
            product: {
                type: Object,
                default: function () {
                  return {};
                }
            }
        },
        data() {
            return {
                saving: false,
                alertShow: false,
                alertType: 'primary',
                alertMessage: '',
                category: {},
                categoryOptions: [],
                form: {
                    id: '',
                    name: '',
                    price: '',
                    meta: {
                        title: '',
                        keywords: '',
                        description: ''
                    },
                    images: []
                },
                config: {
                    height: 300,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor importcss',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    menubar: 'edit view insert format tools table help',
                    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
                    content_css: '/css/contents.css',
                    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                    contextmenu: "link image imagetools table",
                    formats: {
                        alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left' },
                        aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center' },
                        alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right' },
                        alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full' },
                        bold: { inline: 'span', classes: 'bold' },
                        italic: { inline: 'span', classes: 'italic' },
                        underline: { inline: 'span', classes: 'underline', exact: true },
                        strikethrough: { inline: 'del' },
                        customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format'} , classes: 'example1'}
                    },
                    font_formats: 'Oswold=Oswald,san-serif;ProximaNova=Lato,san-serif;Arial=arial,helvetica,sans-serif;',
                    fontsize_formats: '11px 12px 14px 16px 18px 24px 36px 48px'
                }
            }
        },
        validations: {
            form: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                url: {
                    required,
                    minLength: minLength(3)
                },
                description: {
                    required,
                    minLength: minLength(3)
                },
                images: {
                    required,
                }
            }
        },
        computed: {
            noProductDefined() {
                return this.product.id !== undefined;
            },
            button() {
                if (this.type === 'create') {
                    return 'Create';
                }

                return 'Save changes'
            },
            formEndpoint() {
                let endpoint = '/api/product';

                if (this.type === 'edit') {
                    endpoint = '/api/product/' + this.product.id;
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
            validateState(name) {
                const { $dirty, $error } = this.$v.form[name];
                return $dirty ? !$error : null;
            },
            loadProductCategoryOptions(defaultOption) {
              axios.get('/api/product-category').then(response => {
                  this.categoryOptions = response.data.data;

                  if (this.product.category_id === undefined) {
                      this.category = this.categoryOptions[0];
                  } else {
                      this.category = this.categoryOptions.filter((productCategory) => {
                          return productCategory.id === this.product.category_id;
                      })[0];
                  }
              }).catch(error => {
                // handle error
              });
            },
            load() {
                if (this.product.id !== undefined) {
                    this.form.id = this.product.id;
                    this.category_id = this.category.id;
                    this.form.name = this.product.name;
                    this.form.description = this.product.description;
                    this.form.price = this.product.price;
                }

                let defaultProductCategoryValue = {
                  id: null,
                  name: 'No category'
                };

                this.loadProductCategoryOptions(defaultProductCategoryValue);
            },
            save() {
                this.$v.form.$touch();

                if (this.$v.form.$anyError) {
                    return;
                }

                this.saving = true;

                axios({
                    method: this.formMethod,
                    url: this.formEndpoint,
                    data:  {
                        name: this.form.name,
                        description: this.form.description,
                        category_id: this.category.id,
                        price: this.form.price,
                        variants: [],
                        meta: {},
                        images: []
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('product-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/product');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to product';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Product already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Product already exists!';

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
                    this.alertMessage = 'Failed to create product try again later';
                });
            },
            generateSlug() {
                this.sectionSlug = this.sectionName.toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            },
            setUploadedImage(path) {
                this.form.images[0] = path;
                this.$v.form['images'].$touch();
            },
            removeUploadedImage() {
                this.form.images = [];
                this.$v.form['images'].$touch();
            },
            validateProductInformation() {
                return new Promise((resolve, reject) => {
                    this.$v.form.$touch();

                    switch (true) {
                        case this.form.name === '':
                            reject('Name must not be empty');
                            break;
                        case this.form.url === '':
                            reject('URL must not be empty');
                            break;
                        case this.form.description === '':
                            reject('Name must not be empty');
                            break;
                        case this.form.images[0] === undefined:
                            reject('Must upload an image');
                            break;
                        default:
                            console.log('validation passed');
                            resolve(true);
                    }
                })
            }
        }
    }
</script>

<style lang="scss">
    .wizard-header {
        display: none;
    }

    .wizard-navigation {
        .wizard-progress-with-circle {
            display: none;
        }
        .wizard-nav {
            border-bottom: 1px solid #F1F1F1;
            padding-bottom: 0.4rem;
            li {
                display: block;
                margin-top: -0.2rem;
                a {
                    display: block;
                    .tab_shape {
                        outline: none!important;
                    }
                    .wizard-icon {
                        font-style: normal;
                        font-size: 1rem;
                    }
                    .stepTitle {
                        display: block;
                        padding: 0.6rem 0;
                        font-size: 0.8rem;
                    }
                }
                &.active {
                    a {
                        .wizard-icon {
                            font-size: 1rem!important;
                        }
                    }
                }
            }
        }
    }
</style>