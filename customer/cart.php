<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../classes/DBConnection.php');
$db = new DBConnection;
$conn = $db->conn;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<section class="py-5 mt-4">
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col d-flex justify-content-end mb-2"></div>
        </div>
        <div class="card rounded-0">
            <div class="card-body">
                <div class="d-flex w-100 justify-content-between align-items-center mb-3">
                    <h3><b><i class="fa fa-shopping-cart"></i> Cart List</b></h3>
                    <button class="btn btn-outline-dark btn-flat btn-sm" type="button" id="empty_cart">Empty Cart</button>
                </div>
                <hr class="border-dark">
                <?php 
                    $qry = $conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid from `cart` c inner join `inventory` i on i.id=c.inventory_id inner join products p on p.id = i.product_id where c.client_id = ".$_settings->userdata('id'));
                    while($row= $qry->fetch_assoc()):
                        $upload_path = base_app.'/uploads/product_'.$row['pid'];
                        $img = "";
                        if(is_dir($upload_path)){
                            $fileO = scandir($upload_path);
                            if(isset($fileO[2])) {
                                $img_candidate = 'uploads/product_'.$row['pid'].'/'.$fileO[2];
                                if(is_file(base_app.$img_candidate)) {
                                    $img = $img_candidate;
                                }
                            }
                        }
                ?>
                    <div class="d-flex w-100 justify-content-between mb-2 py-2 border-bottom cart-item">
                        <div class="d-flex align-items-center col-8">
                            <span class="mr-2"><a href="javascript:void(0)" class="btn btn-sm btn-outline-danger rem_item" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></a></span>
                            <div class="cart-images d-flex align-items-center mr-2 mr-sm-2">
                                <?php if(!empty($img)): ?>
                                    <img src="<?php echo base_url . $img ?>" loading="lazy" class="cart-prod-img mr-2 mr-sm-2 border" alt="">
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="mb-1 mb-sm-1"><?php echo $row['product_name'] ?></p>
                                <p class="mb-1 mb-sm-1"><small><b>Price:</b> <span class="price">₱<?php echo number_format($row['price'], 2) ?></span></small></p>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-sm btn-outline-secondary min-qty" type="button" id="button-addon1"><i class="fa fa-minus"></i></button>
                                        </div>
                                        <input type="number" class="form-control form-control-sm qty text-center cart-qty" placeholder="" aria-label="Example text with button addon" value="<?php echo $row['quantity'] ?>" aria-describedby="button-addon1" data-id="<?php echo $row['id'] ?>" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-secondary plus-qty" type="button" id="button-addon1"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col text-right align-items-center d-flex justify-content-end">
                            <h4><b class="total-amount">₱<?php echo number_format($row['price'] * $row['quantity'], 2) ?></b></h4>
                        </div>
                    </div>
                <?php endwhile; ?>
                <div class="d-flex w-100 justify-content-between mb-2 py-2 border-bottom">
                    <div class="col-8 d-flex justify-content-end"><h4>Grand Total:</h4></div>
                    <div class="col d-flex justify-content-end"><h4 id="grand-total">₱ -</h4></div>
                </div>
            </div>
        </div>
        <div class="d-flex w-100 justify-content-end mt-3">
            <a href="./?p=checkout" class="btn btn-sm btn-flat btn-dark" id="checkout_btn" style="pointer-events: none; opacity: 0.6;">Checkout</a>
        </div>
    </div>
</section>
<script>
    function calc_total(){
        var total = 0;
        var hasItems = false;
        $('.total-amount').each(function(){
            let amount = $(this).text();
            amount = amount.replace(/[₱,]/g,''); // Remove peso sign and commas
            amount = parseFloat(amount);
            if(!isNaN(amount)) {
                total += amount;
                hasItems = true;
            }
        });
        $('#grand-total').text('₱' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        // Enable/disable checkout button based on cart items
        if(hasItems) {
            $('#checkout_btn').css('pointer-events', 'auto').css('opacity', '1');
        } else {
            $('#checkout_btn').css('pointer-events', 'none').css('opacity', '0.6');
        }
    }
    function qty_change($type,_this){
        var qty = _this.closest('.cart-item').find('.cart-qty').val();
        var price = _this.closest('.cart-item').find('.price').text();
        var cart_id = _this.closest('.cart-item').find('.cart-qty').attr('data-id');
        var new_total = 0;
        if($type == 'minus'){
            if(parseInt(qty) - 1 <= 0){
                // Prompt confirmation before removing
                _conf("Are you sure you want to remove this item from the list?", 'rem_item', [cart_id]);
                return;
            } else {
                qty = parseInt(qty) - 1;
            }
        }else{
            qty = parseInt(qty) + 1;
        }
        start_loader();
        price = parseFloat(price.replace(/[₱,]/g,''));
        new_total = parseFloat(qty * price).toLocaleString('en-US');
        _this.closest('.cart-item').find('.cart-qty').val(qty);
        _this.closest('.cart-item').find('.total-amount').text('₱' + new_total);
        calc_total();
        $.ajax({
            url:'classes/Master.php?f=update_cart_qty',
            method:'POST',
            data:{id:cart_id, quantity: qty},
            dataType:'json',
            error:err=>{
                console.log(err);
                alert_toast("an error occured", 'error');
                end_loader();
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    end_loader();
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader();
                }
            }
        });
    }
    function rem_item(id){
        $('.modal').modal('hide');
        var _this = $('.rem_item[data-id="'+id+'"]');
        var id = _this.attr('data-id');
        var item = _this.closest('.cart-item');
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=delete_cart',
            method:'POST',
            data:{id:id},
            dataType:'json',
            error:err=>{
                console.log(err);
                alert_toast("an error occured", 'error');
                end_loader();
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    item.hide('slow', function(){ 
                        item.remove(); 
                        calc_total();
                    });
                    end_loader();
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader();
                }
            }
        });
    }
    function empty_cart(){
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=empty_cart',
            method:'POST',
            data:{},
            dataType:'json',
            error:err=>{
                console.log(err);
                alert_toast("an error occured", 'error');
                end_loader();
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                   location.reload();
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader();
                }
            }
        });
    }
    $(function(){
        calc_total();
        $('.min-qty').click(function(){
            qty_change('minus',$(this));
        });
        $('.plus-qty').click(function(){
            qty_change('plus',$(this));
        });
        $('#empty_cart').click(function(){
            _conf("Are you sure to empty your cart list?",'empty_cart',[]);
        });
        $('.rem_item').click(function(){
            _conf("Are you sure to remove the item in cart list?",'rem_item',[$(this).attr('data-id')]);
        });
    });
</script>
</script>