<script>
    document.addEventListener('DOMContentLoaded', function() {

        const chartElement = document.querySelector('#ventasChart');
        if (!chartElement) return;

        const cardColor = config.colors.cardColor;
        const headingColor = config.colors.headingColor;
        const labelColor = config.colors.textMuted;
        const borderColor = config.colors.borderColor;

        const chartConfig = {
            chart: {
                height: 320,
                type: 'area',
                toolbar: false,
                dropShadow: {
                    enabled: true,
                    top: 14,
                    left: 2,
                    blur: 3,
                    color: config.colors.primary,
                    opacity: 0.15,
                }
            },
            series: [{
                name: 'Ventas',
                data: @json($totales)
            }],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            colors: [
                config.colors.primary
            ],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    gradientToColors: [cardColor],
                    opacityTo: 0.1,
                    stops: [0, 100],
                }
            },
            grid: {
                borderColor: borderColor,
                strokeDashArray: 8,
                padding: {
                    top: -10,
                    bottom: -8,
                    left: 0,
                    right: 8
                }
            },
            xaxis: {
                categories: @json($fechas),
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '13px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return 'S/ ' + value;
                    },
                    style: {
                        colors: labelColor,
                        fontSize: '13px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'S/ ' + value;
                    }
                }
            },
            legend: {
                show: false
            }
        };
        const chart = new ApexCharts(
            chartElement,
            chartConfig
        );
        chart.render();
    });
</script>
