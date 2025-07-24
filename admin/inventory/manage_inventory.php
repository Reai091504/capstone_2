<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `inventory` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="row mb-3">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stock Out Today</span>
                <span class="info-box-number" id="stock_out_today">0</span>
            </div>
        </div>
    </div>
    <!-- Removed Most Purchased Item info box -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Daily Deducted Items Report</span>
                <button class="btn btn-light btn-sm" id="downloadDailyReportBtn">Download</button>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
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
        <h3 class="card-title">Daily Sales Graph</h3>
        <div class="float-right d-flex align-items-center">
            <input type="date" id="report_date" class="form-control form-control-sm mr-2" value="<?php echo date('Y-m-d'); ?>">
        </div>
    </div>
    <div class="card-body">
        <canvas id="salesChart" style="min-height: 300px;"></canvas>
    </div>
</div>
<div class="card card-outline card-info mt-4">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Update ": "Create New " ?> Inventory</h3>
	</div>
	<div class="card-body">
		<form action="" id="inventory-form">
			<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
			<div class="form-group">
				<label for="product_id" class="control-label">Product</label>
                <select name="product_id" id="product_id" class="custom-select select2" required>
                    <option value=""></option>
                    <?php
                        $qry = $conn->query("SELECT * FROM `products` order by product_name asc");
                        while($row= $qry->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($product_id) && $product_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['product_name'] ?></option>
                    <?php endwhile; ?>
                </select>
			</div>
            <div class="form-group">
				<label for="quantity" class="control-label">Beginning Quantity</label>
                <input type="number" class="form-control form" required name="quantity" value="<?php echo isset($quantity) ? $quantity : '' ?>">
                <?php if(isset($id)): 
                    $stock_status = '';
                    if($quantity <= 0) {
                        $stock_status = 'text-danger';
                    } else if($quantity <= 5) {
                        $stock_status = 'text-warning';
                    } else {
                        $stock_status = 'text-success';
                    }
                ?>
                <small class="text-muted">
                    Current Stock: <span class="<?php echo $stock_status ?>"><?php echo $quantity ?></span>
                </small>
                <?php endif; ?>
            </div>
            <div class="form-group">
				<label for="price" class="control-label">Price</label>
                <input type="number" step="any" class="form-control form" required name="price" value="<?php echo isset($price) ? $price : '' ?>">
            </div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="inventory-form">Save</button>
		<a class="btn btn-flat btn-default" href="?page=inventory">Cancel</a>
	</div>
</div>
<script>
    function displayImg(input,_this) {
        console.log(input.files)
        var fnames = []
        Object.keys(input.files).map(k=>{
            fnames.push(input.files[k].name)
        })
        _this.siblings('.custom-file-label').html(JSON.stringify(fnames))
	    
	}
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Please Select here",width:"relative"})
		$('#inventory-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_inventory",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=inventory";
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

        $('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })

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
            var selectedDate = $('#report_date').val();
            $.ajax({
                url: '../get_sales_data.php',
                method: 'GET',
                data: { date: selectedDate },
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
                                    text: "Paid Products Sold (Quantity)"
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
                alert('Please select a date.');
                return;
            }
            var url = '../generate_sales_report.php?date=' + encodeURIComponent(selectedDate);
            window.open(url, '_blank');
        });

        // Download daily deducted items report button click
        $('#downloadDailyReportBtn').on('click', function() {
            var selectedDate = $('#report_date').val();
            if (!selectedDate) {
                alert('Please select a date.');
                return;
            }
            var url = '../generate_daily_deducted_report.php?date=' + encodeURIComponent(selectedDate);
            window.open(url, '_blank');
        });

        // Reload chart on date change
        $('#report_date').on('change', function() {
            loadSalesChart();
        });

        // Initial load
        loadStats();
        loadSalesChart();

        // Refresh every 15 seconds
        setInterval(loadStats, 15000);
        setInterval(loadSalesChart, 15000);
	})
</script>
