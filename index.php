<?php 
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/classes/DBConnection.php');
$db = new DBConnection;
$conn = $db->conn;
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once(__DIR__ . '/inc/header.php'); ?>
<?php require_once(__DIR__ . '/inc/topBarNav.php'); ?>
<body>

<?php 
$page = isset($_GET['p']) ? $_GET['p'] : 'home';

// If page is 'home' or empty, show the home content directly
if($page == 'home' || empty($page)) {
?>
    <!-- Header-->
    <header class="py-5" id="main-header" style="margin-top: 76px; background-color: #3c8dbc;">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Your Pets Deserve The Best</h1>
                <p class="lead fw-normal text-white-50 mb-0">Looking for your pet's needs? Shop Now!</p>
            </div>
        </div>
    </header>

    <!-- Automatic Slideshow Section -->
    <div class="container px-4 px-lg-5 my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="slideshow-container" style="max-width: 1200px; margin: 0 auto; position: relative;">
                    <div class="slideshow-wrapper" style="overflow: hidden; position: relative; aspect-ratio: 3/1;">
                        <div class="slideshow-slide" style="position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out;">
                            <img src="customer/store.jpg" alt="Store Front" style="width: 100%; height: auto; object-fit: cover; max-height: 100%;" />
                            <div class="slideshow-caption" style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.5); padding: 10px;">
                                <h3>Visit Our Store</h3>
                                <p>Experience our welcoming atmosphere</p>
                            </div>
                        </div>
                        <div class="slideshow-slide" style="position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out;">
                            <img src="customer/redfish.jpg" alt="Pet Products" style="width: 100%; height: auto; object-fit: cover; max-height: 100%;" />
                            <div class="slideshow-caption" style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.5); padding: 10px;">
                                <h3>Quality Products</h3>
                                <p>Best selection for your pets</p>
                            </div>
                        </div>
                        <div class="slideshow-slide" style="position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out;">
                            <img src="customer/oyechamps.jpg" alt="Pet Care" style="width: 100%; height: auto; object-fit: cover; max-height: 100%;" />
                            <div class="slideshow-caption" style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.5); padding: 10px;">
                                <h3>Expert Care</h3>
                                <p>Professional service for your pets</p>
                            </div>
                        </div>
                        <div class="slideshow-slide" style="position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out;">
                            <img src="customer/cutefish.jpg" alt="Fish Collection" style="width: 100%; height: auto; object-fit: cover; max-height: 100%;" />
                            <div class="slideshow-caption" style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.5); padding: 10px;">
                                <h3>Fish Collection</h3>
                                <p>Beautiful and exotic fish species</p>
                            </div>
                        </div>
                        <div class="slideshow-slide" style="position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out;">
                            <img src="customer/akwa.jpg" alt="Aquarium Supplies" style="width: 100%; height: auto; object-fit: cover; max-height: 100%;" />
                            <div class="slideshow-caption" style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.5); padding: 10px;">
                                <h3>Aquarium Supplies</h3>
                                <p>Complete aquarium setup and accessories</p>
                            </div>
                        </div>
                        <div class="slideshow-slide" style="position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out;">
                            <img src="customer/prodok.jpg" alt="Pet Accessories" style="width: 100%; height: auto; object-fit: cover; max-height: 100%;" />
                            <div class="slideshow-caption" style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.5); padding: 10px;">
                                <h3>Pet Accessories</h3>
                                <p>Essential supplies for your pets</p>
                            </div>
                        </div>
                    </div>
                    <div class="slideshow-dots" style="text-align: center; padding: 10px;">
                        <span class="dot active" style="height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer;"></span>
                        <span class="dot" style="height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer;"></span>
                        <span class="dot" style="height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer;"></span>
                        <span class="dot" style="height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer;"></span>
                        <span class="dot" style="height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer;"></span>
                        <span class="dot" style="height: 12px; width: 12px; margin: 0 5px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('uploads/bghome.jpg') no-repeat center center fixed;
        background-size: cover;
    }
    .video-section-container {
        background: rgba(0,0,0,0.2);
        padding: 2rem;
        border-radius: 1rem;
    }
    .slideshow-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }
    .slideshow-wrapper {
        border-radius: 10px;
        width: 100%;
        position: relative;
        /* Responsive aspect ratio using padding-top hack */
        height: 0;
        padding-top: 33.33%; /* 3:1 aspect ratio */
    }
    .slideshow-slide {
        border-radius: 10px;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    .slideshow-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 10px;
    }
    .slideshow-caption {
        border-radius: 0 0 10px 10px;
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
        background: rgba(0,0,0,0.5);
        padding: 10px;
        font-size: 1.1rem;
    }
    .dot {
        height: 12px;
        width: 12px;
        margin: 0 5px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .dot.active {
        background-color: #0d6efd !important;
    }
    .slideshow-dots {
        text-align: center;
        padding: 10px;
    }
    .product-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .product-item:hover {
        transform: scale(1.04);
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        z-index: 2;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    /* Responsive Styles */
    @media (max-width: 992px) {
        .slideshow-wrapper {
            padding-top: 50%; /* 2:1 aspect ratio for tablets */
        }
    }
    @media (max-width: 768px) {
        .slideshow-wrapper {
            padding-top: 75%; /* 4:3 aspect ratio for small tablets */
        }
        .card-body {
            padding: 20px !important;
        }
        .card-title {
            font-size: 1.5rem !important;
        }
        .slideshow-caption {
            font-size: 1rem;
            padding: 8px;
        }
        .dot {
            height: 10px;
            width: 10px;
        }
    }
    @media (max-width: 576px) {
        .slideshow-wrapper {
            padding-top: 100%; /* 1:1 aspect ratio for mobile */
        }
        .card-body {
            padding: 15px !important;
        }
        .card-title {
            font-size: 1.25rem !important;
        }
        .slideshow-caption {
            font-size: 0.95rem;
            padding: 6px;
        }
        .dot {
            height: 8px;
            width: 8px;
            margin: 0 3px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slideshow-slide');
        const dots = document.querySelectorAll('.dot');
        
        // Show first slide
        slides[0].style.opacity = '1';
        dots[0].classList.add('active');
        
        function showSlide(n) {
            // Hide all slides and remove active from all dots
            slides.forEach(slide => slide.style.opacity = '0');
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show current slide and set active dot
            slides[n].style.opacity = '1';
            dots[n].classList.add('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        // Add click events to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });
        
        // Auto advance slides every 5 seconds
        setInterval(nextSlide, 5000);
    });
    </script>

    <!-- Shop Information Section -->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <?php 
                $products = $conn->query("SELECT * FROM `products` where status = 1 order by rand() limit 8 ");
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
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 d-flex">
                <a href=".?p=view_product&id=<?php echo md5($row['id']) ?>" class="text-decoration-none w-100">
                    <div class="card h-100 product-item">
                        <!-- Product image-->
                        <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" alt="..." style="object-fit:cover; height:200px;" />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder text-dark"><?php echo $row['product_name'] ?></h5>
                                <!-- Product price-->
                                <?php 
                                    $price = $conn->query("SELECT MIN(price) as min_price FROM inventory WHERE product_id = ".$row['id'])->fetch_assoc()['min_price'];
                                    echo '<span class="text-dark"><b>₱</b>'.number_format($price, 2).'</span>';
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

    <!-- Category Cards Section -->
    <div class="container px-4 px-lg-5 my-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="?p=products&c=a87ff679a2f3e71d9181a67b7542122c&s=a87ff679a2f3e71d9181a67b7542122c" class="text-decoration-none">
                    <div class="card text-white rounded-lg shadow-sm" style="background-color: rgb(147, 112, 219) !important; border-radius: 15px; overflow: hidden; position: relative; cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body py-5 px-4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px;">
                            <h4 class="card-title fw-bold" style="font-size: 1.75rem;">DOG NEEDS</h4>
                            <img src="customer/golden-retriever.gif" alt="Golden Retriever" style="width: 120px; height: 120px; object-fit: contain; mix-blend-mode: multiply; filter: brightness(1.1);">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="?p=products&c=a87ff679a2f3e71d9181a67b7542122c&s=e4da3b7fbbce2345d7772b0674a318d5" class="text-decoration-none">
                    <div class="card bg-green text-white rounded-lg shadow-sm" style="background-color: #2E8B57; border-radius: 15px; overflow: hidden; position: relative; cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body py-5 px-4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px;">
                            <h4 class="card-title fw-bold" style="font-size: 1.75rem;">CAT NEEDS</h4>
                            <img src="customer/cat.gif" alt="Cat" style="width: 120px; height: 120px; object-fit: contain; mix-blend-mode: multiply; filter: brightness(1.1);">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                <a href="?p=products&c=a87ff679a2f3e71d9181a67b7542122c&s=1679091c5a880faf6fb5e6087eb1b2dc" class="text-decoration-none">
                    <div class="card text-white rounded-lg shadow-sm" style="background-color: rgb(255, 105, 180) !important; border-radius: 15px; overflow: hidden; position: relative; cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body py-5 px-4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px;">
                            <h4 class="card-title fw-bold" style="font-size: 1.75rem;">FISH NEEDS</h4>
                            <img src="customer/clown-fish.gif" alt="Clown Fish" style="width: 120px; height: 120px; object-fit: contain; mix-blend-mode: multiply; filter: brightness(1.1);">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Food Categories -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="http://localhost/?p=products&c=c4ca4238a0b923820dcc509a6f75849b&s=c4ca4238a0b923820dcc509a6f75849b" class="text-decoration-none">
                    <div class="card text-white rounded-lg shadow-sm" style="background-color: #FF4500 !important; border-radius: 15px; overflow: hidden; position: relative; cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body py-5 px-4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px;">
                            <h4 class="card-title fw-bold" style="font-size: 1.75rem;">DOG FOODS</h4>
                            <img src="customer/bone.gif" alt="Bone" style="width: 120px; height: 120px; object-fit: contain; mix-blend-mode: multiply; filter: brightness(1.1);">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="http://localhost/?p=products&c=c4ca4238a0b923820dcc509a6f75849b&s=eccbc87e4b5ce2fe28308fd9f2a7baf3" class="text-decoration-none">
                    <div class="card text-white rounded-lg shadow-sm" style="background-color: rgb(255, 204, 0) !important; border-radius: 15px; overflow: hidden; position: relative; cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body py-5 px-4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px;">
                            <h4 class="card-title fw-bold" style="font-size: 1.75rem;">CAT FOODS</h4>
                            <img src="customer/catfood.gif" alt="Cat food" style="width: 120px; height: 120px; object-fit: contain; mix-blend-mode: multiply; filter: brightness(1.1);">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                <a href="http://localhost/?p=products&c=c4ca4238a0b923820dcc509a6f75849b&s=8f14e45fceea167a5a36dedd4bea2543" class="text-decoration-none">
                    <div class="card text-white rounded-lg shadow-sm" style="background-color: #00CED1 !important; border-radius: 15px; overflow: hidden; position: relative; cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body py-5 px-4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px;">
                            <h4 class="card-title fw-bold" style="font-size: 1.75rem;">FISH FOODS</h4>
                            <img src="customer/food.gif" alt="food" style="width: 120px; height: 120px; object-fit: contain; mix-blend-mode: multiply; filter: brightness(1.1);">
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Video Section -->
    <div class="container px-4 px-lg-5 my-5 video-section-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <video class="w-100" controls style="max-height: 400px; object-fit: contain;">
                            <source src="customer/oyeevid.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-12 text-center">
                <p class="lead mb-4 text-white">Experience the joy of shopping for your beloved pets with our extensive collection of premium products. From nutritious food to stylish accessories, we ensure your pets receive only the best. Our expert staff is always ready to assist you in finding the perfect items for your furry friends. Join our community of pet lovers and discover why we're the preferred choice for pet care essentials.</p>
                <a href="javascript:void(0)" onclick="uni_modal('','customer/login.php')" class="btn btn-primary btn-lg px-5">Shop Now</a>
            </div>
        </div>
    </div>

<?php 
} else {
    // Handle other pages
    if(!file_exists('customer/'.$page.".php") && !is_dir('customer/'.$page)){
        include 'customer/404.html';
    } else {
        if(is_dir('customer/'.$page))
            include 'customer/'.$page.'/index.php';
        else
            include 'customer/'.$page.'.php';
    }
}
?>

<?php require_once(__DIR__ . '/inc/footer.php'); ?>

<!-- Modal Components -->
<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content" style="border: 2px solid #0d6efd; border-top-left-radius: 0; border-top-right-radius: 0; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <div class="modal-header" style="background-color: #0d6efd; color: #fff; border-top-left-radius: 0; border-top-right-radius: 0; display: flex; align-items: center; justify-content: space-between;">
          <h5 class="modal-title" style="color: #fff;">Confirmation</h5>
        </div>
        <div class="modal-body" style="background-color: #fffae6; color: #333;">
          <div id="delete_content"></div>
        </div>
        <div class="modal-footer" style="background-color: #e3f0ff; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; display: flex;">
          <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog rounded-0 modal-md modal-dialog-centered" role="document">
      <div class="modal-content rounded-0">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
</div>

<div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog rounded-0 modal-full-height modal-md" role="document">
      <div class="modal-content rounded-0">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
</div>

<div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
</div>

</body>
</html> 