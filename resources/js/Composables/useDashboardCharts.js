import Chart from 'chart.js/auto';
import { ref } from 'vue';

export function useDashboardCharts() {
    const chartUsers = ref(null);
    const chartLeads = ref(null);
    const chartLeadsSource = ref(null);
    const chartUsersProfile = ref(null);
    const chartUserLeads = ref(null);

    const chartUsersCanvas = ref(null);
    const chartLeadsCanvas = ref(null);
    const chartLeadsSourceCanvas = ref(null);
    const chartUsersProfileCanvas = ref(null);
    const chartUserLeadsCanvas = ref(null);

    const initUsersByCountryChart = (data) => {
        if (chartUsersCanvas.value && chartUsers.value === null) {
            chartUsers.value = new Chart(chartUsersCanvas.value, {
                type: 'bar',
                data: {
                    labels: data?.map(item => item.ctry_name) || [],
                    datasets: [{
                        label: 'Usuarios por País',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    };

    const initLeadsBySourceChart = (data) => {
        if (chartLeadsSourceCanvas.value && chartLeadsSource.value === null) {
            chartLeadsSource.value = new Chart(chartLeadsSourceCanvas.value, {
                type: 'pie',
                data: {
                    labels: data?.map(item => item.leadSou_name) || [],
                    datasets: [{
                        label: 'Leads por Fuente',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(199, 199, 199, 0.6)',
                            'rgba(83, 102, 255, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    };

    const initUsersByProfileChart = (data) => {
        if (chartUsersProfileCanvas.value && chartUsersProfile.value === null) {
            chartUsersProfile.value = new Chart(chartUsersProfileCanvas.value, {
                type: 'bar',
                data: {
                    labels: data?.map(item => item.role_name) || [],
                    datasets: [{
                        label: 'Usuarios por Perfil',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    };

    const initLeadsByStatusChart = (data, isDoughnut = true) => {
        if (chartLeadsCanvas.value && chartLeads.value === null) {
            chartLeads.value = new Chart(chartLeadsCanvas.value, {
                type: isDoughnut ? 'doughnut' : 'doughnut',
                data: {
                    labels: data?.map(item => item.leadSta_name) || [],
                    datasets: [{
                        label: 'Leads por Estado',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    };

    const initUserLeadsByStatusChart = (data) => {
        if (chartUserLeadsCanvas.value && chartUserLeads.value === null) {
            chartUserLeads.value = new Chart(chartUserLeadsCanvas.value, {
                type: 'doughnut',
                data: {
                    labels: data?.map(item => item.leadSta_name) || [],
                    datasets: [{
                        label: 'Mis Leads por Estado',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    };

    return {
        chartUsers,
        chartLeads,
        chartLeadsSource,
        chartUsersProfile,
        chartUserLeads,
        chartUsersCanvas,
        chartLeadsCanvas,
        chartLeadsSourceCanvas,
        chartUsersProfileCanvas,
        chartUserLeadsCanvas,
        initUsersByCountryChart,
        initLeadsBySourceChart,
        initUsersByProfileChart,
        initLeadsByStatusChart,
        initUserLeadsByStatusChart
    };
}
