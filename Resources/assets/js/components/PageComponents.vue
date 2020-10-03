<template>
    <div>
        <div class="row">
            <div class="col-6">
                <h5 class="float-left m-0">Page Features</h5>
            </div><!-- End col -->
            <div class="col-6">
                <b-button variant="default" size="sm" class="float-right" @click="toggleFeatureOptions()" v-b-tooltip.hover title="Click to add a new page feature">
                    <i v-show="!showFeatureOptions" class="fas fa-plus"></i>
                    <i v-show="showFeatureOptions" class="fas fa-minus"></i>
                </b-button>
            </div><!-- End col -->
            <div class="col-12" v-show="showFeatureOptions">
                <div class="hr mt-2 mb-2"></div>
                <label for="component-section">Page Placement</label>
                <v-select id="component-section" label="name" v-model="section" :options="sectionOptions" :clearable="false"></v-select>

                <div class="component-options">
                    <div class="component-option-selector" v-for="component in options" @click="addComponent(component)">
                        <i class="fas" :class="component.settings.icon"></i>
                        <h6 class="title">{{ component.name }}</h6>
                    </div><!-- Component option selector -->
                </div><!-- End container options -->
            </div><!-- End col -->
        </div><!-- End row -->

        <div class="row">
            <div class="col-md-12">
                <div v-for="pageComponent in components">
                    <b-card class="mb-2">
                        <div class="row">
                            <div class="col-6">
                                <h4>{{ pageComponent.frontendName }}</h4>
                            </div><!-- End col -->
                            <div class="col-6">
                                <b-btn @click="removeComponent(pageComponent)" variant="default" class="float-right" v-b-tooltip.hover title="Click to remove from page">&times;</b-btn>
                            </div><!-- End col -->
                        </div><!-- End row -->

                        <table class="table table-bordered" style="font-size: 0.8rem">
                            <tr>
                                <th width="50%" class="align-middle">Page Placement</th>
                                <td class="align-middle">{{ pageComponent.section }}</td>
                            </tr>
                        </table>

                        <component class="mb-2" :is="pageComponent.name" :id="pageComponent.id" v-model="pageComponent.properties"></component>
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
                components: [],
                showFeatureOptions: false
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
                    alert('Failed to load component options');
                });
            },
            removeComponent(component) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.components.indexOf(component);

                        this.components.splice(index, 1);
                    }
                }).catch(error => {
                    alert('Failed to remove component');
                });
            },
            addComponent(component) {
                let componentId = helpers.uuidv4();
                let componentName = 'admin-' + component.slug + '-component';
                let name = component.name;

                if (component.settings.name !== undefined) {
                    name = component.settings.name;
                }

                let componentIndex = this.components.push({
                    id: componentId,
                    name: componentName,
                    section: this.section.slug,
                    frontendName: name,
                    frontendComponentName: name.replace(/\s/g, '') + 'Component',
                    properties: component.properties
                });

                componentIndex -= 1;

                bus.$on(componentId + '-' + componentName + '-change', response => {
                    this.components[componentIndex].properties = response.properties;
                    this.$emit('input', this.components);
                });

                this.toggleFeatureOptions();
            },
            toggleFeatureOptions() {
                this.showFeatureOptions = !this.showFeatureOptions;
            }
        }
    }
</script>

<style lang="scss">
    .component-options {
        display: flex;
        flex-wrap: wrap;
        margin: 1rem 0 0 0;
        .component-option-selector {
            flex-basis: 20%;
            flex-grow: 1;
            width: 25%;
            padding: 1rem;
            margin: 0.2rem;
            text-align: center;
            border: 1px solid #F1F1F1;
            cursor: pointer;
            transition: all ease-in-out 0.2s;
            .fas {
                font-size: 1.65rem;
            }
            .title {
                font-weight: normal;
                font-size: 0.8rem;
                margin: 0.8rem 0 0 0;
            }
            &:hover {
                box-shadow: 0 0 5px #D9D9D9;
            }
        }
    }
</style>