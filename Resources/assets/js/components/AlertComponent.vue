<template>
    <div>
        <b-alert
            :show="shouldShowAlert()"
            :variant="alert.variant"
            dismissible
            @dismissed="alert.dismissCountDown=0"
            @dismiss-count-down="alertCountDownChanged"
        >
            {{ alert.message }}
            <b-progress
                v-if="alert.dismissible"
                variant="warning"
                :max="alert.dismissSecs"
                :value="alert.dismissCountDown"
                height="4px"
            ></b-progress>
        </b-alert>
    </div>
</template>

<script>
    export default {
        name: 'AlertComponent',
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            alert: {
                type: Object
            }
        },
        methods: {
            showAlertIfShowAndNotDismissible() {
                return this.alert.show && !this.alert.dismissible;
            },
            showAlertIfShowAndDismissCountDownNotZero() {
                return this.alert.show && this.alert.dismissCountDown;
            },
            shouldShowAlert() {
                return this.showAlertIfShowAndNotDismissible() || this.showAlertIfShowAndDismissCountDownNotZero();
            },
            alertCountDownChanged(dismissCountDown) {
                this.alert.dismissCountDown = dismissCountDown
            }
        }
    }
</script>
