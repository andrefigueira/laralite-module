<template>
    <div>
        <div class="row">
            <div class="col-12">
                <b-card>
                    <b-card-text>
                        <h5 class="mb-4">General Settings</h5>

                        <b-form-checkbox
                            id="checkbox-1"
                            v-model="maintenance"
                            name="checkbox-1"
                            :value="true"
                            :unchecked-value="false"
                        >
                            Maintenance Mode
                        </b-form-checkbox>

                        <button class="btn btn-success mt-4">Save Changes</button>
                    </b-card-text>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                maintenance: false
            }
        }
    }
</script>
