<template>
    <div>
        <div class="page-section p-4">
          <div class="row">
            <div class="col-6">
              <h2 class="dashboard-title">Today's Sale</h2>
              <p class="dashboard-subtitle"></p>
            </div>
<!--            <div class="col-3">
              <b-form-group id="discount-end-date-group" label="Select Start Date" label-for="start-date-input">
                <b-input-group>
                  <b-input-group-append class="col-md-12 m-0 p-0">
                    <b-form-datepicker
                        id="start-date-input"
                        v-model="startDate"
                    ></b-form-datepicker>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>
            </div>
            <div class="col-3">
              <b-form-group id="discount-end-date-group" label="Select End Date" label-for="end-date-input">
                <b-input-group>
                  <b-input-group-append class="col-md-12 m-0 p-0">
                    <b-form-datepicker
                        id="end-date-input"
                        v-model="endDate"
                    ></b-form-datepicker>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>
            </div>-->
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
}

</script>
