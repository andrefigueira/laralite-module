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
        axios.get(`/admin/sales?startDate=${this.startDate}&endDate=${this.endDate}`).then((response) => {
          let data = response.data;
          console.log(response.data);
          if(data) {
            var array = Object.keys(response.data)
                .map(function(key) {
                  return (response.data[key].length);
                });

            console.log("My Array  : "+array);

            this.renderChart({
                  labels: Object.keys(response.data),
                  datasets: [{
                    label: 'Revenue',
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
    /*mounted() {
       const salesMonth= this.month;
       const salesAHT = this.day;
       console.log(salesMonth);
       console.log(salesAHT);
        this.renderChart(
            {
                labels:  ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                    {
                      label: "Revenue",
                      fill: true,
                      lineTension: 0.5,
                      backgroundColor: "rgba(85, 110, 230, 0.2)",
                      borderColor: "#5664d2",
                      borderCapStyle: "butt",
                      borderDash: [],
                      borderDashOffset: 0.0,
                      borderJoinStyle: "miter",
                      pointBorderColor: "#5664d2",
                      pointBackgroundColor: "#fff",
                      pointBorderWidth: 1,
                      pointHoverRadius: 5,
                      pointHoverBackgroundColor: "#5664d2",
                      pointHoverBorderColor: "#fff",
                      pointHoverBorderWidth: 2,
                      pointRadius: 1,
                      pointHitRadius: 10,
                      data: salesAHT,
                    }
                ]
            },
            {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: false,
                    text: "Revenue"
                }
            },
            {
              defaultFontColor: "#8791af",
              scales: {
                xAxes: [
                  {
                    gridLines: {
                      color: "rgba(166, 176, 207, 0.1)"
                    }
                  }
                ],
                yAxes: [
                  {
                    ticks: {
                      max: 100,
                      min: 20,
                      stepSize: 10
                    },
                    gridLines: {
                      color: "rgba(166, 176, 207, 0.1)"
                    }
                  }
                ]
              }
            }
        );
    }*/
};
</script>
<style scoped>

</style>
