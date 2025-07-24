<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-outline card-primary" style="cursor: pointer;" onclick="window.location.href='?page=product'">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-box fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Total Products</h5>
                            <h2 class="text-primary" id="total_products">0</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-outline card-success" style="cursor: pointer;" onclick="window.location.href='?page=orders'">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-shopping-cart fa-3x text-success"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Orders Today</h5>
                            <h2 class="text-success" id="orders_today">0</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-outline card-info" style="cursor: pointer;" onclick="window.location.href='?page=user_list'">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-users fa-3x text-info"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Total Users</h5>
                            <h2 class="text-info" id="total_users">0</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-outline card-primary mt-4">
        <div class="card-header">
            <h3 class="card-title">Today's Paid Products Overview</h3>
            <div class="float-right d-flex align-items-center">
                <input type="date" id="report_date" class="form-control form-control-sm mr-2" value="<?php echo date('Y-m-d'); ?>">
                <button class="btn btn-sm btn-primary" id="downloadReportBtn">Download Report</button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-responsive" style="position:relative; width:100%; min-height:300px;">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="mt-4" id="totalsSection" style="display:none;">
                <h5>Overall Totals</h5>
                <p><strong>Total Quantity Sold:</strong> <span id="overall_quantity">0</span></p>
                <p><strong>Total Sales:</strong> ₱<span id="overall_sales">0.00</span></p>
                <h5 class="mt-3">Product Breakdown</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity Sold</th>
                                <th>Total Sales (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="productBreakdown"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        function loadSalesChart() {
            $.ajax({
                url: 'get_sales_data.php',
                method: 'GET',
                success: function(data) {
                    var ctx = document.getElementById('salesChart').getContext('2d');
                    if(window.salesChart instanceof Chart) {
                        window.salesChart.destroy();
                    }
                    window.salesChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Quantity Sold',
                                data: data.quantities,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: "Today's Paid Products Sold (Quantity)"
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Quantity Sold: ' + context.parsed.y;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Quantity Sold'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Product Name'
                                    }
                                }
                            }
                        }
                    });

                    // Show totals section
                    if(data.labels.length > 0) {
                        $('#totalsSection').show();
                        $('#overall_quantity').text(data.overall_quantity);
                        $('#overall_sales').text(Number(data.overall_sales).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                        // Fill product breakdown table
                        var rows = '';
                        for(var i=0; i<data.labels.length; i++) {
                            rows += '<tr>' +
                                '<td>' + data.labels[i] + '</td>' +
                                '<td>' + data.quantities[i] + '</td>' +
                                '<td>₱' + Number(data.sales[i]).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                                '</tr>';
                        }
                        $('#productBreakdown').html(rows);
                    } else {
                        $('#totalsSection').hide();
                    }
                }
            });
        }

        // Load chart initially
        loadSalesChart();

        // Function to load dashboard statistics
        function loadDashboardStats() {
            $.ajax({
                url: 'get_dashboard_stats.php',
                method: 'GET',
                success: function(data) {
                    $('#total_products').text(data.total_products);
                    $('#orders_today').text(data.orders_today);
                    $('#total_users').text(data.total_users);
                }
            });
        }

        // Load dashboard stats initially
        loadDashboardStats();

        // Refresh dashboard stats every 15 seconds
        setInterval(loadDashboardStats, 15000);

        // Refresh chart every 15 seconds for real-time updates
        setInterval(loadSalesChart, 15000);

        // Download report logic
        $('#downloadReportBtn').on('click', function() {
            var selectedDate = $('#report_date').val();
            if (!selectedDate) {
                alert('Please select a date.');
                return;
            }
            // Create a hidden iframe to trigger download
            var url = 'generate_sales_report.php?date=' + encodeURIComponent(selectedDate);
            var link = document.createElement('a');
            link.href = url;
            link.download = 'sales_report_' + selectedDate + '.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
</script>
