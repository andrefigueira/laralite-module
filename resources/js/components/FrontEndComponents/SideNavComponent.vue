<template>
    <div class="nav-wrapper">
        <div class="side-nav">
            <a href="javascript:{}" class="side-nav-toggle" @click="toggleNavigation()"><i class="fas fa-bars"></i> <span v-show="showNav">Hide </span> Menu</a>
            <ul class="side-nav-menu" :class="(showNav ? 'show' : '')">
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="https://tickets.trapmusicmuseum.com">Tickets</a></li>
                <li><a href="/groups">Groups</a></li>
                <li><a href="/sip-and-trap">Sip &amp; Trap</a></li>
                <li><a href="/faq">FAQ</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/waivers">Waivers</a></li>
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
    $yellow-color: gold;

    .nav-wrapper {
        display: inline;
    }

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
            background-color:rgba(0, 0, 0, 0.5);
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
                    color: #FFF;
                    text-decoration: none;
                    text-transform: uppercase;
                    transition: all 0.2s ease-in-out;
                    font-size: 1.5rem;
                    &:hover {
                        color: $yellow-color;
                        padding-left: 3rem;
                    }
                }
            }
        }
    }
</style>
