<template>
    <div>
        <p v-if="uploadError !== ''" class="alert alert-danger">{{ uploadError }}</p>
        <div v-if="imagePreview === ''" class="image-upload-wrapper" @click="$refs.file.click()">
            <i class="icon far fa-arrow-alt-circle-up"></i>

            <span class="label">Upload image...</span>
        </div><!-- End image upload wrapper -->
        <div class="uploaded-image" v-if="imagePreview !== ''">
            <div class="image-options">
                <b-button variant="default" @click="removeImage()"><i class="far fa-trash-alt"></i></b-button>
            </div><!-- End image options -->
            <img :src="imagePreview" alt="Upload Image Preview">
        </div><!-- End uploaded image -->

        <input class="file-input" type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
    </div>
</template>

<script>
    import { bus } from '../admin'

    export default {
        data() {
            return {
                file: '',
                imagePreview: '',
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

                axios.post('/api/image/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (result) {
                    self.imagePreview = result.data.data.path;
                    self.uploadError = '';

                    self.$emit('image-uploaded', self.imagePreview);
                }).catch(function (error) {
                    console.log('Image Upload Failed', {
                        error: error
                    });

                    self.uploadError = error.response.data.message;
                });

            },
            showImagePreview() {
                let reader = new FileReader();
                let self = this;

                reader.onload = function (e) {
                    self.imagePreview = e.target.result;
                }

                reader.readAsDataURL(this.file);
            },
            removeImage() {
                this.imagePreview = '';
                this.$emit('image-removed');
            }
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