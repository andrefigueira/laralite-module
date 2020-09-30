<template>
    <div>
        <div class="row">
            <div class="col-12">
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
            <div class="col-3">
                <div class="page-section p-4 mt-2">
                    <label for="currency-option" class="mt-3">Currency</label>
                    <v-select class="mb-3" id="currency-option" label="title" v-model="currency" :options="currencyOptions" :clearable="false"></v-select>
                </div><!-- End page section -->
            </div><!-- End col -->
            <div class="col-12 mt-2">
                <div class="page-section p-4">
                    <button class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </div><!-- End row -->
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                maintenance: false,
                currency: '$ US Dollar',
                currencyOptions: [
                    {
                        title: '$ US Dollar',
                        value: 'USD'
                    },
                    {
                        title: '£ UK Sterling',
                        value: 'GBP'
                    }
                ],
            }
        }
    }
</script>
