@section('title', 'Dashboard | ByteVault')

<x-app-layout>
    <div class="container">
        <div class="py-8">
            <div class="dashboard-container mx-auto">
                <div class="overflow-hidden">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mx-1 mb-3">
                            <h3>Hello, <strong>{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</strong>!</h3>
                            <x-primary-button href="{{ route('reports.inventory') }}" class="py-1">
                                <span class="material-icons-outlined">summarize</span>
                                Report
                            </x-primary-button>
                        </div>

                        <!-- Row for summary cards -->
                        <div class="row mt-3">
                            <!-- Total Sales -->
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card shadow-sm rounded-3 p-2 d-flex flex-column justify-content-center align-items-center"
                                    style="min-height: 100px;">
                                    <span class="material-icons-outlined icon-summary total-sales">payments</span>
                                    <h4 class="fw-bold mb-0 mt-1">{{ $totalSales }}</h4>
                                    <p class="fw-semibold text-muted mb-0 fs-6">Sales</p>
                                </div>
                            </div>
                            <!-- Total Orders -->
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card shadow-sm rounded-3 p-2 d-flex flex-column justify-content-center align-items-center"
                                    style="min-height: 100px;">
                                    <span class="material-icons-outlined icon-summary total-orders">shopping_cart</span>
                                    <h4 class="fw-bold mb-0 mt-1">{{ $totalOrders }}</h4>
                                    <p class="fw-semibold text-muted mb-0 fs-6">Orders</p>
                                </div>
                            </div>
                            <!-- Total Products in Stock -->
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card shadow-sm rounded-3 p-2 d-flex flex-column justify-content-center align-items-center"
                                    style="min-height: 100px;">
                                    <span
                                        class="material-icons-outlined icon-summary products-in-stock">inventory</span>
                                    <h4 class="fw-bold mb-0 mt-1">{{ $totalProductsInStock }}</h4>
                                    <p class="fw-semibold text-muted mb-0 fs-6">Stock</p>
                                </div>
                            </div>
                            <!-- Low Stock Products -->
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card shadow-sm rounded-3 p-2 d-flex flex-column justify-content-center align-items-center"
                                    style="min-height: 100px;">
                                    <span class="material-icons-outlined icon-summary low-stock">warning</span>
                                    <h4 class="fw-bold mb-0 mt-1">{{ $lowStockProducts }}</h4>
                                    <p class="fw-semibold text-muted mb-0 fs-6">Low Stock</p>
                                </div>
                            </div>
                        </div>

                        <!-- Row for Category Distribution and Sales Overview -->
                        <div class="row mt-3">
                            <!-- Category Distribution -->
                            <div class="col-12 col-md-5 mb-3">
                                <div class="card shadow-sm rounded-3 p-2" style="height: 300px;">
                                    <div class="card-body d-flex flex-column h-100">
                                        <h6 class="card-title fw-semibold mb-2 d-flex align-items-center">
                                            <span
                                                class="material-icons-outlined icon-title category-distribution me-1">pie_chart</span>
                                            Categories
                                        </h6>
                                        <div class="d-flex flex-grow-1 align-items-center">
                                            <div class="me-2" style="min-width: 100px; max-width: 100px;">
                                                @foreach (json_decode($categoryLabels) as $index => $label)
                                                    <div class="d-flex align-items-center mb-1">
                                                        <span
                                                            style="width: 15px; height: 15px; display: inline-block; margin-right: 5px; background-color: {{ ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'][$index] }}; border-radius: 3px;"></span>
                                                        <span class="fs-7 fw-medium text-truncate">{{ $label }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="flex-grow-1">
                                                <canvas id="categoryDistributionChart"
                                                    style="max-height: 200px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sales Overview -->
                            <div class="col-12 col-md-7 mb-3">
                                <div class="card shadow-sm rounded-3 p-2" style="height: 300px;">
                                    <div class="card-body d-flex flex-column h-100">
                                        <h6 class="card-title fw-semibold mb-2 d-flex align-items-center">
                                            <span
                                                class="material-icons-outlined icon-title sales-overview me-1">trending_up</span>
                                            Sales
                                        </h6>
                                        <div class="flex-grow-1">
                                            <canvas id="salesChart" style="max-height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row for Sales by Category and Top Sales -->
                        <div class="row mt-3 mb-4">
                            <!-- Sales by Category -->
                            <div class="col-12 col-md-8 mb-3">
                                <div class="card shadow-sm rounded-3 p-3" style="min-height: 300px;">
                                    <div class="card-body d-flex flex-column h-100">
                                        <h6 class="card-title fw-semibold mb-2 d-flex align-items-center">
                                            <span
                                                class="material-icons-outlined icon-title sales-by-category me-1">bar_chart</span>
                                            Sales by Category
                                        </h6>
                                        <div class="flex-grow-1">
                                            <canvas id="categorySalesChart" style="max-height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Top Sales -->
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card shadow-sm rounded-3 p-3" style="min-height: 300px;">
                                    <div class="card-body">
                                        <h6 class="card-title fw-semibold mb-2 d-flex align-items-center">
                                            <span class="material-icons-outlined icon-title top-sales me-1">star</span>
                                            Top Sales
                                        </h6>
                                        @if (count($topSellingProducts) > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($topSellingProducts as $product)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center py-1">
                                                        <div>
                                                            <strong class="fs-7">{{ $product->productName }}</strong><br>
                                                            <small
                                                                class="text-muted fs-8">₱{{ number_format($product->total_revenue, 2) }}</small>
                                                        </div>
                                                        <span
                                                            class="badge bg-primary rounded-pill fs-8">{{ $product->total_quantity }}
                                                            sold</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted fs-7">No sales data.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sales Overview Line Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! $salesLabels !!},
                    datasets: [{
                        label: 'Total Sales (₱)',
                        data: {!! $salesData !!},
                        borderColor: '#4BC0C0',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 2,
                        pointBackgroundColor: '#4BC0C0',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: { display: false },
                            grid: { display: false },
                            ticks: {
                                color: '#2a5055',
                                font: { size: 12 }
                            }
                        },
                        y: {
                            title: { display: false },
                            grid: { display: false },
                            ticks: {
                                color: '#2a5055',
                                font: { size: 12 },
                                maxTicksLimit: 4,
                                callback: function (value) {
                                    return '₱' + value.toLocaleString('en-US');
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#faf8f5',
                            titleFont: { size: 12 },
                            bodyFont: { size: 12 },
                            titleColor: '#2a5055',
                            bodyColor: '#2a5055',
                            padding: 6,
                            callbacks: {
                                label: function (context) {
                                    return `₱${context.raw.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
                                }
                            }
                        }
                    }
                }
            });

            // Category Distribution Doughnut Chart
            const categoryCtx = document.getElementById('categoryDistributionChart').getContext('2d');
            const categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! $categoryLabels !!},
                    datasets: [{
                        data: {!! $categoryData !!},
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        borderWidth: 1,
                        borderColor: '#fff',
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            },
                            backgroundColor: '#faf8f5',
                            titleFont: { size: 12 },
                            bodyFont: { size: 12 },
                            titleColor: '#2a5055',
                            bodyColor: '#2a5055',
                            padding: 6
                        }
                    },
                    layout: {
                        padding: 5
                    }
                }
            });

            // Sales by Category Bar Chart
            const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
            const categorySalesChart = new Chart(categorySalesCtx, {
                type: 'bar',
                data: {
                    labels: {!! $salesCategoryLabels !!},
                    datasets: [{
                        label: 'Sales (₱)',
                        data: {!! $salesCategoryData !!},
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        borderWidth: 1,
                        borderColor: '#fff',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: { display: false },
                            grid: { display: false },
                            ticks: {
                                color: '#2a5055',
                                font: { size: 12 }
                            }
                        },
                        y: {
                            title: { display: false },
                            grid: { display: false },
                            ticks: {
                                color: '#2a5055',
                                font: { size: 12 },
                                maxTicksLimit: 4,
                                callback: function (value) {
                                    return '₱' + value.toLocaleString('en-US');
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    return `${label}: ₱${value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                                }
                            },
                            backgroundColor: '#faf8f5',
                            titleFont: { size: 12 },
                            bodyFont: { size: 12 },
                            titleColor: '#2a5055',
                            bodyColor: '#2a5055',
                            padding: 6
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>