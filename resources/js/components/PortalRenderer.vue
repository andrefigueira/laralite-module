<template>
    <div class="portal-renderer">
        <div class="row">
            <div :class="'col-md-' + section.column" v-for="section in page.template.sections">
                <component v-for="component in filterSectionComponents(section)" :is="component.frontendName" v-bind="component.properties"></component>
            </div><!-- End col -->
        </div>
    </div>
</template>

<script>
    export default {
        name: 'PortalRenderer',
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        props: {
            page: {}
        },
        data () {
            return {
                sections: {}
            }
        },
        methods: {
            load() {

            },
            filterSectionComponents(section) {
                return this.page.components.filter(component => {
                    return component.section === section.slug;
                });
            }
        }
    }
</script>

<style lang="scss">
    .portal-renderer {
        /*overflow-x: hidden;*/
    }
</style>
