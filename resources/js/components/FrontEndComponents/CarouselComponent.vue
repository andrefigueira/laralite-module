<template>
    <div>


    </div>
</template>

<script>
    export default {
        name: 'CarouselComponent',
        mounted() {
            console.log('Component mounted.')
        },
        props: {

        }
    }
</script>

<style lang="scss">
    @import '../../../sass/mixins';


    .logo {
        display: block;
        margin: 10rem auto;
        width: 20rem;
        height: auto;
    }

    .call-to-action {
        @include theme-font;
        width: 100%;
        background: #310A0A;
        border: 4px solid transparent;
        text-transform: uppercase;
        margin: 0 0 0.5rem 0;
        &:hover {
            background: none;
            border: 4px solid #310A0A;
        }
    }

    /* Carousel base class */
    .carousel {

    }
    /* Since positioning the image, we need to help out the caption */
    .carousel-caption {
        bottom: 3rem;
        z-index: 10;
    }

    /* Declare heights because of positioning of img element */
    .carousel-item {
        height: 32rem;
        background-color: #777;
    }
    .carousel-item > img {
        position: absolute;
        top: 0;
        left: 0;
        min-width: 100%;
        height: 32rem;
    }
</style>
