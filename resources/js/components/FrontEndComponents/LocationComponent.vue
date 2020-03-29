<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4">
                <div class="where-are-we-located">
                    <h2>Where are we located?</h2>
                    <p class="address">630 TRAVIS ST. NW<br>
                    ATLANTA, GEORGIA 30318</p>
                </div>
            </div>
        </div>
        <iframe width="100%" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=630%20TRAVIS%20ST.%20NW%20ATLANTA%2C%20GEORGIA%2030318&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
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

    .mapouter {
        position: relative;
        text-align: right;
        height: 500px;
        width: 600px;
    }

    .gmap_canvas {
        overflow: hidden;
        background: none !important;
        height: 500px;
        width: 600px;
    }

    .where-are-we-located {

    }

    h2 {
        @include theme-font;
        text-transform: uppercase;
    }

    .address {
        font-style: italic;
    }

    .content-wrapper {
        color: #FFF;
        padding: 2rem;
        @include theme-font;
    }
</style>
