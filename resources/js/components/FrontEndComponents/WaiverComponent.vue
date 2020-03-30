<template>
    <div>
        <div class="hr"></div>
        <div class="row">
            <div class="col-md-12 p-5">
                <div class="waiver-form">
                    <div class="form-group">
                        <label for="waiver-agreement">I HAVE READ THIS DOCUMENT AND UNDERSTAND IT. I FURTHER UNDERSTAND THAT BY SIGNING THIS RELEASE, I VOLUNTARILY SURRENDER CERTAIN LEGAL RIGHTS.</label>
                        <input type="text" class="form-control" id="waiver-agreement" placeholder="Please type your full name...">
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="agree-checkbox">
                        <label class="custom-control-label" for="agree-checkbox">I Agree *</label>
                        <small class="form-text text-muted">I agree to the Trap Music Museum Waiver</small>
                    </div>

                    <div class="form-group mt-3">
                        <label for="inputState">Any minors? *</label>
                        <select id="inputState" class="form-control">
                            <option selected>None</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="additional-guest-names">Additional Guest Names</label>
                        <textarea class="form-control" id="additional-guest-names" rows="3" placeholder="Enter guest names here..."></textarea>
                        <small class="form-text text-muted">If you know who you will be accompanying, please enter the first and last names of the guest in your party.</small>
                    </div>

                    <button class="btn btn-white">Send Waiver</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'WaiverComponent',
        mounted() {
            console.log('Component mounted.')
        },
        props: {

        },
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>

<style lang="scss">
    @import "../../../sass/mixins";

    .waiver-form {
        label {
            color: #FFF;
            @include theme-font;
        }
    }
</style>
