<template>
    <div>
        <div class="row home-main">
            <div class="col-md-12">
                <img class="logo" src="/images/trap-music-museum-logo.png" alt="">

                <div class="row mb-5">
                    <div class="col-md-2 offset-5">
                        <a href="" class="btn btn-secondary call-to-action">Purchase tickets <i class="fas fa-chevron-right"></i></a>
                        <a href="" class="btn btn-secondary call-to-action">Escape room <i class="fas fa-chevron-right"></i></a>
                    </div><!-- End col -->
                </div><!-- End row -->
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="spacer" style="height: 30rem"></div>

        <div class="row">
            <div class="col-md-12 image-section image-section-1">
                <h3 class="image-section-title">
                    The Trap Music Museum isn't just about music but also about the culture that <span class="highlight-text">Inspires</span>
                </h3>
                <h4 class="image-section-subtitle">- The Source</h4>

                <img
                    style="
                position: absolute;
                height: 50rem;
                width: auto;
                "
                    src="/images/parralax-image-1.png" alt="Parralex Image 1" class="parralax-image-1">

                <div class="spacer" style="height: 52rem"></div>

                <img
                    style="
                position: absolute;
                height: 70rem;
                width: auto;
                "
                    src="/images/parralax-image-2.png" alt="Parralex Image 2" class="parralax-image-2">

                <img
                    style="
                position: absolute;
                margin: 20rem 0 0 0;
                height: 50rem;
                width: auto;
                "
                    src="/images/parralax-image-3.png" alt="Parralex Image 3" class="parralax-image-3">

                <h3 class="image-section-supplemental-title">MUSEUM HOURS</h3>
                <p class="image-section-supplemental-content">
                    Thursday (HAPPY HOUR)  4pm-10pm<br>
                    FRIDAY 4pm - 12AM<br>
                    Saturday 12pm - 12am<br>
                    Sunday 2pm -10pm<br>
                    <a href="/" class="btn btn-white">More Information <i class="fas fa-chevron-right"></i></a>
                </p>
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="spacer" style="height: 52rem"></div>

        <div class="row">
            <div class="col-md-12 image-section">
                <h3 class="image-section-title font-size-3">
                    ESCAPE ROOM HOURS
                </h3>
                <h4 class="image-section-subtitle font-size-1">
                    Open 7 Days a week<br>
                    <a class="" href="#">Click here to book</a>
                </h4>
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="row">
            <div class="col-md-12">
                <a href="#">
                    <img class="merchandise-banner" src="/images/merchandise-banner.jpg" alt="Merchandise banner for Trap Music Museum">
                </a>

                <h4 class="call-to-action-text">The Trap Music Museum online store is open to give our patrons the opportunity to buy our official products online. <br>
                    We offer t-shirts, caps, hoodies and more to come</h4>
            </div>
        </div>
    </div>
</template>

<script>
    import helpers from '../../helpers';

    export default {
        name: 'TrapMusicParralaxComponent',
        mounted() {
            console.log('Component mounted.');

            window.onload = function () {
                document.body.className += 'loaded';
            }
        },
        props: {
            sections: {}
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
    @import "../../../sass/utilities";

    .image-section {
        padding-left: 3rem!important;
        padding-right: 3rem!important;
        blockquote {
            display: inline;
            text-transform: uppercase;
            background-color: rgba(0, 0, 0, 0.5);
            color: #FFF;
            padding: 0.2rem;
            margin: auto;
            font-size: 1.5rem;
            @include quote-font;
        }
        .source {
            display: block;
            text-transform: uppercase;
            color: #FFF;
            font-size: 2rem;
            @include quote-font;
        }

        .image-section-title {
            color: #FFF;
            text-transform: uppercase;
            text-align: left;
            .highlight-text {
                display: block;
                font-size: 5rem;
            }
            @include theme-font;
        }

        .image-section-subtitle {
            color: #FFF;
            text-transform: uppercase;
            text-align: left;
            @include theme-font;
        }

        .image-section-supplemental-title {
            color: #FFF;
            text-transform: uppercase;
            text-align: right;
            font-size: 4rem;
            @include theme-font;
        }

        .image-section-supplemental-content {
            color: #FFF;
            text-transform: uppercase;
            text-align: right;
            font-size: 2rem;
            @include theme-font;
        }
    }

    .parralax-image-1 {
        right: 0;
        transition: all ease 1s;
    }

    .parralax-image-2 {
        left: 0;
        transition: all ease 1s;
    }

    .parralax-image-3 {
        right: 0;
        transition: all ease 1s;
    }

    .home-main {
        margin-top: -2rem;
        opacity: 0;
        transition: all ease-in-out 1s;
    }

    .loaded {
        .home-main {
            margin-top: 0;
            opacity: 1;
        }
    }
</style>
