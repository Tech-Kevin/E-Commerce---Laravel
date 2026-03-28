const chartCommonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                color: '#6d5c53',
                font: { size: 12, weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#2f241f',
            titleColor: '#ffffff',
            bodyColor: '#ffffff',
            padding: 12,
            cornerRadius: 10
        }
    },
    scales: {
        x: {
            ticks: { color: '#8a7769' },
            grid:  { display: false }
        },
        y: {
            ticks: { color: '#8a7769' },
            grid:  { color: '#f2e7dc' }
        }
    }
};

const revenueChart = new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Revenue',
            data: [12000, 18000, 15000, 22000, 26000, 24000, 30000, 34000, 32000, 38000, 42000, 46000],
            borderColor: '#e67e4d',
            backgroundColor: 'rgba(230, 126, 77, 0.12)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#f29d62',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 4
        }]
    },
    options: chartCommonOptions
});

const trafficChart = new Chart(document.getElementById('trafficChart'), {
    type: 'doughnut',
    data: {
        labels: ['Direct', 'Social', 'Search', 'Ads'],
        datasets: [{
            data: [38, 24, 21, 17],
            backgroundColor: ['#f29d62', '#e67e4d', '#f3c49e', '#f7dcc5'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#2f241f',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                padding: 12,
                cornerRadius: 10
            }
        }
    }
});

const ordersCustomersChart = new Chart(document.getElementById('ordersCustomersChart'), {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [
            {
                label: 'Orders',
                data: [32, 45, 38, 52, 60, 48, 55],
                backgroundColor: '#f29d62',
                borderRadius: 8
            },
            {
                label: 'Customers',
                data: [20, 30, 24, 35, 42, 33, 39],
                backgroundColor: '#f7dcc5',
                borderRadius: 8
            }
        ]
    },
    options: chartCommonOptions
});
