<?php if(isset($_GET['view'])): 
require_once('../../config.php');
endif;?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(!isset($_GET['id'])){
    $_settings->set_flashdata('error','No order ID Provided.');
    redirect('admin/?page=orders');
}
$order = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client FROM `orders` o inner join clients c on c.id = o.client_id where o.id = '{$_GET['id']}' ");
if($order->num_rows > 0){
    foreach($order->fetch_assoc() as $k => $v){
        $$k = $v;
    }
}else{
    $_settings->set_flashdata('error','Order ID provided is Unknown');
    redirect('admin/?page=orders');
}
?>
<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="conitaner-fluid">
            <p><b>Client Name: <?php echo $client ?></b></p>
            <p><b>Delivery Address: <?php echo $delivery_address ?></b></p>
            <table class="table-striped table table-bordered">
                <colgroup>
                    <col width="15%">
                    <col width="15%">
                    <col width="30%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>QTY</th>
                        <th>Unit</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $olist = $conn->query("SELECT o.*,p.product_name FROM order_list o inner join products p on o.product_id = p.id where o.order_id = '{$id}' ");
                        while($row = $olist->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['quantity'] ?></td>
                        <td><?php echo $row['unit'] ?></td>
                        <td><?php echo $row['product_name'] ?></td>
                        <td class="text-right">₱<?php echo number_format($row['price'], 2) ?></td>
                        <td class="text-right">₱<?php echo number_format($row['price'] * $row['quantity'], 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='4'  class="text-right">Total</th>
                        <th class="text-right">₱<?php echo number_format($amount, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Payment Method: <?php echo strtoupper($payment_method) ?></p>
                <p>Payment Status: <?php echo $paid == 0 ? '<span class="badge badge-light">Unpaid</span>' : '<span class="badge badge-success">Paid</span>' ?></p>
            </div>
            <div class="col-6 row row-cols-2">
                <div class="col-3">Order Status:</div>
                <div class="col-9">
                <?php 
                    switch($status){
                        case '0':
                            echo '<span class="badge badge-light text-dark">Pending</span>';
	                    break;
                        case '1':
                            echo '<span class="badge badge-primary">Packed</span>';
	                    break;
                        case '2':
                            echo '<span class="badge badge-warning">Out for Delivery</span>';
	                    break;
                        case '3':
                            echo '<span class="badge badge-success">Delivered</span>';
	                    break;
                        default:
                            echo '<span class="badge badge-danger">Cancelled</span>';
	                    break;
                    }
                ?>
                </div>
                <?php if(!isset($_GET['view'])): ?>
                <div class="col-3"></div>
                <div class="col">
                    <button type="button" id="update_status" class="btn btn-sm btn-flat btn-primary">Update Status</button>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <?php if(isset($_GET['view'])): ?>
    <button type="button" class="btn btn-danger" id="cancel_order">Cancel Order</button>
    <?php endif; ?>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<?php if(isset($_GET['view'])): ?>
<style>
    #uni_modal>.modal-dialog>.modal-content>.modal-footer{
        display:none;
    }
</style>
<?php endif; ?>
<script>
    $(function(){
        $('#update_status').click(function(){
            uni_modal("Update Status", "./orders/update_status.php?oid=<?php echo $id ?>&status=<?php echo $status ?>")
        })
        
        $('#cancel_order').click(function(){
            _conf("Are you sure to cancel this order?", "cancel_order", [<?php echo $id ?>])
        })
    })

    function cancel_order($id){
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Master.php?f=cancel_order",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    location.reload();
                }else{
                    alert_toast("An error occurred", 'error');
                    end_loader();
                }
            }
        })
    }
</script>