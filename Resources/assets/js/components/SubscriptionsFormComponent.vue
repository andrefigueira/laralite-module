<template>
    <div>
        <div class="row">
            <div class="col-md-12">
              <div class="admin-title-section pt-2">
                <a @click="goBack" class="back-btn p-0">
                  <b-icon icon="arrow-left" font-scale="1"></b-icon>
                </a>
                <span class="admin-title pl-2">
                  {{ type === 'create' ? 'Create new subscription' : 'Edit subscription ' }}
                  <strong v-show="type === 'edit'">{{ subscription.name }}</strong>
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
                            <b-form-group id="subscription-name-group" label="Subscription name" label-for="subscription-name-input">
                                <b-form-input
                                    id="subscription-name-input"
                                    required
                                    v-model="form.name"
                                    :state="validateState('name')"
                                    placeholder="Enter subscription name"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid name with more than 3 characters</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-12">
                            <b-form-group id="subscription-description-group" label="Subscription description" label-for="subscription-description-input">
                                <b-form-input
                                    id="subscription-description-input"
                                    required
                                    v-model="form.description"
                                    :state="validateState('description')"
                                    placeholder="Enter subscription description"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid description with more than 3 characters</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-12">
                            <b-form-group id="subscription-price-group" label="Subscription price" label-for="subscription-price-input">
                                <b-form-input
                                    id="subscription-price-input"
                                    required
                                    v-model="form.price"
                                    :state="validateState('price')"
                                    placeholder="Enter subscription price"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid price in integer</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                      <div class="col-md-12">
                        <b-form-group id="subscription-credit-amount-group" label="Subscription Credit Amount" label-for="subscription-credit-amount-input">
                          <b-form-input
                              id="subscription-credit-amount-input"
                              required
                              v-model="form.default_credit_amount"
                              :state="validateState('default_credit_amount')"
                              placeholder="Enter subscription periodic credit amount"
                          ></b-form-input>
                          <b-form-invalid-feedback>Enter a valid credit amount number</b-form-invalid-feedback>
                        </b-form-group>
                      </div><!-- End col -->
                      <div class="col-md-12">
                        <b-form-group id="subscription-initial-credit-amount-group" label="Subscription Initial Credit Amount" label-for="subscription-initial-credit-amount-input">
                          <b-form-input
                              id="subscription-initial-credit-amount-input"
                              required
                              v-model="form.default_initial_credit_amount"
                              :state="validateState('default_initial_credit_amount')"
                              placeholder="Enter subscription initial credit amount"
                          ></b-form-input>
                          <b-form-invalid-feedback>Enter a valid initial credit amount number</b-form-invalid-feedback>
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
    import { required, minLength, integer } from 'vuelidate/lib/validators'

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
            subscription: {
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
                form: {
                    id: '',
                    name: '',
                    description: '',
                    price: '',
                    default_credit_amount: 0,
                    default_initial_credit_amount: 0,
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
                },
                default_credit_amount: {
                  integer
                },
                default_initial_credit_amount: {
                  integer
                },
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
                let endpoint = '/api/subscriptions';

                if (this.type === 'edit') {
                    endpoint = '/api/subscriptions/' + this.subscription.id;
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
                if (this.subscription.id !== undefined) {
                    this.form.id = this.subscription.id;
                    this.form.name = this.subscription.name;
                    this.form.price = this.subscription.prices[0].price;
                    this.form.description = this.subscription.description;
                    this.form.default_credit_amount = this.subscription.default_credit_amount;
                    this.form.default_initial_credit_amount = this.subscription.default_initial_credit_amount;
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
                    data: {
                        name: this.form.name,
                        description: this.form.description,
                        price: this.form.price,
                        default_credit_amount: this.form.default_credit_amount,
                        default_initial_credit_amount: this.form.default_initial_credit_amount
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('subscription-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/subscriptions');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to subscription';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Subscription already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Subscription already exists!';

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
                    this.alertMessage = 'Failed to create subscription try again later';
                });
            }
        }
    }
</script>
