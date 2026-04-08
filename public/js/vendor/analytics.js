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
            data: analyticsData.revenueData,
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

const statusColors = ['#f29d62', '#e67e4d', '#f3c49e', '#f7dcc5', '#d4a574', '#c98b5a', '#b87340'];

const statusChart = new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: analyticsData.statusLabels,
        datasets: [{
            data: analyticsData.statusData,
            backgroundColor: statusColors.slice(0, analyticsData.statusLabels.length),
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
        labels: analyticsData.dailyLabels,
        datasets: [
            {
                label: 'Orders',
                data: analyticsData.dailyOrders,
                backgroundColor: '#f29d62',
                borderRadius: 8
            },
            {
                label: 'New Customers',
                data: analyticsData.dailyCustomers,
                backgroundColor: '#f7dcc5',
                borderRadius: 8
            }
        ]
    },
    options: chartCommonOptions
});
