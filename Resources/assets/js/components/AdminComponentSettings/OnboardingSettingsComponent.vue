<template>
    <div>
        <alert-component :alert="alert"></alert-component>

        <b-form-checkbox
            id="checkbox-1"
            v-model="value.live"
            name="checkbox-1"
            :value="true"
            :unchecked-value="false">
            Is Live?
        </b-form-checkbox>

        <button class="btn btn-success mt-4" @click="saveChanges()">Save Changes</button>
    </div>
</template>

<script>
import { bus } from '../../admin';
import helpers from "../../helpers";

export default {
    props: {
        value: {
            type: Object,
            default: {
                live: false
            }
        },
        component: {
            type: Object
        }
    },
    data() {
        return {
            alert: {
                show: false,
                dismissible: true,
                message: '',
                variant: '',
                dismissCountDown: 0,
                dismissSecs: 3
            },
        }
    },
    methods: {
        saveChanges() {
            let payload = {
                properties: this.value
            };

            axios.patch('/api/component/' + this.component.id, payload).then(response => {
                this.alert.show = true;
                this.alert.dismissCountDown = 3;
                this.alert.variant = 'success';
                this.alert.message = 'Component settings saved';

            }).catch(error => {
                this.alert.show = true;
                this.dismissible = true;
                this.alert.dismissCountDown = 3;
                this.alert.variant = 'danger';
                this.alert.message = 'Failed to save changes, try again later...';
            });
        }
    }
}
</script>

<style lang="scss">

</style>
