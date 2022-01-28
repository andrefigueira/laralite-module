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
              v-model="settings.maintenanceActive"
              name="checkbox-1"
              :value="true"
              :unchecked-value="false">
            Maintenance Mode
          </b-form-checkbox>
        </div>
      </div><!-- End col -->

      <Tabs>
        <TabItem>
          <template #name>
            <span>Finance Settings</span>
          </template>

          <div class="row p-3 pb-5">
            <div class="col-md-3">
              <div class="page-section pl-2 pr-2">
                <label for="currency-option" class="mt-2" style="font-weight: bold">Currency</label>
                <v-select class="mb-3" id="currency-option" label="title" v-model="settings.currency"
                          :options="currencyOptions" :clearable="false"></v-select>
              </div><!-- End page section -->
            </div><!-- End col -->
            <div class="col-md-9">
              <div class="page-section p-2">
                <div class="mt-2" style="display: inline-block"><strong>Connected Stripe Account</strong></div>
                <a v-b-modal.secretKeyInfo class="btn btn-primary mb-2 float-right"
                   v-if="settings.stripeSecretKey === ' '"><i class="fas fa-external-link-alt"></i>CONNECT STRIPE
                  ACCOUNT</a>
                <a v-b-modal.secretKeyInfo class="btn btn-primary mb-2 float-right" v-else><i
                    class="fas fa-external-link-alt"></i>RECONNECT STRIPE ACCOUNT</a>
                <div class="pt-4 mt-1 pl-2" v-if="settings.stripeSecretKey">
                  <div>
                    <p><strong>Client Id:</strong> {{ settings.stripeClientId }}</p>
                    <p><strong>Secret Key:</strong> {{ hideSecretKey(settings.stripeSecretKey) }}</p>
                    <p><strong>Access Token:</strong> {{ hideSecretKey(settings.stripeAccessToken) }}</p>
                    <p><strong>Account Id:</strong> {{ settings.stripeAccountId }}</p>
                    <p><strong>Live Account:</strong> {{ settings.stripeLiveAccount }}</p>
                    <p><strong>Publisher Key:</strong> {{ settings.stripePublishKey }}</p>
                  </div>
                </div>
                <div class="mt-4" v-if="settings.stripeSecretKey !== ''">
                  <div class="row">
                    <div class="col-md-4 col-sm-12">
                      <b-form-checkbox
                          id="feeActive"
                          v-model="settings.feeActive"
                          name="feeActive"
                          :value="true"
                          :unchecked-value="false">
                        Activate Fee take
                      </b-form-checkbox>
                      <label class="mt-2">Fee Amount <span style="color: darkgray; font-size: 10px">(In Percent)</span></label>
                      <b-form-input v-model="settings.feeAmount" placeholder="10%"></b-form-input>
                    </div>

                    <div class="col-md-4 col-sm-12">
                      <b-form-checkbox
                          id="taxActive"
                          v-model="settings.taxActive"
                          name="taxActive"
                          :value="true"
                          :unchecked-value="false">
                        Activate Tax
                      </b-form-checkbox>
                      <label class="mt-2">Tax Amount <span style="color: darkgray; font-size: 10px">(In Percent)</span></label>
                      <b-form-input v-model="settings.taxAmount" placeholder="10%"></b-form-input>
                    </div>

                    <div class="col-md-4 col-sm-12">
                      <b-form-checkbox
                          id="serviceFeeActive"
                          v-model="settings.serviceFeeActive"
                          name="serviceFeeActive"
                          :value="true"
                          :unchecked-value="false">
                        Activate Service Fee
                      </b-form-checkbox>
                      <label class="mt-2">Service Fee <span style="color: darkgray; font-size: 10px"></span></label>
                      <b-form-input v-model="settings.serviceFeeAmount" placeholder="100"></b-form-input>
                    </div>
                  </div>
                </div>
              </div><!-- End page section -->
            </div><!-- End col -->
          </div>

          <b-modal size="lg" ref="secretKeyInfo" id="secretKeyInfo" title="Connected Stripe Account" class="mt-5"
                   hide-footer no-close-on-backdrop>
            <div>
              <label for="connected-stripe-clientId" class="mt-1" style="font-weight: bold">Stripe Account Client
                Id</label>
              <a target="_blank" class="float-right mt-2" style="font-size: 10px; cursor: pointer"
                 href="https://docs.cs-cart.com/latest/user_guide/addons/stripe_connect/credentials.html#getting-client-id-and-defining-redirect-uri"><i
                  class="fas fa-external-link-alt"></i>Find your StripeClient Id</a>
              <b-form-input id="connected-stripe-clientId" v-model="settings.stripeClientId"
                            placeholder="Connected Stripe Client ID"></b-form-input>
                </br>
              <label for="connected-stripe-account" class="mt-1" style="font-weight: bold">Stripe Account Secret
                Key</label>
              <a target="_blank" class="float-right mt-3" style="font-size: 10px; cursor: pointer"
                 href="https://docs.cs-cart.com/latest/user_guide/addons/stripe_connect/credentials.html#getting-publishable-key-and-secret-key"><i
                  class="fas fa-external-link-alt"></i>Find your Secret Key</a>
              <b-form-input id="connected-stripe-account" v-model="settings.stripeSecretKey"
                            placeholder="Connected Stripe Account ID"></b-form-input>
              </br>
              <label for="connected-stripe-publishkey" class="mt-1" style="font-weight: bold">Stripe Account Publish
                Key</label>
              <a target="_blank" class="float-right mt-3" style="font-size: 10px; cursor: pointer"
                 href="https://docs.cs-cart.com/latest/user_guide/addons/stripe_connect/credentials.html#getting-publishable-key-and-secret-key"><i
                  class="fas fa-external-link-alt"></i>Find your Publish Key</a>
              <b-form-input id="connected-stripe-account" v-model="settings.stripePublishKey"
                            placeholder="Connected Stripe Publish Key"></b-form-input>
              <b-button class="mt-2" variant="warning" block @click="connectStripeAccount()"
                        :disabled="settings.stripeSecretKey.length == 0 && settings.stripeClientId.length == 0 && settings.stripePublishKey.length == 0">Connect
                Stripe Account
              </b-button>
              <b-button class="mt-3" block @click="hideSecretKeyInfo">Exit</b-button>
            </div>
          </b-modal>

        </TabItem>

        <TabItem>
          <template #name>
            <span>Site Settings</span>
          </template>

          <div class="row pl-2 pr-2 pb-2">
            <div class="col-md-6">
              <div class="page-section pl-2 pr-2">
                <div id="font-picker"></div>
                <label for="buttonfont-option" class="mt-2" style="font-weight: bold">Buttons Font</label>
                <v-select class="mb-3" id="buttonfont-option" label="title" v-model="settings.buttonsFont"
                          :clearable="false"
                          :options="fonts">
                  <template v-slot:option="font">
                    <span :style="'font-family: ' + font.title">
                      {{ font.title }}
                    </span>
                  </template>
                  <template #selected-option="font">
                    <span :style="'font-family: ' + settings.buttonsFont">
                      {{ settings.buttonsFont }}
                    </span>
                  </template>
                </v-select>

                <label for="font-option" class="mt-2" style="font-weight: bold">Header Footer Font</label>
                <v-select class="mb-3" id="buttonfont-option" label="title" v-model="settings.headerFooterFont"
                          :clearable="false"
                          :options="fonts">
                  <template v-slot:option="font">
                    <span :style="'font-family: ' + font.title">
                      {{ font.title }}
                    </span>
                  </template>
                  <template #selected-option="font">
                    <span :style="'font-family: ' + settings.headerFooterFont">
                      {{ settings.headerFooterFont }}
                    </span>
                  </template>
                </v-select>

              </div><!-- End page section -->
            </div>

            <div class="col-md-6">
              <div class="page-section pl-2 pr-2">
                <label for="textfont-option" class="mt-2" style="font-weight: bold">Main Text Font</label>
                <v-select class="mb-3" id="textfont-option" label="title" v-model="settings.mainTextFont"
                          :options="fonts" :clearable="false">
                  <template v-slot:option="font">
                    <span :style="'font-family: ' + font.title">
                     {{ font.title }}
                    </span>
                  </template>
                  <template #selected-option="font">
                    <span :style="'font-family: ' + settings.mainTextFont">
                      {{ settings.mainTextFont }}
                    </span>
                  </template>
                </v-select>

                <label for="parafont-option" class="mt-2" style="font-weight: bold">Paragraph Font</label>
                <v-select class="mb-3" id="parafont-option" label="title" v-model="settings.paragraphFont"
                          :options="fonts" :clearable="false">
                  <template v-slot:option="font">
                    <span :style="'font-family: ' + font.title">
                     {{ font.title }}
                    </span>
                  </template>
                  <template #selected-option="font">
                    <span :style="'font-family: ' + settings.paragraphFont">
                     {{ settings.paragraphFont }}
                    </span>
                  </template>
                </v-select>
              </div><!-- End page section -->
            </div><!-- End col -->
            <div class="col-md-6 mt-2">
              <div class="page-section p-2">
                <label style="font-weight: bold">Site Logo</label>
                <image-upload-component class="mt-2" v-model="settings.siteLogo" height="100px"
                                        @image-removed="removeUploadedImage"
                                        @image-uploaded="setUploadedImage"></image-upload-component>
              </div>
            </div>

            <div class="col-md-6 mt-2">
              <div class="page-section pl-2 pb-2">
                <label class="mt-2" style="font-weight: bold">Color</label><br/>

                <label class="mt-2 pl-2">Primary Button</label>
                <div class="row pl-3">
                  <div class="px-2" :style="primary">{{ primary.backgroundColor }}</div>
                  <color-picker v-model="settings.buttonPrimaryColor" @onOpen="onOpen" @onClose="onClose"/>
                </div>

                <label class="mt-2 pl-2" style="">Secondary Button</label>
                <div class="row pl-3">
                  <div class="px-2" :style="secondary">{{ secondary.backgroundColor }}</div>
                  <color-picker v-model="settings.buttonSecondaryColor" @onOpen="onOpen" @onClose="onClose"/>
                </div>

                <label class="mt-2 pl-2">Text Primary</label>
                <div class="row pl-3">
                  <div class="px-2" :style="textPrimary">{{ textPrimary.backgroundColor }}</div>
                  <color-picker v-model="settings.textPrimaryColor" @onOpen="onOpen" @onClose="onClose">Text Primary
                  </color-picker>
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
      hideDetails: true,
      connectedStripePublishKey: '',
      settings: {
        currency: '$ US Dollar',
        stripeSecretKey: '',
        stripeClientId: '',
        stripeAccountId: '',
        stripeLiveAccount: '',
        stripePublishKey: '',
        feeActive: false,
        feeAmount: '',
        taxActive: false,
        taxAmount: '',
        serviceFeeActive: false,
        serviceFeeAmount: '',
        siteLogo: '',
        buttonPrimaryColor: "#36A3B8",
        buttonSecondaryColor: "#28a745",
        textPrimaryColor: "#FFFFFF",
        textHighlightColor: "#FFD700",
        buttonsFont: "Acme",
        headerFooterFont: "Padauk",
        mainTextFont: "Cinzel",
        paragraphFont: "Gabriela",
        maintenanceActive: false,
      },
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
        "Arial",
        "Gadugi",
        "Palatino",
        "Garamond",
        "Bookman",
        "Avant Garde",
        "Comic Sans MS",
        "Trebuchet MS",
        "Arial Black",
        "Georgia",
        "Verdana",
        "Times",
        "Courier",
        "Impact",
        "Oswald",
        "Gabriela",
        "Padauk",
        "Acme",
        "Pacifico"
      ],
      buttonOptions: ["Cinzel", "Pacifico", "Acme"],
      headerfooterOptions: ["Padauk", "Cinzel", "Oswald"],
      textOptions: ["Acme", "Oswald", "Gabriela"],
      paraOptions: ["Cinzel", "Pacifico", "Padauk"],
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
    hideSecretKey: function (secretKey) {
      return secretKey.replace(/(.{10})(.*)(?=.2)/,
          function (gp1, gp2, gp3) {
            for (let i = 0; i < gp3.length; i++) {
              gp2 += "*";
            }
            return gp2;
          })
    },
    setSelected(value) {
      this.settings.buttonsFont = value;
    },
    hideSecretKeyInfo() {
      this.$refs['secretKeyInfo'].hide();
    },
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
    connectStripeAccount() {
      this.saving = true;

      axios({
        method: 'patch',
        url: '/api/settings',
        data: this.settings
      }).then(response => {
        this.saving = false;
        this.alertShow = true;
        this.alertMessage = 'Saved Stripe Account Details';
        this.alertType = 'success';
        this.hideSecretKeyInfo();
        window.open('https://connect.stripe.com/oauth/v2/authorize?response_type=code&client_id=' + this.settings.stripeClientId + '&scope=read_write&redirect_uri=' + process.env.MIX_APP_URL + '/api/stripe-connect');
      }).catch(error => {
        this.saving = false;
        this.alertShow = true;
        this.alertType = 'danger';
        this.alertMessage = 'Failed to save Stripe Account Details';
      });
    },
    load() {
      const settingsObject = JSON.parse(this.currentSettings)
      if (settingsObject.currency) {
        this.settings.currency = settingsObject.currency;
      }
      if (settingsObject.stripeSecretKey) {
        this.settings.stripeSecretKey = settingsObject.stripeSecretKey;
      }
      if (settingsObject.stripeAccountId) {
        this.settings.stripeAccountId = settingsObject.stripeAccountId;
      }
      if (settingsObject.stripeAccessToken) {
        this.settings.stripeAccessToken = settingsObject.stripeAccessToken;
      }
      if (settingsObject.stripeClientId) {
        this.settings.stripeClientId = settingsObject.stripeClientId;
      }
      this.settings.stripeLiveAccount = settingsObject.stripeLiveAccount ? 'Yes' : 'No';

      if (settingsObject.stripePublishKey) {
        this.settings.stripePublishKey = settingsObject.stripePublishKey;
      }
      if (settingsObject.feeActive) {
        this.settings.feeActive = settingsObject.feeActive;
      }
      if (settingsObject.feeAmount) {
        this.settings.feeAmount = settingsObject.feeAmount;
      }
      if (settingsObject.taxActive) {
        this.settings.taxActive = settingsObject.taxActive;
      }
      if (settingsObject.taxAmount) {
        this.settings.taxAmount = settingsObject.taxAmount;
      }
      if (settingsObject.serviceFeeActive) {
        this.settings.serviceFeeActive = settingsObject.serviceFeeActive;
      }
      if (settingsObject.serviceFeeAmount) {
        this.settings.serviceFeeAmount = settingsObject.serviceFeeAmount;
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
      if (settingsObject.maintenanceActive) {
        this.settings.maintenanceActive = settingsObject.maintenanceActive;
      }
      var connectedAccount = this.getUrlParameter('connectedAccountId');
      if (connectedAccount !== false) {
        this.settings.stripeSecretKey = connectedAccount;
      }
    },
    getUrlParameter(sParam) {
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

    maintenance() {
      axios({
        method: 'patch',
        url: '/api/settings/',
      })
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
