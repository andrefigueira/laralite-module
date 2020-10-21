<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="admin-title">
                    {{ type === 'create' ? 'Create new permission' : 'Edit permission ' }}
                    <strong v-show="type === 'edit'">{{ permission.name }}</strong>
                </h2>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <b-form-group id="permission-name-group" label="Permission name" label-for="permission-name-input">
                                <b-form-input
                                    id="permission-name-input"
                                    required
                                    v-model="form.name"
                                    :state="validateState('name')"
                                    placeholder="Enter permission name"
                                ></b-form-input>
                                <b-form-invalid-feedback>Enter a valid name with more than 3 characters</b-form-invalid-feedback>
                            </b-form-group>
                        </div><!-- End col -->
                        <div class="col-md-6">
                            <b-form-group id="permission-guard-group" label="Permission guard name" label-for="permission-guard-name-input">
                                <b-form-input
                                    id="permission-guard-name-input"
                                    required
                                    v-model="form.guard_name"
                                    :state="validateState('guard_name')"
                                    placeholder="Enter permission guard name"
                                    autocapitalize="none"
                                ></b-form-input>
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

            this.load();
        },
        props: {
            type: {
                type: String,
                default: 'create'
            },
            permission: {
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
                    guard_name: ''
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
                let endpoint = '/api/permissions';

                if (this.type === 'edit') {
                    endpoint = '/api/permissions/' + this.permission.id;
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
                if (this.permission.id !== undefined) {
                    this.form.id = this.permission.id;
                    this.form.name = this.permission.name;
                    this.form.guard_name = this.permission.guard_name;
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
                        guard_name: this.form.guard_name
                    }
                }).then(response => {
                    this.saving = false;

                    bus.$emit('permission-created', response.data.data);

                    if (this.type === 'create') {
                        window.location.replace('/admin/permissions');
                    }

                    this.alertShow = true;
                    this.alertMessage = 'Saved changes to permission';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    // 409 status code: conflict, i.e. already exists in system
                    if (error.response.status === 409) {
                        console.log('Permission already exists in system');

                        this.alertShow = true;
                        this.alertType = 'danger';
                        this.alertMessage = 'Permission already exists!';

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
                    this.alertMessage = 'Failed to create permission try again later';
                });
            }
        }
    }
</script>
