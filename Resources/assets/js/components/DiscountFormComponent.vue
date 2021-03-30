<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="admin-title-section">
                    <h2 class="admin-title">
                        {{ type === 'create' ? 'Create new discount' : 'Edit discount ' }}
                        <strong v-show="type === 'edit'">{{ discount.name }}</strong>
                    </h2>
                </div>

                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div><!-- End col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-section p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <b-form-group id="discount-name-group" label="Discount name" label-for="discount-name">
                                <b-form-input
                                    id="discount-name-input"
                                    required
                                    v-model="name"
                                    placeholder="Enter discount name"
                                ></b-form-input>
                            </b-form-group>
                          </div>
                          <div class="col-md-6">
                            <b-form-group id="discount-code-group" label="Discount code" label-for="discount-code">
                                <b-form-input
                                    id="discount-code-input"
                                    required
                                    v-model="code"
                                    placeholder="Enter discount code"
                                ></b-form-input>
                            </b-form-group>
                            </div>
                      <div class="col-md-6">
                            <b-form-group id="discount-type-group" label="Discount type" label-for="discount-type">
                                <b-form-select v-model="discountType" :options="typeOptions"></b-form-select>
                            </b-form-group>
                        </div>
                      <div class="col-md-6">
                            <b-form-group id="discount-value-group" label="Discount value" label-for="discount-value">
                                <b-form-input
                                    id="discount-value-input"
                                    required
                                    type="number"
                                    v-model="value"
                                    placeholder="Enter discount value"
                                ></b-form-input>
                            </b-form-group>
                              </div>
                      <div class="col-md-12">
                            <button class="btn btn-theme" :disabled="saving" @click="save()">{{ button }}</button>
                      </div>
                        </div><!-- End col -->
                    </div><!-- End row -->
                </div><!-- End row -->
            </div><!-- End col -->
        </div><!-- End row -->
</template>

<script>
import { bus } from '../admin'
import helpers from '../helpers'

export default {
    mounted() {
        console.log('Component mounted.');

        this.load();
    },
    props: {
        type: {
            type: String,
            default: 'create'
        },
        discount: {
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
            code: '',
            discountType: null,
            value: 0,
            typeOptions: [
                {
                    value: null,
                    text: 'Please select type'
                },
                {
                    value: 'percent',
                    text: 'Percent'
                },
                {
                    value: 'fixed',
                    text: 'Fixed value'
                }
            ]
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
            let endpoint = '/api/discount';

            if (this.type === 'edit') {
                endpoint = '/api/discount/' + this.discount.id;
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
        load() {
            if (this.discount.id !== undefined) {
                this.id = this.discount.id;
                this.name = this.discount.name;
                this.code = this.discount.code;
                this.discountType = this.discount.type;
                this.value = this.discount.value;
            }
        },
        save() {
            this.saving = true;

            axios({
                method: this.formMethod,
                url: this.formEndpoint,
                data:  {
                    name: this.name,
                    code: this.code,
                    type: this.discountType,
                    value: this.value
                }
            }).then(response => {
                this.saving = false;

                bus.$emit('discount-created', response.data.data);

                if (this.type === 'create') {
                    window.location.replace('/admin/discounts');
                }

                this.alertShow = true;
                this.alertMessage = 'Saved changes to discount';
                this.alertType = 'success';
            }).catch(error => {
                this.saving = false;

                // 409 status code: conflict, i.e. already exists in system
                if (error.response.status === 409) {
                    console.log('Discount already exists in system');

                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Discount already exists!';

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
                this.alertMessage = 'Failed to create discount try again later';
            });
        },
    }
}
</script>
