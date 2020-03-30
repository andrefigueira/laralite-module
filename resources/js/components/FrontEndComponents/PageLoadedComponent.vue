<template>
    <div>
    </div>
</template>

<script>
    export default {
        name: 'PageLoadedComponent',
        mounted() {
            console.log('Component mounted.')

            window.onload = function () {
                document.body.className += 'loaded';
            }
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
    .loaded {
        .home-main {
            margin-top: 0;
            opacity: 1;
        }
        .top-nav {
            opacity: 1;
        }
    }
</style>
