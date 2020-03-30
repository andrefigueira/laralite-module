<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-4">
                    <div class="col-md-4 offset-4">
                        <div class="list-group" :class="[forms.selected ? 'forms-selected': '']">
                            <a href="#" @click="selectForm('general')" class="list-group-item list-group-item-action">General Enquires <i class="mt-1 float-right fas fa-arrow-circle-right"></i></a>
                            <a href="#" @click="selectForm('refunds')" class="list-group-item list-group-item-action">Refunds <i class="mt-1 float-right fas fa-arrow-circle-right"></i></a>
                            <a href="#" @click="selectForm('media')" class="list-group-item list-group-item-action">Media <i class="mt-1 float-right fas fa-arrow-circle-right"></i></a>
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
            &.active {
                background: #310A0A;
                border-color: #310A0A;
            }
            &.active:hover {
                background: #310A0A;
                border-color: #310A0A;
            }
            &:hover {
                background: #491111;
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
