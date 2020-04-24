<template>
    <footer class="footer">
        <div class="row">
            <div class="col-lg-3">
                <ul>
                    <li><a href="/contact">Contact us</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/faq">Frequently Asked Questions</a></li>
                    <li><a href="/covid-19">Tickets</a></li>
                    <li><a href="/hours">Hours</a></li>
                    <li><a href="/">Home</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <a class="footer-link" href="/covid-19"><img class="footer-logo" src="/images/trap-music-museum-logo.png" alt=""></a>
            </div>
            <div class="col-lg-3">
                <a class="footer-link" href="https://trappyhour.resova.us/"><img class="footer-logo" src="/images/sip-and-trap-logo.png" alt=""></a>
            </div>
            <div class="col-lg-3">
                <a class="footer-link" href="https://escapethetrap.resova.us/"><img class="footer-logo" src="/images/escape-trap-logo.png" alt=""></a>
            </div>
        </div>
    </footer>
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
    .footer {
        ul {
            display: block;
            li {
                a {
                    display: block;
                }
            }
        }
        .footer-logo {
            max-height: 10rem;
        }
    }

    .footer-link {
        cursor: pointer;
    }
</style>
