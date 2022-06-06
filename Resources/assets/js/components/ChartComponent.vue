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
      type: {
        type: String,
        required: true
      },
      label: {
        type: String,
        default: "Enter Label"
      }
    },
    mounted() {
      console.log("Sales Mounted")
      this.fetchData()
    },
  watch: {
    startDate(newValue, oldValue) {
      this.fetchData()
    },
    endDate(newValue, oldValue) {
      this.fetchData()
    }
  },
    methods: {
      fetchData() {
        axios.get(`/admin/${this.type}?startDate=${this.startDate}&endDate=${this.endDate}`).then((response) => {
          let data = response.data;
          if(data) {
            var array = Object.keys(response.data)
                .map(function(key) {
                  return (response.data[key].length);
                });

            this.renderChart({
                  labels: Object.keys(response.data),
                  datasets: [{
                    label: this.label,
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
