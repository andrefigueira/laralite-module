<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="admin-title-section pt-2">
                  <a @click="goBack" class="back-btn p-0">
                    <b-icon icon="arrow-left" font-scale="1"></b-icon>
                  </a>
                  <span class="admin-title pl-2">
                      {{ type === 'create' ? 'Create new product category' : 'Edit product ' }}
                      <strong v-show="type === 'edit'">{{ productCategory.name }}</strong>
                  </span>
                </div><!-- End admin title section -->

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <b-form-group id="product-category-name-group" label="Product category name" label-for="product-category-name">
                                <b-form-input
                                    id="product-category-name-input"
                                    required
                                    v-model="form.name"
                                    :state="validateState('name')"
                                    placeholder="Enter product category name"
                                    @keyup="generateSlug()"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid name with more than 3 characters</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                          <b-form-group id="product-category-slug-group" label="Product category slug" label-for="product-category-slug">
                            <b-form-input
                                id="product-category-slug-input"
                                required
                                v-model="form.slug"
                                :state="validateState('slug')"
                                placeholder="Enter product category slug"
                            ></b-form-input>
                            <b-form-invalid-feedback>Enter a valid slug with more than 3 characters</b-form-invalid-feedback>
                          </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-12">
                            <b-button class="mt-2" variant="success" :disabled="saving" @click="save()">{{ button }}</b-button>
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
    import { validationMixin } from 'vuelidate'
    import { required, minLength, email, sameAs, requiredIf } from 'vuelidate/lib/validators'

    export default {
        mixins: [validationMixin],
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        props: {
            type: {
                type: String,
                default: 'create'
            },
            productCategory: {
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
                form: {
                    id: '',
                    name: '',
                    slug: '',
                    description: '',
                }
            }
        },
        validations: {
            form: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                slug: {
                    required,
                    minLength: minLength(3)
                },
                description: {}
            }
        },
        computed: {
            noProductCategoryDefined() {
                return this.productCategory.id !== undefined;
            },
            button() {
                if (this.type === 'create') {
                    return 'Create';
                }

                return 'Save changes'
            },
            formEndpoint() {
                let endpoint = '/api/product-category';

                if (this.type === 'edit') {
                    endpoint = '/api/product-category/' + this.productCategory.id;
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
            validateState(name) {
                const { $dirty, $error } = this.$v.form[name];
                return $dirty ? !$error : null;
            },
            load() {
                if (this.productCategory.id !== undefined) {
                    this.form.id = this.productCategory.id;
                    this.form.name = this.productCategory.name;
                    this.form.slug = this.productCategory.slug;
                    this.form.description = this.productCategory.description;
                }
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
                        slug: this.form.slug,
                        description: this.form.description,
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('product-category-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/product-category');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to product category';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Product category already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Product category already exists!';

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
                    this.alertMessage = 'Failed to create product category try again later';
                });
            },
            generateSlug() {
                this.form.slug = this.form.name.toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            }
        }
    }
</script>
