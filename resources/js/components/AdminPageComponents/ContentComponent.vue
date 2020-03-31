<template>
    <div>
        <editor
            api-key="1zv9du0onoyl619egrfevih7r7p4p8vawafvqhi5hzzfutmf"
            :value="value.editorData"
            @input="onChange"
            :init="config"
        />
    </div>
</template>

<script>
    import { bus } from '../../app';
    import helpers from '../../helpers'
    import Editor from '@tinymce/tinymce-vue'

    export default {
        mounted() {
            console.log('Component mounted.');
        },
        components: {
            Editor,
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
                editorData: '',
                config: {
                    height: 500,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor importcss',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    menubar: 'edit view insert format tools table help',
                    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
                    content_css: '/css/contents.css',
                    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                    contextmenu: "link image imagetools table",
                    formats: {
                        alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left' },
                        aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center' },
                        alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right' },
                        alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full' },
                        bold: { inline: 'span', classes: 'bold' },
                        italic: { inline: 'span', classes: 'italic' },
                        underline: { inline: 'span', classes: 'underline', exact: true },
                        strikethrough: { inline: 'del' },
                        customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format'} , classes: 'example1'}
                    },
                    font_formats: 'Oswold=Oswald,san-serif;ProximaNova=Lato,san-serif;Arial=arial,helvetica,sans-serif;',
                    fontsize_formats: '11px 12px 14px 16px 18px 24px 36px 48px'
                }
            }
        }
    }
</script>
