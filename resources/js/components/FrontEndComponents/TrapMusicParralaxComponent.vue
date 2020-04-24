<template>
    <div>
        <div class="row home-main">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        <a href="#" class="logo-link text-right">630 Travis ST</a>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <img class="logo" src="/images/trap-music-museum-logo.png" alt="">
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <a href="#" class="logo-link text-left">Atlanta, GA</a>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-lg-2 offset-lg-5 col-md-6 offset-md-3">
                        <a href="/covid-19" class="btn btn-secondary call-to-action">Purchase tickets <i class="fas fa-chevron-right"></i></a>
                        <a href="https://escapethetrap.resova.us/" class="btn btn-secondary call-to-action">Escape room <i class="fas fa-chevron-right"></i></a>
                    </div><!-- End col -->
                </div><!-- End row -->
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="spacer" style="height: 19rem"></div>

        <div class="row">
            <div class="col-md-12 image-section image-section-1">
                <h3 class="image-section-title">
                    The Trap Music Museum isn't just about music but also about the culture that <span class="highlight-text">Inspires</span>
                </h3>
                <h4 class="image-section-subtitle">- The Source</h4>

                <img src="/images/parralax-image-1.png" alt="Parralex Image 1" class="parralax-image parralax-image-1">

                <div class="spacer" style="height: 52rem"></div>

                <img src="/images/parralax-image-2.png" alt="Parralex Image 2" class="parralax-image parralax-image-2">

                <img src="/images/parralax-image-3.png" alt="Parralex Image 3" class="parralax-image parralax-image-3">

                <h3 class="image-section-supplemental-title">MUSEUM HOURS</h3>
                <p class="image-section-supplemental-content">
                    Thursday (HAPPY HOUR)  4pm-10pm<br>
                    FRIDAY 4pm - 12AM<br>
                    Saturday 12pm - 12am<br>
                    Sunday 2pm -10pm<br>
                    <a href="/" class="btn btn-white">More Information <i class="fas fa-chevron-right"></i></a>
                </p>

                <img src="/images/parralax-image-2.png" alt="Parralex Image 2" class="parralax-image parralax-image-2-mobile">
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

            $(document).ready(function () {
                $(window).scroll(function () {
                    let image1 = $('.parralax-image-1');
                    let image2 = $('.parralax-image-2');
                    let image3 = $('.parralax-image-3');
                    let image4 = $('.parralax-image-2-mobile');
                    let image5 = $('.parralax-image-3-mobile');

                    if (image1.is(':visible')) {
                        image1.addClass('visible');
                    }

                    if (image2.is(':visible')) {
                        image2.addClass('visible');
                    }

                    if (image3.is(':visible')) {
                        image3.addClass('visible');
                    }

                    if (image4.is(':visible')) {
                        image4.addClass('visible');
                    }

                    if (image5.is(':visible')) {
                        image5.addClass('visible');
                    }
                });
            });
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

    $yellow-color: gold;

    .logo-link {
        display: block;
        color: #FFF;
        text-transform: uppercase;
        font-size: 2rem;
        padding-top: 15rem;
        transition: all 0.2s ease-in-out;
        @include theme-font;
        &:hover {
            color: $yellow-color;
            font-size: 2.2rem;
            text-decoration: none!important;
        }
    }

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
            font-size: 2rem;
            font-weight: normal;
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
            .btn {
                margin-top: 2rem;
            }
        }
    }

    .parralax-image-1 {
        right: -20rem;
        transition: all ease 5s;
        position: absolute;
        height: 50rem;
        width: auto;
        opacity: 0;
        &.visible {
            opacity: 1;
            right: 0;
        }
    }

    .parralax-image-2 {
        left: -20rem;
        transition: all ease 10s;
        position: absolute;
        height: 70rem;
        width: auto;
        opacity: 0;
        &.visible {
            left: 0;
            opacity: 1;
        }
    }

    .parralax-image-3 {
        right: -20rem;
        transition: all ease 15s;
        position: absolute;
        margin: 20rem 0 0 0;
        height: 50rem;
        width: auto;
        opacity: 0;
        &.visible {
            right: 0;
            opacity: 1;
        }
    }

    .parralax-image-2-mobile {
        display: none;
    }

    .parralax-image-3-mobile {
        display: none;
    }

    .home-main {
        margin-top: -2rem;
        opacity: 0;
        transition: all ease-in-out 1s;
    }
</style>
