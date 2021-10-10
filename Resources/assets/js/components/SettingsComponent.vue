<template>
    <div>
      <div class="row">
            <div class="col-md-12">
                <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
            </div>

            <div class="col-md-12">
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


          <Tabs>
            <TabItem name="Finance Settings">
              <div class="row p-2">
              <div class="col-md-6">
                <div class="page-section pl-2 pr-2">
                  <label for="currency-option" class="mt-2" style="font-weight: bold">Currency</label>
                  <v-select class="mb-3" id="currency-option" label="title" v-model="settings.currency" :options="currencyOptions" :clearable="false"></v-select>
                </div><!-- End page section -->
              </div><!-- End col -->
              <div class="col-md-6">
                <div class="page-section pl-2 pr-2 pb-2">
                  <label for= "connected-stripe-account" class="mt-2" style="font-weight: bold">Connected Stripe Account</label>
                  <b-form-input id="connected-stripe-account" v-model="settings.connectedStripeAccount" placeholder="Connected Stripe Account ID"></b-form-input>
                  <br/>
                  <a @click="connectStripeAccount()" class="btn btn-primary" style="width:100%;"><i class="fas fa-external-link-alt"></i> CONNECT STRIPE ACCOUNT</a>

                  <div class="mt-4" v-if="settings.connectedStripeAccount !== ''">
                    <b-form-checkbox
                        id="feeActive"
                        v-model="settings.feeActive"
                        name="feeActive"
                        :value="true"
                        :unchecked-value="false">
                      Activate fee take
                    </b-form-checkbox>

                    <label class="mt-2">Fee Amount</label>
                    <b-form-input v-model="settings.feeAmount" placeholder="100"></b-form-input>
                  </div>
                </div><!-- End page section -->
              </div><!-- End col -->
              </div>
            </TabItem>

            <TabItem name="Site Settings">
              <div class="row pl-2 pr-2 pb-2">
                <div class="col-md-6">
                  <div class="page-section pl-2 pr-2">
                    <label for="buttonfont-option" class="mt-2" style="font-weight: bold">Buttons Font</label>
                <v-select class="mb-3" id="buttonfont-option" label="title" v-model="settings.buttonsFont"
                          :options="fonts" :clearable="false"></v-select>

                    <label for="font-option" class="mt-2" style="font-weight: bold">Header Footer Font</label>
                <v-select class="mb-3" id="font-option" label="title" v-model="settings.headerFooterFont"
                          :options="fonts" :clearable="false"></v-select>
                  </div><!-- End page section -->
                </div>

                <div class="col-md-6">
                  <div class="page-section pl-2 pr-2">
                    <label for="textfont-option" class="mt-2" style="font-weight: bold">Main Text Font</label>
                <v-select class="mb-3" id="textfont-option" label="title" v-model="settings.mainTextFont"
                          :options="fonts" :clearable="false"></v-select>

                    <label for="parafont-option" class="mt-2" style="font-weight: bold">Paragraph Font</label>
                <v-select class="mb-3" id="parafont-option" label="title" v-model="settings.paragraphFont"
                          :options="fonts" :clearable="false"></v-select>
                  </div><!-- End page section -->
                </div><!-- End col -->
                <div class="col-md-6 mt-2">
                  <div class="page-section p-2">
                    <label  style="font-weight: bold">Site Logo</label>
                    <image-upload-component class="mt-2" v-model="settings.siteLogo" height="100px" @image-removed="removeUploadedImage" @image-uploaded="setUploadedImage"></image-upload-component>
                  </div>
                </div>

              <div class="col-md-6 mt-2">
                <div class="page-section pl-2 pb-2">
                <label class="mt-2" style="font-weight: bold">Color</label><br />

                <label class="mt-2 pl-2">Primary Button</label>
                <div class="row pl-3">
                  <div class="px-2" :style="primary">{{primary.backgroundColor}}</div>
                  <color-picker v-model="settings.buttonPrimaryColor" @onOpen="onOpen" @onClose="onClose"/>
                </div>

                <label class="mt-2 pl-2" style="">Secondary Button</label>
                <div class="row pl-3">
                  <div class="px-2" :style="secondary">{{secondary.backgroundColor}}</div>
                  <color-picker v-model="settings.buttonSecondaryColor" @onOpen="onOpen" @onClose="onClose"/>
                </div>

                <label class="mt-2 pl-2">Text Primary</label>
                <div class="row pl-3">
                  <div class="px-2" :style="textPrimary">{{ textPrimary.backgroundColor }}</div>
                  <color-picker v-model="settings.textPrimaryColor" @onOpen="onOpen" @onClose="onClose">Text Primary</color-picker>
                </div>

                <label class="mt-2 pl-2">Text Highlight</label>
                <div class="row pl-3">
                  <div class="px-2" :style="textHighlight">{{ textHighlight.backgroundColor }}</div>
                  <color-picker v-model="settings.textHighlightColor" @onOpen="onOpen" @onClose="onClose"/>
                </div>
                </div>
              </div>

              </div>

            </TabItem>
          </Tabs>
<!--        <div>-->
<!--          <b-tabs content-class="mt-3">-->
<!--            <b-tab title="First" active><p>I'm the first tab</p></b-tab>-->
<!--            <b-tab title="Second"><p>I'm the second tab</p></b-tab>-->
<!--            <b-tab title="Disabled" disabled><p>I'm a disabled tab!</p></b-tab>-->
<!--          </b-tabs>-->
<!--        </div>-->

            <div class="col-md-12 mt-2">
                <div class="page-section p-4">
                    <button class="btn btn-success" :disabled="saving" @click="save()">Save Changes</button>
                </div>
            </div>
        </div>
    </div><!-- End row -->
</template>

<script>

import {minLength, required} from "vuelidate/lib/validators";
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
                    feeAmount: '',
                    siteLogo: '',
                    buttonPrimaryColor: "#36A3B8",
                    buttonSecondaryColor: "#28a745",
                    textPrimaryColor: "#FFFFFF",
                    textHighlightColor: "#FFD700",
                    buttonsFont: "Acme",
                    headerFooterFont: "Padauk",
                    mainTextFont: "Cinzel",
                    paragraphFont: "Gabriela"
                },
                maintenance: false,
                currencyOptions: [
                    {
                        title: '$ US Dollar',
                        value: 'USD',
                        currency_symbol: '$'
                    },
                    {
                        title: '£ UK Sterling',
                        value: 'GBP',
                      currency_symbol: '£'
                    }
                ],
      fonts: [
        "Cinzel",
        "Pacifico",
        "Padauk",
        'Arial',
        'Arial Black',
        'Bahnschrift',
        'Calibri',
        'Cambria',
        'Cambria Math',
        'Candara',
        'Comic Sans MS',
        'Consolas',
        'Constantia',
        'Corbel',
        'Courier New',
        'Ebrima',
        'Franklin Gothic Medium',
        'Gabriola',
        'Gadugi',
        'Georgia',
        'HoloLens MDL2 Assets',
        'Impact',
        'Ink Free',
        'Javanese Text',
        'Leelawadee UI',
        'Lucida Console',
        'Lucida Sans Unicode',
        'Malgun Gothic',
        'Marlett',
        'Microsoft Himalaya',
        'Microsoft JhengHei',
        'Microsoft New Tai Lue',
        'Microsoft PhagsPa',
        'Microsoft Sans Serif',
        'Microsoft Tai Le',
        'Microsoft YaHei',
        'Microsoft Yi Baiti',
        'MingLiU-ExtB',
        'Mongolian Baiti',
        'MS Gothic',
        'MV Boli',
        'Myanmar Text',
        'Nirmala UI',
        'Palatino Linotype',
        'Segoe MDL2 Assets',
        'Segoe Print',
        'Segoe Script',
        'Segoe UI',
        'Segoe UI Historic',
        'Segoe UI Emoji',
        'Segoe UI Symbol',
        'SimSun',
        'Sitka',
        'Sylfaen',
        'Symbol',
        'Tahoma',
        'Times New Roman',
        'Trebuchet MS',
        'Verdana',
        'Webdings',
        'Wingdings',
        'Yu Gothic'
      ],
              buttonOptions: ["Cinzel","Pacifico","Acme"],
              headerfooterOptions: ["Padauk","Cinzel","Oswald"],
              textOptions: ["Acme","Oswald","Gabriela"],
              paraOptions: ["Cinzel","Pacifico","Padauk"],
            }
        },
        computed: {
          primary: function () {
            return {
              backgroundColor: this.settings.buttonPrimaryColor,
              height: '25px',
              width: '100px'
            }
          },
          secondary: function () {
            return {
              backgroundColor: this.settings.buttonSecondaryColor,
              height: '25px',
              width: '100px'
            }
          },
          textPrimary: function () {
            return {
              backgroundColor: this.settings.textPrimaryColor,
              height: '25px',
              width: '100px'
            }
          },
          textHighlight: function () {
            return {
              backgroundColor: this.settings.textHighlightColor,
              height: '25px',
              width: '100px'
            }
          },
        },
        methods: {
          onOpen() {
            console.log("open");
          },
          onClose(color) {
            console.log("close", color);
          },
          setUploadedImage(path) {
            this.settings.siteLogo = path;
          },
          removeUploadedImage() {
            this.settings.siteLogo = '';
          },
            connectStripeAccount () {
                window.open('https://connect.stripe.com/oauth/v2/authorize?response_type=code&client_id=ca_IEPc14SDbZBo3sYKSYnRAl733RTB6oPM&scope=read_write&redirect_uri=http://trapmusicmuseum.test/api/stripe-connect');
            },
            load () {
                const settingsObject = JSON.parse(this.currentSettings)
                if (settingsObject.currency) {
                    this.settings.currency = settingsObject.currency;
                }
                if (settingsObject.connectedStripeAccount) {
                    this.settings.connectedStripeAccount = settingsObject.connectedStripeAccount;
                }
                if (settingsObject.feeActive) {
                    this.settings.feeActive = settingsObject.feeActive;
                }
                if (settingsObject.feeAmount) {
                    this.settings.feeAmount = settingsObject.feeAmount;
                }
                if (settingsObject.siteLogo) {
                    this.settings.siteLogo = settingsObject.siteLogo;
                }
              if (settingsObject.buttonPrimaryColor) {
                this.settings.buttonPrimaryColor = settingsObject.buttonPrimaryColor;
              }
              if (settingsObject.buttonSecondaryColor) {
                this.settings.buttonSecondaryColor = settingsObject.buttonSecondaryColor;
              }
              if (settingsObject.textPrimaryColor) {
                this.settings.textPrimaryColor = settingsObject.textPrimaryColor;
              }
              if (settingsObject.textHighlightColor) {
                this.settings.textHighlightColor = settingsObject.textHighlightColor;
              }
              if (settingsObject.buttonsFont) {
                this.settings.buttonsFont = settingsObject.buttonsFont;
              }
              if (settingsObject.headerFooterFont) {
                this.settings.headerFooterFont = settingsObject.headerFooterFont;
              }
              if (settingsObject.mainTextFont) {
                this.settings.mainTextFont = settingsObject.mainTextFont;
              }
              if (settingsObject.paragraphFont) {
                this.settings.paragraphFont = settingsObject.paragraphFont;
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

<style scoped>
 .ew-cp-trigger {
   height: 25px !important;
   border: none !important;
   border-radius: initial !important;
   width: initial !important;
   align-items: initial !important;
   display: revert !important;
 }
 .ew-cp-panel {
   z-index: 15 !important;
 }
 .tabs {
   margin-top: 10px !important;
 }
 .tabs__content {
   padding-bottom: 10px !important;
 }

</style>
