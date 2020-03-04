<template>
    <div>
        <label for="components">Component</label>

        <div class="row">
            <div class="col-md-5">
                <v-select id="components" label="name" v-model="component" :options="options" :clearable="false"></v-select>
            </div><!-- End col -->
            <div class="col-md-5">
                <v-select id="section" label="name" v-model="section" :options="sectionOptions" :clearable="false"></v-select>
            </div><!-- End col -->
            <div class="col-md-2">
                <b-button variant="success" class="btn-sm w-100" @click="addComponent()">Add Component</b-button>
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="hr mb-4 mt-4"></div>

        <div class="row">
            <div class="col-md-12">

                    <div v-for="pageComponent in components">
                        <b-card class="mb-2">
                            <h4>{{ pageComponent.frontendName }} <b-btn @click="removeComponent(pageComponent)" variant="danger" class="float-right btn-sm">Remove &times;</b-btn></h4>
                            <h5>Section: {{ pageComponent.section }}</h5>
                            <component class="mb-2" :is="pageComponent.name" :id="pageComponent.id" v-model="pageComponent.properties"></component>
                        </b-card>
                    </div>
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../app'
    import helpers from '../helpers'

    export default {
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        props: ['value', 'template'],
        data() {
            return {
                options: [],
                sectionOptions: [],
                component: {},
                section: {},
                components: []
            }
        },
        watch: {
            value() {
                if (this.value !== undefined) {
                    this.components = this.value;
                }
            },
            template() {
                if (this.template.sections !== undefined) {
                    this.section = this.template.sections[0];
                    this.sectionOptions = this.template.sections;
                }
            }
        },
        methods: {
            load() {
                axios.get('/api/component').then(response => {
                    this.options = response.data;
                    this.component = this.options[0];
                }).catch(error => {
                    // handle error
                });
            },
            removeComponent(component) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.components.indexOf(component);

                        this.components.splice(index, 1);
                    }
                }).catch(error => {
                    // An error occurred
                });
            },
            addComponent() {
                if (this.component.name === undefined) {
                    alert('Select a component first!');

                    return;
                }

                let componentId = helpers.uuidv4();
                let componentName = 'admin-' + this.component.name.toLowerCase() + '-component';

                let componentIndex = this.components.push({
                    id: componentId,
                    name: componentName,
                    section: this.section.slug,
                    frontendName: this.component.name.toLowerCase() + '-component',
                    properties: {}
                });

                componentIndex -= 1;

                bus.$on(componentId + '-' + componentName + '-change', response => {
                    this.components[componentIndex].properties = response.properties;
                    this.$emit('input', this.components);
                });
            }
        }
    }
</script>
