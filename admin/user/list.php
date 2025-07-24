<?php
// admin/user/list.php
$user = $conn->query("SELECT * FROM users");
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">User List</h3>
		<div class="card-tools">
			<a href="?page=user/manage_user" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Add New User</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="row mb-3">
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" class="form-control" id="searchInput" placeholder="Search users...">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-search"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<table class="table table-hover table-striped" id="userTable">
					<thead>
						<tr>
							<th>#</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Username</th>
							<th>Avatar</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
						while($row = $user->fetch_assoc()):
						?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['firstname']; ?></td>
							<td><?php echo $row['lastname']; ?></td>
							<td><?php echo $row['username']; ?></td>
							<td class="text-center">
								<img src="<?php echo validate_image($row['avatar']); ?>" alt="" class="img-avatar img-thumbnail p-0 border-2">
							</td>
							<td class="text-center">
								<div class="btn-group">
									<a href="?page=user/manage_user&id=<?php echo $row['id']; ?>" class="btn btn-default btn-flat btn-sm"><i class="fa fa-edit"></i></a>
									<button type="button" class="btn btn-danger btn-flat btn-sm delete_user" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash"></i></button>
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
		$('.delete_user').click(function(){
			_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
		})

		// Search functionality
		$("#searchInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#userTable tbody tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	})
	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete",
			method:"POST",
			data:{id:$id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp =='object' && resp.status == 'success'){
					location.reload();
				}else if(resp.status == 'failed' && !!resp.msg){
					var el = $('<div>')
					el.addClass("alert alert-danger err-msg").text(resp.msg)
					_this.prepend(el)
					el.show('slow')
					$("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
					end_loader()
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
