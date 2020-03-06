<template>
    <div>
        <b-form-group id="page-accordion-title-group" label="Title" label-for="accordion-title">
            <b-form-input
                id="accordion-title"
                required
                v-model="title"
                placeholder="e.g. What is it we do?"
            ></b-form-input>
        </b-form-group>

        <ckeditor :editor="editor" :value="editorData" :config="editorConfig"></ckeditor>

        <b-button variant="success" class="mt-2 mb-2" @click="addAccordionSection()">Add accordion section</b-button>

        <h5 v-show="sections.length > 0">Preview</h5>

        <div role="tablist" v-show="sections.length > 0">
            <b-card no-body class="mb-1" v-for="(section, index) in sections">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button href="#" class="w-50" v-b-toggle="'accordion-' + index" variant="primary">
                        {{ section.title }}
                    </b-button>
                    <b-button variant="danger" class="float-right" @click="removeSection(section)">Delete</b-button>
                </b-card-header>
                <b-collapse :id="'accordion-' + index" visible accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-card-text v-html="section.content"></b-card-text>
                    </b-card-body>
                </b-collapse>
            </b-card>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    import { bus } from '../../app';

    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: {
            value: {
                type: Object,
                default: {}
            },
            id: {
                type: String,
                default: ''
            }
        },
        methods: {
            addAccordionSection() {
                this.sections.push({
                    title: this.title,
                    content: this.editorData
                });

                this.title = '';
                this.editorData = '';

                this.$emit('input', {
                    sections: this.sections
                });
            },
            removeSection(section) {
                let index = this.sections.indexOf(section);
                this.sections.splice(index, 1);
            }
        },
        data() {
            return {
                title: '',
                sections: [],
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {

                }
            }
        }
    }
</script>
