<template>
    <div>
        <ckeditor
            :editor="editor"
            v-model="value.editorData"
            :config="editorConfig"
        ></ckeditor>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        components: {
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
                // The configuration of the rich-text editor.
                }
            }
        }
    }
</script>
