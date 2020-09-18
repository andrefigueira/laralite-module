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
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
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

                            <b-form-group id="product-description-group" label="Product description" label-for="product-description">
                                <b-form-textarea
                                    id="product-description-input"
                                    required
                                    v-model="form.description"
                                    :state="validateState('description')"
                                    placeholder="Enter product description"
                                ></b-form-textarea>
                                <b-form-invalid-feedback>Enter a valid description</b-form-invalid-feedback>
                            </b-form-group>

                            <b-form-group id="product-price-group" label="Product price" label-for="product-price">
                              <b-form-input
                                  id="product-price-input"
                                  required
                                  v-model="form.price"
                                  :state="validateState('price')"
                                  placeholder="Enter product price"
                              ></b-form-input>
                              <b-form-invalid-feedback>Enter a valid price</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">

                        </div><!-- End col -->
                        <div class="col-md-6">

                        </div><!-- End col -->
                        <div class="col-md-6">

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
                form: {
                    id: '',
                    name: '',
                }
            }
        },
        validations: {
            form: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                description: {
                    required,
                    minLength: minLength(3)
                },
                price: {
                    required
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
            load() {
                if (this.product.id !== undefined) {
                    this.form.id = this.product.id;
                    this.form.name = this.product.name;
                    this.form.description = this.product.description;
                    this.form.price = this.product.price;
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
                        description: this.form.description,
                        price: this.form.price,
                        meta: {},
                        images: {}
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
            }
        }
    }
</script>
