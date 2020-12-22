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
                    "July"
                ],
                datasets: [
                    {
                        label: "Revenue",
                        data: [2, 10, 5, 9, 0, 6, 20],
                        backgroundColor: "transparent",
                        borderColor: "rgba(0, 0, 0, 0.50)",
                        pointBackgroundColor: "rgba(0, 0, 0, 1)"
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
            }
        );
    }
};
</script>