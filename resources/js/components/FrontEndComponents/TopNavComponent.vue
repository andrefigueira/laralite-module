<template>
    <div class="nav-wrapper">
        <div class="top-nav">
            <ul class="top-nav-menu">
                <li>
                    <a href="/">
                        <i class="fas fa-home d-xl-none d-md-none"></i>
                        Home
                    </a>
                </li>
                <li>
                    <a href="/hours">
                        <i class="fas fa-clock d-xl-none d-md-none"></i>
                        Hours
                    </a>
                </li>
                <li>
                    <a href="https://trapmusicmuseum.us/tickets">
                        <i class="fas fa-ticket-alt d-xl-none d-md-none"></i>
                        Tickets
                    </a>
                </li>
                <li>
                    <a href="https://escapethetrap.resova.us/">
                        <i class="fas fa-door-open d-xl-none d-md-none"></i>
                        Escape Room
                    </a>
                </li>
                <li>
                    <a href="/waiver">
                        <i class="fas fa-file-alt d-xl-none d-md-none"></i>
                        Waiver
                    </a>
                </li>
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
                    .fas {
                        display: block;
                        font-size: 2rem;
                    }
                    &:hover {
                        color: $yellow-color;
                    }
                }
            }
        }
    }
</style>
