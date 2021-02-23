<template>
    <div>
        <p v-if="uploadError !== ''" class="alert alert-danger">{{ uploadError }}</p>
        <div v-if="uploadSuccess === false" class="image-upload-wrapper" @click="$refs.file.click()">
            <i class="icon far fa-arrow-alt-circle-up"></i>

            <span class="label">Upload csv...</span>
        </div>

        <input class="file-input" type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
    </div>
</template>

<script>
    export default {
        props: {
            
        },
        data() {
            return {
                file: '',
                uploadSuccess: false,
                uploadError: ''
            }
        },
        methods: {
            handleFileUpload() {
                let self = this;
                this.file = this.$refs.file.files[0];

                if (this.file === undefined) return;

                let formData = new FormData();

                formData.append('file', this.file);

                axios.post('/api/squarespace/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (result) {
                    self.uploadSuccess = result.data.success;
                    self.uploadError = '';

                    self.$emit('csv-uploaded', true);
                    self.$emit('set-total-rows', result.data.count);
                    
                }).catch(function (error) {
                    console.log('CSV Upload Failed', {
                        error: error
                    });

                    self.uploadError = error.response.data.message;
                });
            },
        }
    }
</script>

<style lang="scss">
    .image-upload-wrapper {
        border: 2px dashed #CCC;
        text-align: center;
        padding: 4rem 0;
        margin: 0 0 1rem 0;
        cursor: pointer;
        .icon {
            font-size: 2rem;
        }
        .label {
            display: block;
            margin: 0.5rem 0 0 0;
        }
    }

    .file-input {
        display: none;
    }

    .uploaded-image {
        position: relative;
        padding: 4rem 0;
        background: #333;
        overflow: hidden;
        vertical-align: middle;
        .image-options {
            position: absolute;
            background: #FFF;
            right: 0;
        }
        img {
            width: 100%;
            height: auto;
        }
    }
</style>