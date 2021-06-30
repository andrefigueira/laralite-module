<script>
import { Line } from "vue-chartjs";

export default {
    extends: Line,
    mounted() {
        this.renderChart(
            {
                labels: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                ],
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
                      data: [65, 59, 80, 81, 56, 55, 40, 55, 30, 80],
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
    }
};
</script>
<style scoped>

</style>
