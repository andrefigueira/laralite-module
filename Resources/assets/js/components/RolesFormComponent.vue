<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="admin-title">
                    {{ type === 'create' ? 'Create new role' : 'Edit role ' }}
                    <strong v-show="type === 'edit'">{{ role.name }}</strong>
                </h2>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <b-form-group id="role-name-group" label="Role name" label-for="role-name-input">
                                <b-form-input
                                    id="role-name-input"
                                    required
                                    v-model="form.name"
                                    :state="validateState('name')"
                                    placeholder="Enter role name"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid name with more than 3 characters</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <b-form-group id="role-guard-group" label="Role guard name" label-for="role-guard-name-input">
                                <b-form-input
                                    id="role-guard-name-input"
                                    required
                                    v-model="form.guard_name"
                                    :state="validateState('guard_name')"
                                    placeholder="Enter role guard name"
                                    autocapitalize="none"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid guard name</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                         <div class="col-md-6">
                            <b-form-group id="role-permissions-group" label="Role permissions" label-for="role-permissions-name-input">
                                <b-form-checkbox-group
                                    id="role-permissions-name-input"
                                    v-model="form.permissions"
                                    :options="permissions"
                                ></b-form-checkbox-group>
                                <b-form-invalid-feedback>Enter a valid guard name</b-form-invalid-feedback>
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

            axios.post(this.data_url, {
                permissions: this.permissions.length <= 0 ? ["id", "name"] : false,
            }).then(res => {
                if (res.data.permissions) {
                    this.permissions = (res.data.permissions || []).map(item => item.name);
                }
            }).catch(err => {
                console.log(err.response);
                this.$emit("error", err.response);
            });

            this.load();
        },
        props: {
            type: {
                type: String,
                default: 'create'
            },
            role: {
                type: Object,
                default: {}
            },
            rolepermissions: {
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
                permissions: [],
                form: {
                    id: '',
                    name: '',
                    guard_name: '',
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
                guard_name: {
                    required,
                    minLength: minLength(3)
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
                let endpoint = '/api/roles';

                if (this.type === 'edit') {
                    endpoint = '/api/roles/' + this.role.id;
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
                if (this.role.id !== undefined) {
                    this.form.id = this.role.id;
                    this.form.name = this.role.name;
                    this.form.guard_name = this.role.guard_name;
                    this.form.permissions = Object.keys(this.rolepermissions).map(key => {return this.rolepermissions[key]; });
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
                        guard_name: this.form.guard_name,
                        permissions: this.form.permissions
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('role-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/roles');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to role';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Role already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Role already exists!';

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
                    this.alertMessage = 'Failed to create role try again later';
                });
            }
        }
    }
</script>
