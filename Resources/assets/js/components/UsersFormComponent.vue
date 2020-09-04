<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="admin-title">
                    {{ type === 'create' ? 'Create new user' : 'Edit user ' }}
                    <strong v-show="type === 'edit'">{{ user.name }}</strong>
                </h2>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <b-form-group id="user-name-group" label="User name" label-for="user-name">
                                <b-form-input
                                    id="user-name-input"
                                    required
                                    v-model="form.name"
                                    :state="validateState('name')"
                                    placeholder="Enter user name"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid name with more than 3 characters</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <b-form-group id="user-email-group" label="User email" label-for="user-email">
                                <b-form-input
                                    type="email"
                                    id="user-email-input"
                                    required
                                    v-model="form.email"
                                    :state="validateState('email')"
                                    placeholder="Enter user email"
                                    autocapitalize="none"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid email address</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <b-form-group id="user-password-group" label="Password" label-for="user-password">
                                <b-form-input
                                    type="password"
                                    id="user-password-input"
                                    required
                                    v-model="form.password"
                                    :state="validateState('password')"
                                    placeholder="Enter user password"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid password</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <b-form-group id="user-confirm-password-group" label="Confirm password" label-for="user-confirm-password">
                                <b-form-input
                                    type="password"
                                    id="user-confirm-password-input"
                                    required
                                    v-model="form.confirmPassword"
                                    :state="validateState('confirmPassword')"
                                    placeholder="Confirm password"
                                ></b-form-input>
                                <b-form-invalid-feedback>Passwords must match</b-form-invalid-feedback>
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
            user: {
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
                    email: '',
                    password: '',
                    confirmPassword: ''
                }
            }
        },
        validations: {
            form: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                email: {
                    required,
                    email
                },
                password: {
                    required: requiredIf('noUserDefined'),
                    minLength: minLength(6)
                },
                confirmPassword: {
                    confirmPassword: sameAs('password')
                }
            }
        },
        computed: {
            noUserDefined() {
                debugger;
                return this.user.id !== undefined;
            },
            button() {
                if (this.type === 'create') {
                    return 'Create';
                }

                return 'Save changes'
            },
            formEndpoint() {
                let endpoint = '/api/user';

                if (this.type === 'edit') {
                    endpoint = '/api/user/' + this.user.id;
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
                if (this.user.id !== undefined) {
                    this.form.id = this.user.id;
                    this.form.name = this.user.name;
                    this.form.email = this.user.email;
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
                        email: this.form.email,
                        password: this.form.password
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('user-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/users');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to user';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('User already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'User already exists!';

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
                    this.alertMessage = 'Failed to create user try again later';
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
