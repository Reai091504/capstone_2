<?php 
$title = "Your Pets Deserve The Best";
$sub_title = "Explore our products for your pet.";
if(isset($_GET['c']) && isset($_GET['s'])){
    $cat_qry = $conn->query("SELECT * FROM categories where md5(id) = '{$_GET['c']}'");
    if($cat_qry->num_rows > 0){
        $title = $cat_qry->fetch_assoc()['category'];
    }
 $sub_cat_qry = $conn->query("SELECT * FROM sub_categories where md5(id) = '{$_GET['s']}'");
    if($sub_cat_qry->num_rows > 0){
        $sub_title = $sub_cat_qry->fetch_assoc()['sub_category'];
    }
}
elseif(isset($_GET['c'])){
    $cat_qry = $conn->query("SELECT * FROM categories where md5(id) = '{$_GET['c']}'");
    if($cat_qry->num_rows > 0){
        $title = $cat_qry->fetch_assoc()['category'];
    }
}
elseif(isset($_GET['s'])){
    $sub_cat_qry = $conn->query("SELECT * FROM sub_categories where md5(id) = '{$_GET['s']}'");
    if($sub_cat_qry->num_rows > 0){
        $title = $sub_cat_qry->fetch_assoc()['sub_category'];
    }
}
?>
<!-- Header-->
<header class="bg-dark py-5" id="main-header">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder"><?php echo $title ?></h1>
            <p class="lead fw-normal text-white-50 mb-0"><?php echo $sub_title ?></p>
        </div>
    </div>
</header>

<style>
.product-item {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-item .img-container {
    position: relative;
    overflow: hidden;
    width: 100%;
    padding-top: 100%; /* Creates 1:1 aspect ratio */
    cursor: pointer;
}

.product-item .card-img-top {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.product-item:hover .card-img-top {
    transform: scale(1.05);
}

.product-item .img-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.03);
    opacity: 0;
    transition: all 0.3s ease;
}

.product-item .img-container:hover::after {
    opacity: 1;
}

.product-item .card-body {
    padding: 1.25rem;
}

.product-item .btn-primary {
    transition: all 0.3s ease;
    opacity: 0.9;
}

.product-item .btn-primary:hover {
    opacity: 1;
    transform: translateY(-2px);
}
</style>

<!-- Section-->
<section class="py-5">
    <div class="container-fluid px-4 px-lg-5 mt-5">
    <?php 
                if(isset($_GET['search'])){
                    echo "<h4 class='text-center'><b>Search Result for '".$_GET['search']."'</b></h4>";
                }
            ?>
        
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
           
            <?php 
                $whereData = "";
                if(isset($_GET['search']))
                    $whereData = " and (product_name LIKE '%{$_GET['search']}%' or description LIKE '%{$_GET['search']}%')";
                elseif(isset($_GET['c']) && isset($_GET['s']))
                    $whereData = " and (md5(category_id) = '{$_GET['c']}' and md5(sub_category_id) = '{$_GET['s']}')";
                elseif(isset($_GET['c']))
                    $whereData = " and md5(category_id) = '{$_GET['c']}' ";
                elseif(isset($_GET['s']))
                    $whereData = " and md5(sub_category_id) = '{$_GET['s']}' ";
                $products = $conn->query("SELECT * FROM `products` where status = 1 {$whereData} order by rand() ");
                while($row = $products->fetch_assoc()):
                    $upload_path = base_app.'/uploads/product_'.$row['id'];
                    $img = "";
                    if(is_dir($upload_path)){
                        $fileO = scandir($upload_path);
                        if(isset($fileO[2]))
                            $img = "/uploads/product_".$row['id']."/".$fileO[2];
                    }
                    $inventory = $conn->query("SELECT * FROM inventory where product_id = ".$row['id']);
                    $inv = array();
                    while($ir = $inventory->fetch_assoc()){
                        $inv[$ir['size']] = number_format($ir['price'], 2);
                    }
            ?>
            <div class="col mb-5">
                <div class="card h-100 product-item">
                    <!-- Product image-->
                    <a href=".?p=view_product&id=<?php echo md5($row['id']) ?>" class="img-container">
                        <img class="card-img-top" src="<?php echo validate_image($img) ?>" loading="lazy" alt="<?php echo $row['product_name'] ?>" />
                    </a>
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
                            <a class="btn btn-flat btn-primary" href=".?p=view_product&id=<?php echo md5($row['id']) ?>">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php 
                if($products->num_rows <= 0){
                    echo "<h4 class='text-center'><b>No Product Listed.</b></h4>";
                }
            ?>
        </div>
    </div>
</section>