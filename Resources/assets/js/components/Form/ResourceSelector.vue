<template>
  <div>
    <v-select class="mb-3" id="primary-option" label="name" v-model="selected" :options="options"
              :clearable="false"></v-select>
  </div>
</template>

<script>
export default {
  name: "ResourceSelector",
  props: {
    value: {
      required: true,
      type: String,
    },
    type: {
      required: true,
      type: String,
    }
  },
  data() {
    return {
      options: [],
    }
  },
  beforeMount() {
    if (typeof this[this.type] !== "function") {
      throw Error('Invalid prop `type` passed to Selector component');
    }
    this[this.type]();
  },
  computed: {
    selected: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.$emit('input', newValue)
      }
    }
  },
  methods: {
    productTypes() {
      axios.get('/api/product/types', {withCredentials: true}).then(response => {
        this.options = response.data.data;
        console.log(this.options);

        if (this.selected === undefined) {
          this.selected = this.options[0];
        } else {
          this.selected = this.options.filter((productType) => {
            return productType.id === this.selected;
          })[0];
        }
      }).catch(error => {
        // handle error
      });
    },
    productCategory() {
      axios.get('/api/product-category-list', {withCredentials: true}).then(response => {
        this.options = response.data;
         console.log(this.options);
        if (this.selected === undefined) {
          this.selected = this.options[0];
        } else {
          this.selected = this.options.filter((productCategory) => {
            return productCategory.id === this.selected;
          })[0];
        }
      }).catch(error => {
        // handle error
      });
    },
  }
}
</script>

<style scoped>

</style>