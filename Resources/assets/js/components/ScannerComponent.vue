<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <b-button class="btn btn-lg float-right" variant="warning" @click="turnCameraOn()"
                  style="margin: 8px; font-size: 18px; ">
          <i v-b-tooltip:hover title="Refresh" class="ri-restart-line" style="font-size: 18px;"></i>
        </b-button>
      </div>
      <div class="col-md-12">
        <qrcode-stream :camera="camera" @decode="onDecode" @init="onInit">
        </qrcode-stream>
        <div v-if="validationPending" class="validation-pending">
          <b-modal size="lg" ref="reedemTicket" id="reedemTicket" hide-footer no-close-on-backdrop
                   @hide="ticket = null">
            <template #modal-header>
              <h5 v-if="ticket.validated === 1">Already Reedemed</h5>
              <h5 v-else>Reedem Ticket</h5>
            </template>

            <template v-if="ticket !== null">
              <table class="table table-bordered">
                <tr>
                  <th scope="row">Order Number</th>
                  <td>{{ ticket.order.unique_id }}</td>
                </tr>
                <tr>
                  <th scope="row">Customer Name</th>
                  <td>{{ ticket.order.customer.name }}</td>
                </tr>
                <tr>
                  <th scope="row">Purchase Date</th>
                  <td>{{ timeFormat(ticket.created_at) }}</td>
                </tr>
                <tr v-if="ticket.validated === 1">
                  <th scope="row">Redemption Date</th>
                  <td>{{ timeFormat(ticket.updated_at) }}</td>
                </tr>
                <tr>
                  <th scope="row">Members Per Ticket</th>
                  <td>{{ ticket.admit_quantity }}</td>
                </tr>
                <tr>
                  <th scope="row">Status</th>
                  <td v-if="ticket.status === 'GENERATED'">
                    <b-badge variant="secondary">{{ ticket.status }}</b-badge>
                  </td>
                  <td v-else-if="ticket.status === 'REDEEMED'">
                    <b-badge variant="success">{{ ticket.status }}</b-badge>
                  </td>
                  <td v-else-if="ticket.status === 'UNREDEEMED'">
                    <b-badge variant="primary">{{ ticket.status }}</b-badge>
                  </td>
                  <td v-else-if="ticket.status === 'CANCELLED'">
                    <b-badge variant="danger">{{ ticket.status }}</b-badge>
                  </td>
                </tr>
                <tr>
                  <th scope="row">Status Log</th>
                  <td>
                    <div class="status_log_continaer">
                      <b-table striped hover :items="this.ticket.status_log" :fields="['status', 'date']"></b-table>
                    </div>
                  </td>
                </tr>
              </table>
              <div v-if="actionSuccess === true">
                <p v-if="action === 'REDEEM'">
                  <strong>Successfully Redeemed On:</strong> {{ timeFormat(ticket.updated_at) }}
                </p>
                <p v-if="action === 'UNREDEEM'">
                  <strong>Successfully Unredeemed On:</strong> {{ timeFormat(ticket.updated_at) }}
                </p>
                <p v-if="action === 'CANCEL'">
                  <strong>Ticket has now been cancelled!</strong>
                </p>
              </div>
              <div v-if="actionProcessing === false && actionError === false">
                <template v-if="ticket.order.order_status === 'cancel' && ticket.order.refunded === 1
                || ticket.status === 'CANCELLED' && action !== 'CANCEL'">
                  <p><strong>Ticket is already cancelled or refunded.</strong></p>
                </template>
                <template v-else-if="(ticket.status === 'GENERATED' || ticket.status === 'UNREDEEMED') && ticket.status !== 'CANCELLED'">
                  <b-button class="mt-2"
                            variant="warning"
                            :disabled="actionProcessing === true"
                            block
                            @click="processTicketAction('redeem')">
                    Reedem Ticket
                  </b-button>
                </template>
                <template v-else-if="ticket.status === 'REDEEMED' && ticket.status !== 'CANCELLED'">
                  <b-button class="mt-2"
                            variant="warning"
                            :disabled="actionProcessing === true"
                            block
                            @click="processTicketAction('unredeem')">
                    Unredeem Ticket
                  </b-button>
                </template>
                <template v-if="ticket.status !== 'CANCELLED' && ticket.status !== 'REDEEMED'">
                  <b-button class="mt-2"
                            variant="danger"
                            :disabled="actionProcessing === true"
                            block
                            @click="processTicketAction('cancel')">
                    Cancel Ticket
                  </b-button>
                </template>
              </div>
              <div v-if="actionError === true">
                <p>{{ actionErrorMessage }}</p>
              </div>
              <div v-show="actionProcessing" class="text-center">
                <b-spinner label="Spinning"></b-spinner>
              </div>
              <b-button class="mt-3" block @click="hideReedem">Exit</b-button>
            </template>
            <template v-else>
              <p>Ticket Not Found</p>
              <b-button class="mt-3" block @click="hideReedem">Exit</b-button>
            </template>
          </b-modal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {QrcodeStream, QrcodeDropZone, QrcodeCapture} from 'vue-qrcode-reader'
import * as moment from "moment";

export default {
  data() {
    return {
      camera: 'auto',
      result: '',
      error: false,
      message: '',
      ticket_id: '',
      action: '',
      isValid: undefined,
      actionProcessing: false,
      actionError: false,
      actionSuccess: false,
      actionErrorMessage: '',
      actionSuccessMessage: '',
      ticket: null
    }
  },
  components: {
    QrcodeStream,
    QrcodeDropZone,
    QrcodeCapture
  },
  watch: {
    result(newValue, oldValue) {
      if (newValue != null && newValue !== '') {
        this.result = newValue;
        this.getTicketDetails(this.result);
      }
    },
    ticket(newValue, oldValue) {
      if (newValue !== null) {
        this.$bvModal.show('reedemTicket')
      } else {
        this.result = null
        this.turnCameraOn()
      }
    }
  },
  computed: {
    variant() {
      return this.error ? 'danger' : 'success';
    },

    validationPending() {
      return this.isValid === undefined
          && this.camera === 'off'
    },

    validationSuccess() {
      return this.isValid === true
    },

    validationFailure() {
      return this.isValid === false
    }
  },

  methods: {
    timeFormat(time) {
      return moment(time).format("Do MMM, YYYY");
    },
    turnCameraOn() {
      this.camera = 'auto'
    },
    turnCameraOff() {
      this.camera = 'off'
    },
    hideReedem() {
      this.$bvModal.hide('reedemTicket')
      this.reedemSuccess = false
    },
    onDecode(result) {
      this.result = result
      this.turnCameraOff()
    },
    processTicketAction(action) {
      let self = this;
      self.actionProcessing = true;
      self.action = action.toUpperCase();
      axios.patch('/api/ticket/' + this.result + '/' + action, {withCredentials: true})
          .then((response) => {
            self.ticket = response.data.data;
            self.message = response.data.message;
            self.actionError = false;
            self.actionSuccess = true;
            self.actionProcessing = false;
          })
          .catch(error => {
            console.log(error);
            self.actionError = true;
            self.actionSuccess = false;
            self.actionErrorMessage = error;
            self.actionProcessing = false;
          })
    },
    getTicketDetails() {
      axios.get('/api/ticket/' + this.result)
          .then((response) => {
            // console.log(response.data)
            this.ticket = response.data
          })
          .catch(error => {
            console.log(error);
          })
    },
    onInit(promise) {
      promise
          .catch(console.error)
          .then(this.resetValidationState)
    },
  },
}
</script>

<style lang="scss">
.error {
  font-weight: bold;
  color: red;
}

div.status_log_continaer {
  max-height: 300px;
  overflow-y: scroll;
}

</style>
