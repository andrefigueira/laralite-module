<template>
    <div>
        <div class="side-nav">
            <a href="#" class="side-nav-toggle" @click="toggleNavigation()"><i class="fas fa-bars"></i> <span v-show="showNav">Hide </span> Menu</a>
            <ul class="side-nav-menu" :class="(showNav ? 'show' : '')">
                <li><a href="#">Home</a></li>
                <li><a href="#">Tickets</a></li>
                <li><a href="#">Museum</a></li>
            </ul>
        </div><!-- End side nav -->
    </div>
</template>

<script>
    export default {
        name: 'SideNavComponent',
        mounted() {
            console.log('Component mounted.')
        },
        props: {

        },
        data() {
            return {
                showNav: false
            }
        },
        methods: {
            toggleNavigation() {
                this.showNav = !this.showNav;
            }
        }
    }
</script>

<style lang="scss">
    @import "../../../sass/mixins";

    $blue-color: #709be7;

    .side-nav {
        padding: 4rem 0 0 4rem;
        @include theme-font;
        .side-nav-toggle {
            display: block;
            position: fixed;
            top: 2rem;
            left: 2rem;
            color: $blue-color;
            text-decoration: none;
            text-transform: uppercase;
            z-index: 100;
        }
        .side-nav-menu {
            position: fixed;
            background: #FFF;
            top: 0;
            left: -20%;
            width: 20%;
            list-style: none;
            margin: 0;
            padding: 6rem 0 0 0;
            height: 100vh;
            z-index: 99;
            transition: all 0.2s ease-in-out;
            &.show {
                left: 0;
            }
            li {
                a {
                    display: block;
                    padding: 1rem 2rem;
                    color: #333;
                    text-decoration: none;
                    text-transform: uppercase;
                    &:hover {
                        background: $blue-color;
                        color: #FFF;
                    }
                }
            }
        }
    }
</style>
