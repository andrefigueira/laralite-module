<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <form action="">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Full Name</label>
                            <input id="name" type="text" class="form-control mb-4" placeholder="Enter your name...">
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email address</label>
                            <input id="email" type="email" class="form-control mb-4" placeholder="Enter a valid email address...">
                        </div>
                        <div class="col-md-12">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control mb-4" placeholder="Enter your message..."></textarea>

                            <button class="btn btn-white">Send Enquiry</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'FooterComponent',
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

    .content-wrapper {
        color: #FFF;
        padding: 2rem;
        @include theme-font;
    }

    .form-control {
        border-radius: 0;
    }
</style>
