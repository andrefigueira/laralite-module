<template>
    <div>
        <div class="page-section p-4">
          <div class="row">
            <div class="col-6">
              <h2 class="dashboard-title">Today's Sale</h2>
              <p class="dashboard-subtitle">{{ currentDate() }}</p>
            </div>
          </div>


            <sales-chart-component class="row" :startDate="startDate" :endDate="endDate"></sales-chart-component>

        </div><!-- End page section -->
    </div>
</template>

<script>
export default {
  data() {
    return {
      startDate: '',
      endDate: ''
    }
  },
  methods: {
    currentDate() {
      const current = new Date();
      const date = `${current.getDate()}-${current.getMonth()+1}-${current.getFullYear()}`;
      return date;
    }
  },
}

</script>
