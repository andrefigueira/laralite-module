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

        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>

        <b-button variant="success" class="mt-2 mb-2" @click="addAccordionSection()">Add accordion section</b-button>

        <h5 v-show="value.sections !== undefined && value.sections.length > 0">Preview</h5>

        <div role="tablist" v-show="value.sections !== undefined && value.sections.length > 0">
            <b-card no-body class="mb-1" v-for="(section, index) in value.sections">
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

    import { bus } from '../../admin';

    export default {
        name: 'AccordionComponent',
        mounted() {
            console.log('Component mounted.');
        },
        props: {
            value: {},
            id: {
                type: String,
                default: ''
            }
        },
        methods: {
            addAccordionSection() {
                if (this.value.sections === undefined) {
                    this.value.sections = [];
                }

                this.value.sections.push({
                    title: this.title,
                    content: this.editorData
                });

                this.title = '';
                this.editorData = '';

                this.$emit('input', {
                    sections: this.value.sections
                });
            },
            removeSection(section) {
                let index = this.value.sections.indexOf(section);
                this.value.sections.splice(index, 1);
            }
        },
        data() {
            return {
                title: '',
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {

                }
            }
        }
    }
</script>
