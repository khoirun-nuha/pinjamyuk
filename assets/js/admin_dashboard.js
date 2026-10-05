document.addEventListener("DOMContentLoaded", function () {
    // Ensure data is available
    if (typeof monthLabels === 'undefined' || typeof loanData === 'undefined') return;

    // Area Chart
    var ctx = document.getElementById("myAreaChart");
    if (ctx) {
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: "Peminjaman",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: loanData,
                    fill: true,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { grid: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } },
                    y: { ticks: { maxTicksLimit: 5, padding: 10, callback: function (value) { return value; } }, grid: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } },
                }
            }
        });
    }

    // Pie Chart
    var ctxPie = document.getElementById("myPieChart");
    if (ctxPie && typeof stockReady !== 'undefined' && typeof stockBorrowed !== 'undefined') {
        var myPieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ["Tersedia", "Dipinjam"],
                datasets: [{
                    data: [stockReady, stockBorrowed],
                    backgroundColor: ['#1cc88a', '#f6c23e'],
                    hoverBackgroundColor: ['#17a673', '#dda20a'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                cutout: '70%',
            },
        });
    }
});
