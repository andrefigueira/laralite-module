<template>
    <div>
        <div class="row">
            <div class="col-12">
                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div>

            <div class="col-12">
                <div class="page-section p-4">
                    <b-form-checkbox
                        id="checkbox-1"
                        v-model="maintenance"
                        name="checkbox-1"
                        :value="true"
                        :unchecked-value="false">
                        Maintenance Mode
                    </b-form-checkbox>
                </div>
            </div><!-- End col -->
            <div class="col-3">
                <div class="page-section p-4 mt-2">
                    <label for="currency-option" class="mt-3">Currency</label>
                    <v-select class="mb-3" id="currency-option" label="title" v-model="settings.currency" :options="currencyOptions" :clearable="false"></v-select>
                </div><!-- End page section -->
            </div><!-- End col -->
            <div class="col-3">
                <div class="page-section p-4 mt-2">
                    <label for="connected-stripe-account" class="mt-3">Connected Stripe Account</label>
                    <b-form-input id="connected-stripe-account" v-model="settings.connectedStripeAccount" placeholder="Connected Stripe Account ID"></b-form-input>
                    <b-button @click="connectStripeAccount()" variant="outline-secondary" style="width:100%;"><i class="fas fa-external-link-alt"></i> CONNECT STRIPE ACCOUNT</b-button>
                    
                    <div class="mt-4" v-if="settings.connectedStripeAccount !== ''">
                        <b-form-checkbox
                            id="feeActive"
                            v-model="settings.feeActive"
                            name="feeActive"
                            :value="true"
                            :unchecked-value="false">
                            Activate fee take
                        </b-form-checkbox>             

                        <label class="mt-3">Fee Amount</label>
                        <b-form-input v-model="settings.feeAmount" placeholder="100"></b-form-input>
                    </div>
                </div><!-- End page section -->
            </div><!-- End col -->

            <div class="col-12 mt-2">
                <div class="page-section p-4">
                    <button class="btn btn-success" :disabled="saving" @click="save()">Save Changes</button>
                </div>
            </div>
        </div><!-- End row -->
    </div>
</template>

<script>
    import VueRouter from 'vue-router'

    export default {
        mounted() {
            console.log('Component mounted.');
            this.load();
        },
        props: ['currentSettings'],
        data() {
            return {
                saving: false,
                alertShow: false,
                alertType: 'primary',
                alertMessage: '',
                currentSettings: this.currentSettings,
                settings: {
                    currency: '$ US Dollar',
                    connectedStripeAccount: '',
                    feeActive: false,
                    feeAmount: ''
                },
                maintenance: false,
                currencyOptions: [
                    {
                        title: '$ US Dollar',
                        value: 'USD'
                    },
                    {
                        title: '£ UK Sterling',
                        value: 'GBP'
                    }
                ],
            }
        },
        methods: {
            connectStripeAccount () {
                window.open('https://connect.stripe.com/oauth/v2/authorize?response_type=code&client_id=ca_IEPc14SDbZBo3sYKSYnRAl733RTB6oPM&scope=read_write&redirect_uri=http://trapmusicmuseum.test/api/stripe-connect');
            },
            load () {
                if (this.currentSettings.currency) {
                    this.settings.currency = this.currentSettings.currency;
                }
                if (this.currentSettings.connectedStripeAccount) {
                    this.settings.connectedStripeAccount = this.currentSettings.connectedStripeAccount;
                }
                if (this.currentSettings.feeActive) {
                    this.settings.feeActive = this.currentSettings.feeActive;
                }
                if (this.currentSettings.feeAmount) {
                    this.settings.feeAmount = this.currentSettings.feeAmount;
                }
                var connectedAccount = this.getUrlParameter('connectedAccountId');
                if (connectedAccount !== false) {
                    this.settings.connectedStripeAccount = connectedAccount;
                }
            },
            getUrlParameter (sParam) {
                var sPageURL = window.location.search.substring(1),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                    }
                }
                return false;
            },
            save() {
                this.saving = true;

                axios({
                    method: 'patch',
                    url: '/api/settings',
                    data: this.settings
                }).then(response => {
                    this.saving = false;

                    this.alertShow = true;
                    this.alertMessage = 'Saved settings';
                    this.alertType = 'success';
                }).catch(error => {
                    this.saving = false;

                    this.alertShow = true;
                    this.alertType = 'danger';
                    this.alertMessage = 'Failed to save settings';
                });
            }
        }
    }
</script>
