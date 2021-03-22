<template>
    <div>
        <div class="row">
            <div class="col-12">
<!--                <p class="alert alert-danger" v-if="error !== ''">{{ error }}</p>-->
<!--              <p class="decode-result">Ticket ID: <b>{{ result }}</b></p>-->
<!--              <b-alert :variant="variant" show>{{ message }}</b-alert>-->
              <p class="alert" :class="{ 'alert-danger': this.error, 'alert-success': !this.error }" v-if="message !== ''">
                <b>TICKET ID: </b>{{ ticket_id }} <b>Visits: </b> {{ count }} <br/>{{ message }}</p>
              <qrcode-stream :camera="camera" @decode="onDecode" @init="onInit"></qrcode-stream>
            </div>
        </div>
    </div>
</template>

<script>
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

export default {
    data() {
        return {
          camera: 'auto',
          result: '',
          error: false,
          message: '',
          count: '',
          ticket_id: '',
        }
    },
    components: {
        QrcodeStream,
        QrcodeDropZone,
        QrcodeCapture
    },
    watch: {
      result (newValue, oldValue) {
        if (newValue != null && newValue !== '')
        {
          this.result = newValue;
          this.verifyTicket(this.result);
        }

      },
    },
  computed: {
    variant() {
      return this.error ? 'danger' : 'success';
    }
  },
    methods: {
        async onDecode (result) {
          this.result = result
          this.message = ''
          this.error = false;
          this.camera = 'off'
          await this.timeout(500)
          this.camera = 'auto'
        },
        verifyTicket(result) {
          axios.get('/api/scan/ticket/' + this.result)
          .then((response) => {
            console.log(response.data.message)
            this.message = response.data.message
            this.error = false
            this.count = response.data.ticket.visited_counts
            this.result = ''
            this.ticket_id = response.data.ticket.unique_id
          }).catch(error => {
            console.log(error.response.data.message)
            this.message = error.response.data.message
            this.error = true
            this.result = ''
            this.count = 'NULL'
            this.ticket_id = 'NULL'
          });
        },
      onInit (promise) {
        promise
            .catch(console.error)
      },
      turnCameraOff () {
        this.camera = 'off'
      },
      timeout (ms) {
        return new Promise(resolve => {
          window.setTimeout(resolve, ms)
        })
      }
    },
}
</script>

<style lang="scss">
    .error {
        font-weight: bold;
        color: red;
    }
</style>
