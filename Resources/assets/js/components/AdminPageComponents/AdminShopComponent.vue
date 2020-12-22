<template>
  <div>
    <b-form-group
        description="Quantity of items that should be displayed"
        label="How many items should be shown?"
        label-for="max-visible-items"
    >
      <b-form-input id="max-visible-items" v-model="maxVisibleItems" trim></b-form-input>
    </b-form-group>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        maxVisibleItems: 3
      }
    }
  }
</script>

<style lang="scss">

</style>