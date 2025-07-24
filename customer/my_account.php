    
<section class="py-2">
    <div class="container" style="margin-top: 100px; max-width: 900px;">
        <div class="card rounded-4 shadow-lg border-0" style="background: rgba(255,255,255,0.97); box-shadow: 0 8px 32px rgba(60,141,188,0.12);">
            <div class="card-body p-5">
                <div class="w-100 justify-content-between d-flex align-items-center mb-3">
                    <h4 class="mb-0" style="color: #0d6efd;"><b>Orders</b></h4>
                </div>
                <hr class="border-primary mb-4">
                <table class="table table-stripped text-dark">
                    <colgroup>
                        <col width="10%">
                        <col width="15">
                        <col width="25">
                        <col width="25">
                        <col width="15">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DateTime</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Order Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client from `orders` o inner join clients c on c.id = o.client_id where o.client_id = '".$_settings->userdata('id')."' order by unix_timestamp(o.date_created) desc ");
                            while($row = $qry->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td><a href="javascript:void(0)" class="view_order" data-id="<?php echo $row['id'] ?>"><?php echo md5($row['id']); ?></a></td>
                                <td>₱<?php echo number_format($row['amount'], 2) ?> </td>
                                <td class="text-center">
                                        <?php if($row['status'] == 0): ?>
                                            <span class="badge badge-light text-dark">Pending</span>
                                        <?php elseif($row['status'] == 1): ?>
                                            <span class="badge badge-primary">Packed</span>
                                        <?php elseif($row['status'] == 2): ?>
                                            <span class="badge badge-warning">Out for Delivery</span>
                                        <?php elseif($row['status'] == 3): ?>
                                            <span class="badge badge-success">Delivered</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="order-details-content">
        <!-- Order details will be loaded here -->
      </div>
      <div class="modal-footer" id="order-details-footer">
        <!-- Cancel button will be injected here if needed -->
      </div>
    </div>
  </div>
</div>
<script>
$(function(){
    $('.view_order').click(function(){
        var orderId = $(this).data('id');
        $('#order-details-content').html('<div class="text-center py-5"><span class="spinner-border"></span></div>');
        $('#order-details-footer').html('');
        $('#orderDetailsModal').modal('show');
        $.ajax({
            url: 'customer/ajax_order_details.php',
            method: 'GET',
            data: {id: orderId},
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#order-details-content').html(resp.html);
                    if(resp.cancellable) {
                        $('#order-details-footer').html('<button class="btn btn-danger" id="cancel-order-btn" data-id="'+orderId+'">Cancel Order</button>');
                    } else {
                        $('#order-details-footer').html('');
                    }
                } else {
                    $('#order-details-content').html('<div class="alert alert-danger">Failed to load order details.</div>');
                }
            },
            error: function() {
                $('#order-details-content').html('<div class="alert alert-danger">Failed to load order details.</div>');
            }
        });
    });
    // Cancel order logic
    $(document).on('click', '#cancel-order-btn', function(){
        var orderId = $(this).data('id');
        if(confirm('Are you sure you want to cancel this order?')) {
            $.ajax({
                url: 'classes/Master.php?f=update_order_status',
                method: 'POST',
                data: {id: orderId, status: 4}, // 4 = Cancelled
                dataType: 'json',
                success: function(resp) {
                    if(resp.status === 'success') {
                        alert_toast('Order cancelled successfully', 'success');
                        $('#orderDetailsModal').modal('hide');
                        setTimeout(function(){ location.reload(); }, 1500);
                    } else {
                        alert_toast('Failed to cancel order', 'error');
                    }
                },
                error: function() {
                    alert_toast('Failed to cancel order', 'error');
                }
            });
        }
    });
    $('table').dataTable();
});
</script>
<style>
.card.rounded-4 {
    border-radius: 1.5rem !important;
}
#order-details-content .table th, #order-details-content .table td {
    vertical-align: middle;
}
.table thead th {
    color: #3c8dbc;
    font-weight: 600;
    background: #e3f0ff;
    border-bottom: 2px solid #0d6efd;
}
.table tbody tr {
    transition: background 0.2s;
}
.table tbody tr:hover {
    background: #f0f8ff;
}
</style>