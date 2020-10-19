<template>
    <div>
        <b-card>
            <b-form-checkbox
                id="checkbox-1"
                v-model="value.live"
                name="checkbox-1"
                :value="true"
                :unchecked-value="false">
                Is Live?
            </b-form-checkbox>
        </b-card>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: {
            value: {
                type: Object,
                default: {}
            },
            id: {
                type: String,
                default: ''
            }
        }
    }
</script>
