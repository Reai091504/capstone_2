<?php
// admin/user_list/index.php
$client = $conn->query("SELECT * FROM clients");
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Client List</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="row mb-3">
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" class="form-control" id="searchInput" placeholder="Search clients...">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-search"></i></span>
						</div>
					</div>
				</div>
			</div>
			<table class="table table-hover table-striped" id="clientTable">
				<thead>
					<tr>
						<th>#</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Contact</th>
						<th>Delivery Address</th>
						<th>Date Created</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					while($row = $client->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++; ?></td>
						<td><?php echo $row['firstname']; ?></td>
						<td><?php echo $row['lastname']; ?></td>
						<td><?php echo $row['email']; ?></td>
						<td><?php echo $row['contact']; ?></td>
						<td><?php echo isset($row['default_delivery_address']) ? $row['default_delivery_address'] : 'N/A'; ?></td>
						<td><?php echo isset($row['date_created']) ? date('Y-m-d H:i:s', strtotime($row['date_created'])) : 'N/A'; ?></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
    // Search functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#clientTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
