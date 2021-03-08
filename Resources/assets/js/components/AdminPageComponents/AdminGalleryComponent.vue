<template>
  <div>
    <template v-for="(galleryItem, index) in items">
      <div v-if="galleryItem.url !== '' && galleryItem.url !== null" class="d-inline-block">
        <div>
          <a :href="galleryItem.url" target="_blank" class="position-relative">
            <b-button @click.prevent.stop="items.splice(index, 1)" variant="link" class="remove-btn p-0 position-absolute right">
              <b-icon icon="x"></b-icon>
            </b-button>
            <b-img
                :src="galleryItem.url"
                class="image-preview mr-2 mb-2">
              </b-img>
          </a>
        </div>
        <div>
        </div>
      </div>
      <b-button v-else v-b-modal.modal-lg variant="outline-secondary" class="plus-btn mr-2 mb-2" @click.prevent.stop="showModal = true">
        <b-icon icon="plus" font-scale="2"></b-icon>
      </b-button>
    </template>
    <span>
      <b-modal id="modal-lg" size="lg" title="Add New Image" :hide-footer="true">
        <image-upload-component @image-removed="removeImage" @image-uploaded="setUploadedImage"></image-upload-component>
        <b-form-group
            id="name-group"
            label="Title"
            :invalid-feedback="invalidFeedback"
            :state="state"
            label-for="name">
          <b-form-input
              id="name-input"
              required
              placeholder="Enter Image Title"
              v-model="item.name"
              :state="state"
              ></b-form-input>
<!--          <b-form-invalid-feedback>Enter a valid title with more than 3 characters</b-form-invalid-feedback>-->
          <b-button @click.prevent.stop="saveData" variant="success" class="float-right mt-3" :disabled='isDisabled'>Save Image</b-button>
        </b-form-group>
      </b-modal>
    </span>
  </div>
</template>

<script>
export default {
name: "AdminGalleryComponent",
  props: {
    value: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      items: this.value.items,
      item: {
        name: '',
        url: '',
      }
    }
  },
  computed: {
    state() {
      return this.item.name.length >= 4
    },
    invalidFeedback() {
      if (this.item.name.length > 0) {
        return 'Enter at least 4 characters.'
      }
      return 'Please enter title of image.'
    },
    isDisabled: function(){
      return !this.item.url || !this.item.name;
    }
  },
  methods: {
    saveData() {
      this.items.push({ ...this.item })
      this.$emit('input', {
        items: this.items
      })
      this.item = {
        name: '',
        url: ''
      }
      this.$bvModal.hide('modal-lg')
    },
    setUploadedImage(path) {
      this.item.url = path;
    },
    removeImage() {
      this.item.url = ''
    }
  }
}
</script>

<style scoped>
.image-preview {
  width:120px;
  height: 120px;
  object-fit: cover;
}
.plus-btn {
  width: 120px;
  height: 120px;
}
.remove-btn {
  font-size: 15px;
  right: 8px;
  background-color: red;
  color: white;
}
</style>
