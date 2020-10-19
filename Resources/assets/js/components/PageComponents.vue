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
                <b-button variant="success" class="btn-sm w-100" @click="addComponent()">Add</b-button>
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="hr mb-4 mt-4"></div>

        <div class="row">
            <div class="col-md-12">
                    <div v-for="pageComponent in components">
                        <b-card class="mb-2">
                            <table class="table table-bordered" style="font-size: 0.8rem">
                                <tr>
                                    <th class="align-middle w-50">Frontend Name</th>
                                    <td class="align-middle">{{ pageComponent.frontendName }}</td>
                                </tr>
                                <tr>
                                    <th class="align-middle">Section</th>
                                    <td class="align-middle">{{ pageComponent.section }}</td>
                                </tr>
                            </table>

                            <component class="mb-2" :is="pageComponent.name" :id="pageComponent.id" v-model="pageComponent.properties"></component>

                            <b-btn @click="removeComponent(pageComponent)" variant="danger" class="btn-sm">Remove Component &times;</b-btn>
                        </b-card>
                    </div>
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</template>

<script>
    import { bus } from '../admin'
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
                axios.get('/api/component?all=true').then(response => {
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
                let componentName = 'admin-' + this.component.slug + '-component';
                debugger;

                let componentIndex = this.components.push({
                    id: componentId,
                    name: componentName,
                    section: this.section.slug,
                    frontendName: this.component.name.replace(/([a-z])([A-Z])/g, "$1-$2")
                        .replace(/\s+/g, '-')
                        .toLowerCase() + '-component',
                    properties: this.component.properties
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
