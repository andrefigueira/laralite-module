<script>
import { Line } from "vue-chartjs";
import axios  from 'axios';

export default {
    extends: Line,
  props: {
    startDate: {
      type: String,
      default: ""
    },
    endDate: {
      type: String,
      default: ""
    },
  },
    mounted() {
      this.fetchScannerLogData()
    },
  watch: {
    startDate(newValue, oldValue) {
      this.fetchScannerLogData()
    },
    endDate(newValue, oldValue) {
      this.fetchScannerLogData()
    }
  },
    methods: {
      fetchScannerLogData() {
        axios.get(`/admin/scannerlog?startDate=${this.startDate}&endDate=${this.endDate}`).then((response) => {
          let data = response.data;
          if(data) {
            var array = Object.keys(response.data)
                .map(function(key) {
                  return (response.data[key].length);
                });

            // console.log("My Array  : "+array);

            this.renderChart({
                  labels: Object.keys(response.data),
                  datasets: [{
                    label: 'Ticket Scanned',
                    backgroundColor: '#5664D2',
                    data: array
                  }]
                },
                {
                  responsive: true,
                  maintainAspectRatio: false
                }
            )
          }
          else {
            console.log('No data');
          }
        });
      }
    },
};
</script>
<style scoped>

</style>
