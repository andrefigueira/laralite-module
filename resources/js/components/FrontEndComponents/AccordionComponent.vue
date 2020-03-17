<template>
    <div role="tablist" class="accordion-wrapper" v-show="sections.length > 0">
        <b-card no-body class="mb-1" v-for="(section, index) in sections">
            <b-card-header header-tag="header" class="p-1" role="tab">
                <b-button href="#" class="toggle-accordion" v-b-toggle="'accordion-' + index" variant="primary">
                    {{ section.title }}
                </b-button>
            </b-card-header>
            <b-collapse :id="'accordion-' + index" visible accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text v-html="section.content"></b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card>
    </div>
</template>

<script>
    export default {
        name: 'AccordionComponent',
        mounted() {
            console.log('Component mounted.')
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

    .accordion-wrapper {
        background: #FFF;
        .toggle-accordion {
            width: 100%;
        }
    }
</style>
