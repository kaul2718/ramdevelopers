import Chart from 'chart.js/auto';
import { ref } from 'vue';

// Paleta de colores moderna y consistente
const colors = {
    primary: 'rgba(59, 130, 246, 0.8)',
    secondary: 'rgba(34, 197, 94, 0.8)',
    accent: 'rgba(168, 85, 247, 0.8)',
    warning: 'rgba(251, 146, 60, 0.8)',
    danger: 'rgba(239, 68, 68, 0.8)',
    info: 'rgba(14, 165, 233, 0.8)',
    success: 'rgba(34, 197, 94, 0.8)',
    chart: [
        'rgba(59, 130, 246, 0.7)',
        'rgba(34, 197, 94, 0.7)',
        'rgba(168, 85, 247, 0.7)',
        'rgba(251, 146, 60, 0.7)',
        'rgba(239, 68, 68, 0.7)',
        'rgba(14, 165, 233, 0.7)',
        'rgba(236, 72, 153, 0.7)',
        'rgba(139, 92, 246, 0.7)'
    ]
};

const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                padding: 20,
                font: { size: 12, weight: '500' },
                usePointStyle: true,
                pointStyle: 'circle'
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            borderRadius: 8,
            titleFont: { size: 13, weight: '600' },
            bodyFont: { size: 12 },
            displayColors: true,
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1
        }
    }
};

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
                        label: 'Usuarios',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: colors.primary,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(59, 130, 246, 1)'
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    };

    const initLeadsBySourceChart = (data) => {
        if (chartLeadsSourceCanvas.value && chartLeadsSource.value === null) {
            chartLeadsSource.value = new Chart(chartLeadsSourceCanvas.value, {
                type: 'doughnut',
                data: {
                    labels: data?.map(item => item.leadSou_name) || [],
                    datasets: [{
                        label: 'Leads',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: colors.chart,
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            ...commonOptions.plugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
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
                        label: 'Cantidad',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: colors.chart.slice(0, data?.length || 4),
                        borderColor: colors.chart.slice(0, data?.length || 4),
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(0, 0, 0, 0.1)'
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    };

    const initLeadsByStatusChart = (data) => {
        if (chartLeadsCanvas.value && chartLeads.value === null) {
            chartLeads.value = new Chart(chartLeadsCanvas.value, {
                type: 'doughnut',
                data: {
                    labels: data?.map(item => item.leadSta_name) || [],
                    datasets: [{
                        label: 'Leads',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: colors.chart,
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            ...commonOptions.plugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
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
                        label: 'Leads',
                        data: data?.map(item => item.total) || [],
                        backgroundColor: colors.chart,
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            ...commonOptions.plugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
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
