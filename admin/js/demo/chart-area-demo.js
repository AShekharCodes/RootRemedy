document.addEventListener("DOMContentLoaded", function () {
    fetchCounts();
});

function fetchCounts() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetchCounts.php', true);  // Fetch data from PHP script
    xhr.onload = function () {
        if (xhr.status === 200) {
            var counts = JSON.parse(xhr.responseText);
            updateChart(counts);
        } else {
            console.error('Error fetching counts: ' + xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Request failed');
    };
    xhr.send();
}

function updateChart(counts) {
    var ctx = document.getElementById("myAreaChart").getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',  // Changed to 'bar'
        data: {
            labels: ["Plants", "Medicines", "Diseases", "Ayurvedic", "Herbal"],  // Added new categories
            datasets: [{
                label: "Count",
                backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#e74a3b"],  // Different colors for each bar
                hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf", "#f4b619", "#e02d1b"],
                borderColor: "#4e73df",
                data: [counts.plants, counts.medicines, counts.diseases, counts.ayurvedic, counts.herbal],  // Dynamic data from server
                maxBarThickness: 25  // Updated option to dataset
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
                y: {
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleMarginBottom: 10,
                    titleColor: '#6e707e',
                    titleFont: {
                        size: 14
                    },
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: {
                        x: 15,
                        y: 15
                    },
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem) {
                            return 'Count: ' + number_format(tooltipItem.raw);
                        }
                    }
                }
            }
        }
    });
}

function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
