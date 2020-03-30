<template>
    <div class="nav-wrapper">
        <div class="top-nav">
            <ul class="top-nav-menu">
                <li><a href="/">Home</a></li>
                <li><a href="/hours">Hours</a></li>
                <li><a href="https://tickets.trapmusicmuseum.com">Tickets</a></li>
                <li><a href="/escape-room">Escape Room</a></li>
                <li><a href="/waiver">Waiver</a></li>
            </ul>
        </div><!-- End side nav -->
    </div>
</template>

<script>
    export default {
        name: 'TopNavComponent',
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

    $blue-color: #709be7;
    $yellow-color: gold;

    .nav-wrapper {
        display: inline;
    }

    .top-nav {
        padding: 4rem 0 0 4rem;
        opacity: 0;
        transition: all 1s ease-in-out;
        @include theme-font;
        .top-nav-menu {
            position: fixed;
            text-align: center;
            background-color:rgba(0, 0, 0, 0.8);
            top: 0;
            left: 0;
            list-style: none;
            margin: 0;
            width: 100%;
            padding: 0;
            z-index: 99;
            transition: all 0.2s ease-in-out;
            li {
                display: inline-block;
                a {
                    display: block;
                    padding: 1rem 2rem;
                    color: #FFF;
                    text-decoration: none;
                    text-transform: uppercase;
                    transition: all 0.2s ease-in-out;
                    font-size: 1.5rem;
                    &:hover {
                        color: $yellow-color;
                    }
                }
            }
        }
    }

    .loaded {
        .top-nav {
            opacity: 1;
        }
    }
</style>
