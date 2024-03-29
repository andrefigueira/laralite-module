<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <h5 class="float-left m-0 pt-2 pl-2">Page Features</h5>
            </div><!-- End col -->
            <div class="col-md-6">
                <a class="p-2 float-right" @click="toggleFeatureOptions()" v-b-tooltip.hover title="Click to add a new page feature">
                    <i v-show="!showFeatureOptions" class="fas fa-plus" style="font-size: 20px"></i>
                    <i v-show="showFeatureOptions" class="fas fa-minus" style="font-size: 20px"></i>
                </a>
            </div><!-- End col -->
            <div class="col-md-12" v-show="showFeatureOptions">
                <div class="hr mt-2 mb-2"></div>
                <label for="component-section" class="ml-2">Page Placement</label>
                <v-select class="ml-2 mr-2" id="component-section" label="name" v-model="section" :options="sectionOptions" :clearable="false"></v-select>

                <div class="component-options pl-2 pr-2">
                    <div class="component-option-selector m-2 p-2" v-for="component in options" @click="addComponent(component)">
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
                            <div class="col-md-6">
                                <h4>{{ pageComponent.frontendName }}</h4>
                            </div><!-- End col -->
                            <div class="col-md-6">
                              <a v-b-tooltip:hover title="Click to remove from page" class="ri-close-fill float-right align-middle mr-2" style="width: 10%; cursor: pointer" @click="doDelete(pageComponent)"></a>
                              <confirm-dialogue-component ref="confirmDialogue"></confirm-dialogue-component>
<!--                                <a @click="removeComponent(pageComponent)" class="ri-close-fill float-right align-middle" v-b-tooltip.hover title="Click to remove from page"></a>-->
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
    import ConfirmDialogueComponent from "./ConfirmDialogueComponent";

    export default {
      components: {ConfirmDialogueComponent},
      mounted() {
            console.log('Component mounted.');
            window.addEventListener('beforeunload', (event) => {
              if (!this.isEditing) return
              event.preventDefault()
              // Chrome requires returnValue to be set.
              event.returnValue = ""
            })
            this.load();
        },
        props: ['value', 'template', 'editing'],
        data() {
            return {
                options: [],
                sectionOptions: [],
                component: {},
                section: {},
                components: [],
                showFeatureOptions: false,
                isEditing: this.editing
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
            },
            components: {
              handler(newValue, oldValue) {
                if(oldValue.length)
                  this.isEditing = true
              },
              deep: true
            },
            editing (newValue, oldValue) {
              this.isEditing = newValue
            },
            isEditing (newValue, oldValue) {
              // console.log('isEditing', newValue)
              bus.$emit('pageEdited', newValue)
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
          async doDelete(component) {
            const ok = await this.$refs.confirmDialogue[0].show({
              title: 'Delete Component: ' + component.name,
              message: 'Are you sure you want to delete this component? It cannot be undone.',
              okButton: 'Delete',
            })
            // If you throw an error, the method will terminate here unless you surround it wil try/catch
            if (ok) {
              console.log(component);
              if (component) {
                let index = this.components.indexOf(component);
                this.components.splice(index, 1);
                // location.reload();
              } else {
                alert('Failed to remove component');
                console.log(component);
              }
            } else {
              /*alert('You chose not to delete this page. Doing nothing now.')*/
              console.log(component)
            }},
           /* removeComponent(component) {
                this.isEditing = true
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.components.indexOf(component);

                        this.components.splice(index, 1);
                    }
                }).catch(error => {
                    alert('Failed to remove component');
                });
            },*/
            addComponent(component) {
              this.isEditing = true
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
                this.$emit('input', this.components);
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
