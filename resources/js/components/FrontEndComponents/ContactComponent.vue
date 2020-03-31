<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-4">
                    <div class="col-md-4 offset-4">
                        <div class="list-group" :class="[forms.selected ? 'forms-selected': '']">
                            <a href="#" @click="selectForm('general')" class="list-group-item list-group-item-action">General Inquiries <i class="mt-1 float-right fas fa-arrow-circle-right"></i></a>
                            <a href="#" @click="selectForm('refunds')" class="list-group-item list-group-item-action">Refunds <i class="mt-1 float-right fas fa-arrow-circle-right"></i></a>
                        </div>

                        <div class="list-group mb-3" :class="[forms.selected ? '': 'forms-selected']">
                            <a href="#" @click="selectForm(false)" class="list-group-item list-group-item-action"><i class="fas fa-chevron-circle-left"></i> Change form</a>
                        </div>
                    </div>
                </div>

                <form action="" v-show="forms.selected === 'general'" class="contact-form" :class="[forms.selected === 'general' ? 'form-selected': '']">
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
                            <div class="form-group">
                                <label for="inquiry-type">Select Type of Inquiry</label>
                                <select id="inquiry-type" class="form-control">
                                    <option selected="selected">Press / Media</option>
                                    <option>Private Group Events</option>
                                    <option>Sponsorships</option>
                                    <option>Customer Service & General Information</option>
                                </select>
                            </div>

                            <label for="description">Description</label>
                            <textarea id="description" class="form-control mb-4" placeholder="Enter your message..."></textarea>

                            <button class="btn btn-white">Send Enquiry</button>
                        </div>
                    </div>
                </form>

                <form action="" v-show="forms.selected === 'refunds'" class="contact-form" :class="[forms.selected === 'refunds' ? 'form-selected': '']">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="refund-name">Full Name</label>
                            <input id="refund-name" type="text" class="form-control mb-4" placeholder="Enter your name...">
                        </div>
                        <div class="col-md-6">
                            <label for="refund-email">Email Address</label>
                            <input id="refund-email" type="email" class="form-control mb-4" placeholder="Enter a valid email address...">
                        </div>
                        <div class="col-md-6">
                            <label for="refund-phone">Phone Number</label>
                            <input id="refund-phone" type="tel" class="form-control mb-4" placeholder="Enter your telephone number...">
                        </div>
                        <div class="col-md-6">
                            <label for="confirmation-number">Confirmation Number</label>
                            <input id="confirmation-number" type="text" class="form-control mb-4" placeholder="Enter your confirmation number...">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="refund-product-purchased">Product Purchased</label>
                                <select id="refund-product-purchased" class="form-control">
                                    <option selected="selected">Trap Music Museum Ticket</option>
                                    <option>Escape room reservation</option>
                                    <option>Sip & Trap Happy Hour</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="option-selected">What would you like to do?</label>
                                <select id="option-selected" class="form-control">
                                    <option selected="selected">Priority Postpone</option>
                                    <option>Refund</option>
                                </select>
                            </div>

                            <label for="refund-description">Description</label>
                            <textarea id="refund-description" class="form-control mb-4" placeholder="Enter your message..."></textarea>

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
                forms: {
                    selected: false,
                    general: {

                    },
                    refunds: {

                    },
                    media: {

                    }
                }
            }
        },
        methods: {
            selectForm(name) {
                this.forms.selected = name;
            }
        }
    }
</script>

<style lang="scss">
    @import "../../../sass/mixins";

    $yellow-color: gold;

    .content-wrapper {
        color: #FFF;
        padding: 2rem;
        @include theme-font;
    }

    .form-control {
        border-radius: 0;
    }

    .list-group {
        transition: all ease-in-out 0.4s;
        &.forms-selected {
            margin-top: -2rem;
            opacity: 0;
            height: 0;
        }
        .list-group-item {
            background: #310A0A;
            color: #FFF;
            border: 3px solid #310A0A;
            &.active {
                background: none;
                border-color: #310A0A;
            }
            &.active:hover {
                background: none;
                border-color: #310A0A;
            }
            &:hover {
                background: none;
                border-color: #310A0A;
                color: #FFF;
            }
        }
    }

    .contact-form {
        opacity: 0;
        transition: all ease-in-out 0.4s;
        &.form-selected {
            opacity: 1;
        }
    }
</style>
