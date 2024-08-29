
    document.addEventListener("DOMContentLoaded", function () {
        fetchCountsForPieChart();
    });

    function fetchCountsForPieChart() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetchCounts.php', true);  // Fetch data from PHP script
        xhr.onload = function () {
            if (xhr.status === 200) {
                var counts = JSON.parse(xhr.responseText);
                updatePieChart(counts);
            } else {
                console.error('Error fetching counts: ' + xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error('Request failed');
        };
        xhr.send();
    }

    function updatePieChart(counts) {
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Plants", "Medicines", "Diseases"],  // Updated labels
                datasets: [{
                    data: [counts.plants, counts.medicines, counts.diseases],  // Dynamic data
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    }

