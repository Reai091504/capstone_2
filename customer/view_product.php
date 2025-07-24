<?php 
 $products = $conn->query("SELECT * FROM `products`  where md5(id) = '{$_GET['id']}' ");
 if($products->num_rows > 0){
     foreach($products->fetch_assoc() as $k => $v){
         $$k= $v;
     }
    $upload_path = base_app.'/uploads/product_'.$id;
    $img = "";
    if(is_dir($upload_path)){
        $fileO = scandir($upload_path);
        if(isset($fileO[2]))
            $img = "/uploads/product_".$id."/".$fileO[2];
        // var_dump($fileO);
    }
    $inventory = $conn->query("SELECT * FROM inventory where product_id = ".$id);
    $inv = array();
    while($ir = $inventory->fetch_assoc()){
        $inv[] = $ir;
    }
 }
?>
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-start">
            <div class="col-md-6">
                <div class="img-container mb-5 mb-md-0">
                    <img loading="lazy" id="display-img" src="<?php echo validate_image($img) ?>" alt="<?php echo $product_name ?>" />
                </div>
                <div class="mt-2 row gx-2 gx-lg-3 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-start">
                    <?php 
                        foreach($fileO as $k => $img):
                            if(in_array($img,array('.','..')))
                                continue;
                    ?>
                        <div class="col">
                            <a href="javascript:void(0)" class="view-image <?php echo $k == 2 ? "active":'' ?>">
                                <img src="<?php echo validate_image('/uploads/product_'.$id.'/'.$img) ?>" loading="lazy" alt="<?php echo $product_name ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-info-container">
                    <div class="product-title-container mb-3">
                        <h1 class="display-5 fw-bolder text-primary"><?php echo isset($product_name) ? $product_name : 'Product Name Not Available' ?></h1>
                    </div>
                    <div class="product-details-container mb-3">
                        <div class="price-container mb-2">
                            <span class="price-label">Price:</span>
                            <span class="price-value">₱ <span id="price" class="fw-bold"><?php echo isset($inv[0]['price']) ? number_format($inv[0]['price'], 2) : '0.00' ?></span></span>
                        </div>
                        <?php 
                            $stock = isset($inv[0]['quantity']) ? $inv[0]['quantity'] : 0;
                            if($stock <= 0){
                                $stock_class = 'bg-danger';
                            }elseif($stock <= 5){
                                $stock_class = 'bg-warning';
                            }else{
                                $stock_class = 'bg-success';
                            }
                        ?>
                        <div class="stock-container mb-2">
                            <span class="stock-label">Available stock:</span>
                            <span class="stock-value"><span id="avail" class="badge <?php echo $stock_class; ?>"><?php echo ($stock > 0) ? $stock : 'Out of Stock'; ?></span></span>
                        </div>
                    </div>
                    <form action="customer/login.php" id="add-cart" class="add-to-cart-form mb-3">
                        <div class="d-flex align-items-end">
                            <input type="hidden" name="price" value="<?php echo $inv[0]['price'] ?>">
                            <input type="hidden" name="inventory_id" value="<?php echo $inv[0]['id'] ?>">
                            <div class="quantity-container me-3">
                                <label class="quantity-label d-block mb-1">Quantity:</label>
                                <div class="input-group" style="max-width: 10rem;">
                                    <button class="btn btn-outline-secondary" type="button" id="button-minus">-</button>
                                    <input class="form-control text-center" id="inputQuantity" type="num" value="0" name="quantity" aria-label="Product quantity">
                                    <button class="btn btn-outline-secondary" type="button" id="button-plus">+</button>
                                </div>
                            </div>
                            <button class="btn btn-primary flex-shrink-0 add-to-cart-btn" type="submit">
                                <i class="bi-cart-fill me-1"></i>
                                Add to cart
                            </button>
                        </div>
                    </form>
                    <div class="product-description">
                        <h5 class="description-title mb-2">Product Description</h5>
                        <div class="description-content">
                            <?php echo stripslashes(html_entity_decode($description)) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Related items section-->
<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="fw-bolder mb-4">Related products</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <?php 
            $products = $conn->query("SELECT * FROM `products` where status = 1 and (category_id = '{$category_id}' or sub_category_id = '{$sub_category_id}') and id !='{$id}' order by rand() limit 4 ");
            while($row = $products->fetch_assoc()):
                $upload_path = base_app.'/uploads/product_'.$row['id'];
                $img = "";
                if(is_dir($upload_path)){
                    $fileO = scandir($upload_path);
                    if(isset($fileO[2]))
                        $img = "/uploads/product_".$row['id']."/".$fileO[2];
                }
                $inventory = $conn->query("SELECT * FROM inventory where product_id = ".$row['id']);
                $_inv = array();
                while($ir = $inventory->fetch_assoc()){
                    $_inv[$ir['size']] = number_format($ir['price']);
                }
        ?>
            <div class="col mb-5">
                <a href=".?p=view_product&id=<?php echo md5($row['id']) ?>" class="product-link">
                    <div class="card h-100 product-item">
                        <!-- Product image-->
                        <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo $row['product_name'] ?></h5>
                                <!-- Product price-->
                                <?php 
                                    $price = $conn->query("SELECT MIN(price) as min_price FROM inventory WHERE product_id = ".$row['id'])->fetch_assoc()['min_price'];
                                    echo '<span class="text-dark h5"><b>₱</b>'.number_format($price, 2).'</span>';
                                ?>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <span class="btn btn-flat btn-primary">View</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<script>
    var inv = $.parseJSON('<?php echo json_encode($inv) ?>');
    $(function(){
        $('.view-image').on('mouseenter', function(){
            var _img = $(this).find('img').attr('src');
            $('#display-img').attr('src',_img);
            $('.view-image').removeClass("active")
            $(this).addClass("active")
        });
        $('.p-size').click(function(){
            var k = $(this).attr('data-id');
            $('.p-size').removeClass("active")
            $(this).addClass("active")
            $('#price').text(inv[k].price)
            $('[name="price"]').val(inv[k].price)
            $('#avail').text(inv[k].quantity > 0 ? inv[k].quantity : 'Out of Stock')
            $('[name="inventory_id"]').val(inv[k].id)
            availableStock = inv[k].quantity;
            // Reset quantity to 1 when changing product variant
            $('#inputQuantity').val(1);
            updateStockDisplay();
        })

        // Get initial available stock
        var availableStock = parseInt($('#avail').text());
        if (isNaN(availableStock)) {
            availableStock = 0;
        }
        
        // Update stock display when quantity changes
        function updateStockDisplay() {
            var currentQuantity = parseInt($('#inputQuantity').val());
            var remainingStock = availableStock;
            
            // Update the badge color based on remaining stock
            if(remainingStock <= 0) {
                $('#avail').removeClass('bg-success').addClass('bg-danger').text('Out of Stock');
            } else if(remainingStock <= 5) {
                $('#avail').removeClass('bg-success').addClass('bg-warning');
            } else {
                $('#avail').removeClass('bg-warning bg-danger').addClass('bg-success');
            }
        }

        // Quantity buttons functionality
        $('#button-minus').click(function(){
            var inputQuantity = $('#inputQuantity');
            var currentVal = parseInt(inputQuantity.val());
            if (!isNaN(currentVal) && currentVal > 0) {
                inputQuantity.val(currentVal - 1);
            }
        });

        $('#button-plus').click(function(){
            var inputQuantity = $('#inputQuantity');
            var currentVal = parseInt(inputQuantity.val());
            if (!isNaN(currentVal) && currentVal < availableStock) {
                inputQuantity.val(currentVal + 1);
            }
        });

        // Handle manual input
        $('#inputQuantity').on('change', function() {
            var currentVal = parseInt($(this).val());
            if (isNaN(currentVal) || currentVal < 1) {
                $(this).val(1);
            } else if (currentVal > availableStock) {
                $(this).val(availableStock);
            }
        });

        $('#add-cart').submit(function(e){
            e.preventDefault();
            var qty = parseInt($('#inputQuantity').val());
            if (isNaN(qty) || qty === 0) {
                alert_toast('Please select a quantity before adding to cart.', 'warning');
                return false;
            }
            if(availableStock <= 0){
                alert_toast('No stock available for this product.', 'warning');
                return false;
            }
            if('<?php echo $_settings->userdata('id') ?>' <= 0){
                uni_modal("","customer/login.php");
                return false;
            }
            start_loader();
            $.ajax({
                url:'classes/Master.php?f=add_to_cart',
                data:$(this).serialize(),
                method:'POST',
                dataType:"json",
                error:err=>{
                    console.log(err)
                    alert_toast("an error occured",'error')
                    end_loader()
                },
                success:function(resp){
                    if(typeof resp == 'object' && resp.status=='success'){
                        alert_toast("Product added to cart.",'success')
                        $('#cart-count').text(resp.cart_count)
                    }else{
                        console.log(resp)
                        alert_toast(resp.msg || "an error occured",'error')
                    }
                    end_loader();
                }
            })
        })
    })
</script>
<!-- Add this CSS for responsiveness and hover animation -->
<style>
/* Main product image container */
.img-container {
    position: relative;
    width: 100%;
    padding-top: 100%; /* Creates 1:1 aspect ratio */
    overflow: hidden;
}

#display-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product title styling */
.product-title-container {
    position: relative;
    margin-bottom: 2rem;
}

.product-title-container h1 {
    position: relative;
    display: inline-block;
    margin: 0;
    padding-bottom: 10px;
    color: #0d6efd;
    font-weight: 700;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.product-title-container h1:hover {
    transform: translateY(-2px);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

/* Thumbnail images */
.view-image {
    position: relative;
    width: 100%;
    padding-top: 100%; /* Creates 1:1 aspect ratio */
    display: block;
    overflow: hidden;
    transition: transform 0.2s cubic-bezier(.25,.8,.25,1), box-shadow 0.2s cubic-bezier(.25,.8,.25,1);
}

.view-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s cubic-bezier(.25,.8,.25,1), box-shadow 0.2s cubic-bezier(.25,.8,.25,1);
}

.view-image:hover,
.view-image:focus {
    transform: scale(1.06);
    z-index: 2;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.view-image:hover img,
.view-image:focus img {
    transform: scale(1.08);
    box-shadow: 0 4px 16px rgba(0,0,0,0.18);
}

/* Card hover animation */
.product-item {
    transition: transform 0.3s cubic-bezier(.25,.8,.25,1), box-shadow 0.3s cubic-bezier(.25,.8,.25,1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    cursor: pointer;
    height: 100%;
}

.product-item:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 2;
}

.product-link {
    color: inherit;
    text-decoration: none;
    display: block;
    height: 100%;
}

.product-link:hover .product-item,
.product-link:focus .product-item {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 2;
    text-decoration: none;
}

@media (max-width: 575.98px) {
    .product-item {
        margin-bottom: 1rem;
    }
    .card-body.p-4 {
        padding: 1rem !important;
    }
}

/* Product details styling */
.product-details-container {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.price-container, .stock-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.price-label, .stock-label {
    font-weight: 600;
    color: #495057;
    min-width: 100px;
}

.price-value {
    font-size: 1.25rem;
    color: #0d6efd;
}

.stock-value .badge {
    font-size: 0.9rem;
    padding: 0.4rem 0.8rem;
}

/* Size variations styling */
.size-variations {
    background: #fff;
    padding: 1rem;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.size-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

.p-size {
    min-width: 70px;
    transition: all 0.3s ease;
    padding: 0.4rem 0.8rem;
}

.p-size:hover {
    transform: translateY(-2px);
}

.p-size.active {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

/* Add to cart form styling */
.add-to-cart-form {
    background: #fff;
    padding: 1rem;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.quantity-container {
    display: flex;
    flex-direction: column;
}

.quantity-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

#inputQuantity {
    border: 2px solid #dee2e6;
    border-radius: 4px;
    font-size: 1rem;
    padding: 0.4rem;
    transition: all 0.3s ease;
    height: 38px; /* Match button height */
}

#inputQuantity:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

.add-to-cart-btn {
    padding: 0.5rem 1.25rem;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    height: 38px; /* Match input height */
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-details-container,
    .size-variations,
    .add-to-cart-form {
        padding: 0.75rem;
    }
    
    .price-container, .stock-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .add-to-cart-form .d-flex {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .quantity-container {
        width: 100%;
    }
    
    .add-to-cart-btn {
        width: 100%;
    }
}

/* Product info container */
.product-info-container {
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-title-container h1 {
    color: #0d6efd;
    margin: 0;
    font-size: 2rem;
    line-height: 1.2;
}

/* Product description styling */
.product-description {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 6px;
    margin-top: 1rem;
    max-height: 300px;
    overflow-y: auto;
}

.description-title {
    color: #495057;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 0.25rem;
    margin-bottom: 0.25rem;
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 1;
}

.description-content {
    color: #6c757d;
    line-height: 1;
    font-size: 0.95rem;
    word-wrap: break-word;
    white-space: pre-wrap;
    overflow-wrap: break-word;
    margin-top: 0;
}

.description-content p {
    margin-bottom: 0;
    max-width: 100%;
}

.description-content p:last-child {
    margin-bottom: 0;
}

/* Add custom scrollbar for description */
.product-description::-webkit-scrollbar {
    width: 8px;
}

.product-description::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.product-description::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.product-description::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
<link rel="stylesheet" href="customer/assets/css/styles.css">