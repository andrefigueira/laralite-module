<template>
    <div>
        <div class="row">
            <div class="col-12">
                <p class="alert alert-danger" v-if="error !== ''">{{ error }}</p>

                <p v-if="result" class="decode-result">Last result: <b>{{ result }}</b></p>

                <qrcode-stream @decode="onDecode" @init="onInit" />
            </div>
        </div>
    </div>
</template>

<script>
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

export default {
    data() {
        return {
            result: '',
            error: ''
        }
    },
    components: {
        QrcodeStream,
        QrcodeDropZone,
        QrcodeCapture
    },
    methods: {
        onDecode (result) {
            this.result = result
            this.error = '';
        },

        async onInit (promise) {
            try {
                await promise
            } catch (error) {
                if (error.name === 'NotAllowedError') {
                    this.error = "ERROR: you need to grant camera access permission"
                } else if (error.name === 'NotFoundError') {
                    this.error = "ERROR: no camera on this device"
                } else if (error.name === 'NotSupportedError') {
                    this.error = "ERROR: secure context required (HTTPS, localhost)"
                } else if (error.name === 'NotReadableError') {
                    this.error = "ERROR: is the camera already in use?"
                } else if (error.name === 'OverconstrainedError') {
                    this.error = "ERROR: installed cameras are not suitable"
                } else if (error.name === 'StreamApiNotSupportedError') {
                    this.error = "ERROR: Stream API is not supported in this browser"
                } else if (error.name === 'InsecureContextError') {
                    this.error = "ERROR: MUST BE ACCESSED OVER HTTPS!"
                }

                if (this.error === '') {
                    this.error = 'Failed due to error';
                }
            }
        }
    }
}
</script>

<style lang="scss">
    .error {
        font-weight: bold;
        color: red;
    }
</style>