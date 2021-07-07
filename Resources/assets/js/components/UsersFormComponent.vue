<template>
    <div>
        <div class="row">
            <div class="col-md-12">
              <div class="admin-title-section pt-2">
                <a @click="goBack" class="back-btn p-0">
                  <b-icon icon="arrow-left" font-scale="1"></b-icon>
                </a>
                  <span class="admin-title pl-2">
                      {{ type === 'create' ? 'Create new user' : 'Edit user ' }}
                      <strong v-show="type === 'edit'">{{ user.name }}</strong>
                  </span>
              </div>

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
                        <div class="col-md-6">
                            <b-form-group label="Roles *">
                                <b-form-select
                                    :required="true"
                                    :options="roles"
                                    v-model="temp_role"
                                    class="role-select"
                                    @input="(role)=>{
                                        if (role && form.roles.indexOf(role) < 0) {
                                            form.roles.push(role);
                                        }
                                    }"
                                >
                                    <b-form-select-option :value="null">Please select an option</b-form-select-option>
                                </b-form-select>

                                <b-button
                                    size="sm"
                                    class="m-1"
                                    title="Click to Remove"
                                    v-for="(role,key) in form.roles" :key="key"
                                    @click="()=>{
                                        if (temp_role == form.roles[key]) {
                                            temp_role = null;
                                        }
                                        form.roles.splice(key, 1);
                                    }"
                                >
                                    <b-icon-x />
                                    {{role}}
                                </b-button>
                            </b-form-group>
                        </div>
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
            axios.post(this.data_url, {
                roles: this.roles.length <= 0 ? ["id", "name"] : false,
                permissions: this.permissions.length <= 0 ? ["id", "name"] : false,
            }).then(res => {
                if (res.data.roles) {
                    this.roles = (res.data.roles || []).map(item => item.name);
                }
                if (res.data.permissions) {
                    this.permissions = (res.data.permissions || []).map(item => item.name);
                }
            }).catch(err => {
                console.log(err.response);
                this.$emit("error", err.response);
            });

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
            },
            userroles: {
                type: [Array, Object],
                default: []
            },
            data_url: {
                type: String,
                default: "/api/user/data"
            },
        },
        data() {
            return {
                saving: false,
                alertShow: false,
                alertType: 'primary',
                alertMessage: '',
                temp_role: null,
                roles: [],
                permissions: [],
                form: {
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    confirmPassword: '',
                    roles: [],
                    permissions: [],
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
                },
                roles: {
                    required
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
          goBack() {
            window.history.back();
          },
            validateState(name) {
                const { $dirty, $error } = this.$v.form[name];
                return $dirty ? !$error : null;
            },
            load() {
                if (this.user.id !== undefined) {
                    this.form.id = this.user.id;
                    this.form.name = this.user.name;
                    this.form.email = this.user.email;
                    this.form.roles = Object.keys(this.userroles).map(key => {return this.userroles[key]; })
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
                        password: this.form.password,
                        roles: this.form.roles
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

<style type="text/css">
.role-select{ margin-bottom: 5px; }
</style>
