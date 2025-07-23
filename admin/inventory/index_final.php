<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="row mb-3">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Products Ordered Today</span>
                <span class="info-box-number" id="stock_out_today">0</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Most Purchased Item</span>
                <span class="info-box-number" id="most_purchased_item">N/A</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-file-download"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Download Report</span>
                <button class="btn btn-light btn-sm" id="downloadReportBtn">Download</button>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Inventory</h3>
		<div class="card-tools">
			<div class="d-flex align-items-center">
				<input type="date" id="inventory_report_date" class="form-control form-control-sm mr-2" value="<?php echo date('Y-m-d'); ?>">
				<button class="btn btn-success btn-sm" id="downloadInventoryReportBtn">
					<i class="fas fa-download"></i> Download Report
				</button>
				<a href="?page=inventory/manage_inventory" class="btn btn-flat btn-primary ml-2"><span class="fas fa-plus"></span> Create New</a>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="35%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th>Price</th>
						<th>Stock</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT i.*,p.product_name as product from `inventory` i inner join `products` p on p.id = i.product_id order by p.product_name asc ");
						while($row = $qry->fetch_assoc()):
						$stock_status = '';
						$status_text = '';
						if($row['quantity'] <= 0) {
							$stock_status = 'bg-danger';
							$status_text = 'Out of Stock';
						} else if($row['quantity'] <= 5) {
							$stock_status = 'bg-warning';
							$status_text = 'Low Stock';
						} else {
							$stock_status = 'bg-success';
							$status_text = 'In Stock';
						}
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['product'] ?></td>
							<td class="text-right">₱<?php echo number_format($row['price']) ?></td>
							<td class="text-right"><?php echo $row['quantity'] ?></td>
							<td class="text-center">
								<span class="badge <?php echo $stock_status ?>"><?php echo $status_text ?></span>
							</td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="?page=inventory/manage_inventory&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.table').dataTable();

        // Load stats and chart
        function loadStats() {
            $.ajax({
                url: '../get_dashboard_stats.php',
                method: 'GET',
                success: function(data) {
                    $('#stock_out_today').text(data.stock_out_today || 0);
                    $('#most_purchased_item').text(data.most_purchased_item || 'N/A');
                }
            });
        }

        function loadSalesChart() {
            $.ajax({
                url: '../get_sales_data.php',
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
                }
            });
        }

        // Download report button click
        $('#downloadReportBtn').on('click', function() {
            var selectedDate = $('#report_date').val();
            if (!selectedDate) {
                selectedDate = '<?php echo date('Y-m-d'); ?>';
            }
            var url = 'generate_daily_orders_pdf.php?date=' + encodeURIComponent(selectedDate);
            window.open(url, '_blank');
        });

        // Inventory report download functionality
        $('#downloadInventoryReportBtn').on('click', function() {
            var selectedDate = $('#inventory_report_date').val();
            if (!selectedDate) {
                alert('Please select a date.');
                return;
            }
            var url = 'generate_inventory_report.php?date=' + encodeURIComponent(selectedDate);
            window.open(url, '_blank');
        });

        // Initial load
        loadStats();
        loadThe file path is admin/inventory/index_new.php, so I will update that file.

<edit_file>
<path>admin/inventory/index_new.php</path>
<content>
<<<<<<< SEARCH
            var url = 'generate_inventory_report.php?date=' + encodeURIComponent(selectedDate);
=======
            var url = 'generate_inventory_report_html.php?date=' + encodeURIComponent(selectedDate);
