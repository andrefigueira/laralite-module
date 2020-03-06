<template>
    <div>
        Carousel
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

                }
            }
        }
    }
</script>
