<template>
  <div class="mt-4">
    <nav>
      <div class="nav nav-tabs mt-2" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" @click="show('Section1')" :style="currentTab === 'Section1' ? 'background-color: #ffffff' :'background-color: #F0F0F0'">Sales</button>
        <button class="nav-link" id="nav-profile-tab" @click="show('Section2')" :style="currentTab === 'Section2' ? 'background-color: #ffffff': 'background-color: #F0F0F0'">Scanner Log</button>
      </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
      <div v-if="currentTab === 'Section1'" id="Section1">
          <div class="page-section p-4">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <h2 class="dashboard-title">Sales</h2>
                <p class="dashboard-subtitle"></p>
              </div>
              <div class="col-md-3 col-sm-6">
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
              <div class="col-md-3 col-sm-6">
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
              </div>
            </div>
            <chart-component class="row" type="sales" label="Sales" :startDate="startDate" :endDate="endDate" key="first"></chart-component>
          </div>
      </div>
      <div v-if="currentTab === 'Section2'" id="Section2">
          <div class="page-section p-4">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <h2 class="dashboard-title">Scanner Log</h2>
                <p class="dashboard-subtitle"></p>
              </div>
              <div class="col-md-3 col-sm-6">
                <b-form-group id="discount-end-date-group" label="Select Start Date" label-for="start-date-input">
                  <b-input-group>
                    <b-input-group-append class="col-md-12 m-0 p-0">
                      <b-form-datepicker
                          id="start-date-input-2"
                          v-model="logStartDate"
                      ></b-form-datepicker>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-3 col-sm-6">
                <b-form-group id="discount-end-date-group" label="Select End Date" label-for="end-date-input">
                  <b-input-group>
                    <b-input-group-append class="col-md-12 m-0 p-0">
                      <b-form-datepicker
                          id="end-date-input-2"
                          v-model="logEndDate"
                      ></b-form-datepicker>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
              </div>
            </div>
            <chart-component class="row" type="scannerlog" label="Ticket Scanned" :startDate="logStartDate" :endDate="logEndDate" key="second"></chart-component>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import ChartComponent from "./ChartComponent";
export default {
  components: {ChartComponent},
  data() {
    return {
      startDate: '',
      endDate: '',
      logStartDate: '',
      logEndDate: '',
      currentTab: 'Section1'
    }
  },
  methods: {
    show(id) {
      this.currentTab = id
    },
  }
}

</script>

<style scoped>
.nav-tabs {
  border-bottom: none !important;
}

.page-section {
  border-radius: initial !important;
}
</style>

