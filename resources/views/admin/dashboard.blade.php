@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">📊 Dashboard Thống Kê</h4>

    <div class="row g-4">
        <!-- Doanh thu theo tháng -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-light fw-bold">📈 Doanh thu theo tháng</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Đơn hàng theo trạng thái -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-light fw-bold">📦 Đơn hàng theo trạng thái</div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top sản phẩm bán chạy -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-light fw-bold">🔥 Top 5 SP bán chạy</div>
                <div class="card-body">
                    <canvas id="topProductsChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Cảnh báo tồn kho thấp -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-light fw-bold">⚠️ Cảnh báo tồn kho thấp</div>
                <div class="card-body">
                    <canvas id="lowStockChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Global defaults
        Chart.defaults.font.family = 'Nunito, sans-serif';
        Chart.defaults.color = '#495057';

        // 📈 Doanh thu theo tháng (Line Chart with gradient)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctxRevenue.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(0,123,255,0.4)');
        gradient.addColorStop(1, 'rgba(0,123,255,0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: [...Array(12).keys()].map(i => `Tháng ${i + 1}`),
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: @json(array_values($revenueData)),
                    backgroundColor: gradient,
                    borderColor: '#007bff',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#007bff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        grid: { color: '#e9ecef', borderDash: [4, 4] },
                        ticks: {
                            callback: val => new Intl.NumberFormat('vi-VN', { notation: 'compact', compactDisplay: 'short' }).format(val)
                        }
                    }
                }
            }
        });

        // 📦 Đơn hàng theo trạng thái (Doughnut Chart)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($orderStatuses)),
                datasets: [{
                    data: @json(array_values($orderStatuses)),
                    backgroundColor: [
                        'rgba(40,167,69,0.6)',
                        'rgba(255,193,7,0.6)',
                        'rgba(220,53,69,0.6)',
                        'rgba(108,117,125,0.6)'
                    ],
                    borderColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6c757d'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                cutout: '50%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 16, usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.formattedValue}`
                        }
                    }
                }
            }
        });

        // 🔥 Top sản phẩm bán chạy (Horizontal Bar Chart)
        const ctxTop = document.getElementById('topProductsChart').getContext('2d');
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: @json($topProductNames),
                datasets: [{
                    label: 'Số lượng bán',
                    data: @json($topProductQuantities),
                    backgroundColor: '#17a2b8',
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        grid: { color: '#e9ecef', borderDash: [4, 4] },
                        beginAtZero: true
                    },
                    y: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': ' + ctx.parsed.x
                        }
                    }
                }
            }
        });

        // ⚠️ Cảnh báo tồn kho thấp (Vertical Bar Chart)
        const ctxLow = document.getElementById('lowStockChart').getContext('2d');
        new Chart(ctxLow, {
            type: 'bar',
            data: {
                labels: @json($lowStockNames),
                datasets: [{
                    label: 'Tồn kho',
                    data: @json($lowStockQuantities),
                    backgroundColor: '#dc3545',
                    borderRadius: 4,
                    maxBarThickness: 20
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: { grid: { color: '#e9ecef', borderDash: [4, 4] }, beginAtZero: true },
                    y: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': ' + ctx.parsed.x
                        }
                    }
                }
            }
        });
    });
</script>
@endpush