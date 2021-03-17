<template>
    <div>
        <div class="row">
            <div class="col-12">
                <p class="alert alert-danger" v-if="error !== ''">{{ error }}</p>

                <p v-if="result" class="decode-result">Last result: <b>{{ result }}</b></p>

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
          error: '',
          message: ''
        }
    },
    components: {
        QrcodeStream,
        QrcodeDropZone,
        QrcodeCapture
    },
    watch: {
      result (newValue, oldValue) {
        if (newValue != null)
        {
          this.result = newValue;
          this.verifyTicket(this.result);
        }

      },
    },
    methods: {
        async onDecode (result) {
            this.result = result,
            this.error = '';
        },
        verifyTicket(result) {
          axios.get('/api/scan/ticket/' + this.result)
          .then((response) => {
            console.log(response)
          }).catch(error => {
            console.log(error.response.data.message)
            alert(error.response.data.message)
              });
        },
      onInit (promise) {
        promise
            .catch(console.error)
      },
      turnCameraOff () {
        this.camera = 'off'
      },
    },
}
</script>

<style lang="scss">
    .error {
        font-weight: bold;
        color: red;
    }
</style>
