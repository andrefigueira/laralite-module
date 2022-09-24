<template>
    <div>
        <ckeditor :editor="editor" :value="value.editorData" :config="editorConfig" @input="onChange"></ckeditor>
    </div>
</template>

<script>
    import ClassicEditor from 'ckeditor5-custom-build/build/ckeditor';
    import { bus } from '../../admin';

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
            onChange(data) {
                bus.$emit(this.id + '-admin-content-component-change', {
                    componentId: this.id,
                    properties: {
                        editorData: data
                    }
                });

                this.$emit('input', {
                    editorData: data
                });
            }
        },
        data() {
            return {
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' }
                        ]
                    }
                }
            }
        }
    }
</script>
