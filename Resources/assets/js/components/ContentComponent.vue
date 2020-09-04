<template>
    <div>
        <div class="content" v-html="editorData"></div>
    </div>
</template>

<script>
    export default {
        name: 'ContentComponent',
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            editorData: {
                type: String,
                default: ''
            }
        }
    }
</script>
