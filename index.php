<?php
include_once("./inc/dbclass.php");
$db = new Database();
$sql_category = "SELECT * FROM product_category";
$categories = $db->select($sql_category);
$products = $db->select("SELECT * FROM product");
$featuredproducts = $db->select("SELECT * FROM product WHERE featured = 1 ORDER BY id DESC LIMIT 8 ");
$womenCat = $db->select("SELECT * FROM product WHERE category_id = 52");
$estores = $db->select("SELECT * FROM ecommerce_store");

$banners = $db->select("SELECT * FROM banners WHERE status = 1 AND banner_type = 'large'");
$offers = $db->select("SELECT * FROM banners WHERE status = 1 AND banner_type = 'mini' LIMIT 2");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Hot Shopping Deals - Best Online Offers and Discounts on Top Brands</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="online shopping deals, best discounts online, hot deals on fashion, electronics deals, top brand offers, home appliances discounts, fashion sale offers, exclusive shopping deals, daily deals, HotShoppingDeals" name="keywords">
    <meta content="Explore unbeatable online offers and discounts on HotShoppingDeals.in. Shop top brands with massive savings on fashion, electronics, home essentials, and more. Don't miss out!" name="description">
<link rel="canonical" href="https://hotshoppingdeals.in/index.php">

    <!-- Favicon -->
    <link href="shop/assets/img/shopify.svg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="shop/assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="shop/assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="shop/assets/css/style.css" rel="stylesheet">
    <meta name='impact-site-verification' value='1131889208'>

</head>

<body>
    <!-- Navbar start -->
    <?php  include_once("./inc/shop_navbar.php"); ?>
    <!-- Navbar end -->

    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
            <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php foreach($banners as $index => $banner): ?>
                        <li data-target="#header-carousel" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
                    <?php endforeach; ?>
                </ol>
                <div class="carousel-inner">
                    <?php foreach($banners as $index => $banner): ?>
                    <div class="carousel-item position-relative <?= $index === 0 ? 'active' : '' ?>" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="./uploads/banner/<?= $banner['image'] ?>" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <span class="text-white mb-3 animate__animated animate__fadeInDown h1" style="line-height: 1.2;"><?= $banner['title'] ?></span>
                                <p class="mx-md-5 px-5 animate__animated animate__bounceIn"><?= $banner['sub_title'] ?></p>
                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="shop.php?category_id=<?= $banner['cat_id'] ?>">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            </div>
            <div class="col-lg-4">
                <?php foreach($offers as $offer): ?>
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="./uploads/banner/<?= $offer['image'] ?>" alt="<?= $offer['title'] ?>">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase"><?= $offer['sub_title'] ?></h6>
                        <h3 class="text-white mb-3"><?= $offer['title'] ?></h3>
                        <a href="shop.php?category_id=<?= $offer['cat_id'] ?>" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <span class="fa fa-check text-primary m-0 mr-3 h1"></span>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <span class="fa fa-shipping-fast text-primary m-0 mr-3 h1"></span>
                    <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <p class="fas fa-exchange-alt text-primary m-0 mr-3 h1"></p>
                    <h5 class="font-weight-semi-bold m-0 h6">14-Day Return</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <span class="fa fa-phone-volume text-primary m-0 mr-3 h1"></span>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->

    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Categories</span></h2>
        <div class="row px-xl-5 pb-3">
            <?php foreach($categories as $category) { if($category['status']!=0) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <a class="text-decoration-none" href="shop.php?category_id=<?= $cat_id = $category['id']?>">
                    <div class="cat-item img-zoom d-flex align-items-center mb-4">
                        <div class="overflow-hidden" style="width: 100px; height: 100px;">
                            <img class="img-fluid" src="uploads/category_img/<?= $category['image'] ?>" alt="">
                        </div>
                        <div class="flex-fill pl-3">
                            <h6><?= $category['name'] ?></h6>
                             <?php $catcount = $db->select_single("SELECT COUNT(id) as count FROM product WHERE category_id = $cat_id");?>
                            <small class="text-body"><?=  $catcount['count'] ?> Products</small>
                        </div>
                    </div>
                </a>
            </div>
            <?php } } ?>
        </div>
    </div>
    <!-- Categories End -->

    <!-- Featured Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Featured Products</span></h2>
        <div class="row px-xl-5">
            <?php foreach($featuredproducts as $featuredproduct) { if(empty($featuredproduct['deleted_at'])) {  ?>
                <a href="detail.php?product_id=<?= $fid = $featuredproduct['id'] ?>">
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <?php 
                                    $f_image = $db->select_single("SELECT url FROM product_images WHERE product_id= $fid");
                                    $altText = substr($featuredproduct['name'],0,25);
                                ?>
                                <img class="img-product" src="uploads/products/<?= empty($f_image['url'])?"image-not-available.png":$f_image['url'] ?>" alt="<?= $altText ?>">
                                <div class="discount-percent">
                                    <?php
                                        $mrpPrice = $featuredproduct['mrp'];
                                        $discountedPrice = $featuredproduct['sale_price'];
                                        // Ensure no division by zero
                                        if ($mrpPrice > 0) {
                                            $discountPercentage = (($mrpPrice - $discountedPrice) / $mrpPrice) * 100;
                                            echo '-'. round($discountPercentage,2) . '%';
                                        } else {
                                            echo '0%';  
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="text-center py-4 px-4">
                                <a class="h6 text-decoration-none text-truncate" href="detail.php?product_id=<?= $featuredproduct['id'] ?>"><?= $featuredproduct['name'] ?></a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                <h6 style="color:#079707;"><?= "₹ " .  $featuredproduct['sale_price'] ?></h6>
                                <h6 class="text-muted ml-2"><del><?= "₹ " .  $featuredproduct['mrp'] ?></del></h6>&nbsp;
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small>(99)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>    
            <?php }} ?>
        </div>
    </div>
    <!-- Featured Products End -->


    <!-- Offer Start -->
    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="shop/assets/img/Amazon-banner.png" alt="">
                    <div class="offer-text" style="background: rgba(61, 70, 77, 0.2);">
                        <h3 class="text-white mb-3">Amazon</h3>
                        <a href="estore-product.php?estore_id=6" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="shop/assets/img/Flipkart-banner.png" alt="">
                    <div class="offer-text" style="background: rgba(61, 70, 77, 0.2);">
                        <h3 class="text-white mb-3"> Flipkart</h3>
                        <a href="estore-product.php?estore_id=5" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offer End -->

    <!--Women Category Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Latest Women Products</span></h2>
        <div class="row px-xl-5">
            <?php
                $count = 0; 
                foreach ($womenCat as $wproduts) {
                    if (empty($wproduts['deleted_at'])) {
                        $count++; 
                        if ($count > 8) {
                            break;
                        }
            ?>
                <a href="detail.php?product_id=<?= $s_id = $wproduts['id'] ?>">
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <?php 
                                    $s_image = $db->select_single("SELECT url FROM product_images WHERE product_id= $s_id");
                                    $altText = substr($wproduts['name'],0,25);
                                ?>
                                <img class="img-product"  src="uploads/products/<?= empty($s_image['url'])?"image-not-available.png":$s_image['url'] ?>" alt="<?= $altText ?>">
                                <div class="discount-percent">
                                    <?php
                                        $mrpPrice = $wproduts['mrp'];
                                        $discountedPrice = $wproduts['sale_price'];
                                        // Ensure no division by zero
                                        if ($mrpPrice > 0) {
                                            $discountPercentage = (($mrpPrice - $discountedPrice) / $mrpPrice) * 100;
                                            echo '-'. round($discountPercentage,2) . '%';
                                        } else {
                                            echo '0%';  
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="text-center py-4 px-4">
                                <a class="h6 text-decoration-none text-truncate" href="detail.php?product_id=<?= $wproduts['id'] ?>"><?= $wproduts['name'] ?></a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    <h6 style="color:#079707;"><?= "₹ " .  $wproduts['sale_price'] ?></h6>
                                    <h6 class="text-muted ml-2"><del><?= "₹ " .  $wproduts['mrp'] ?></del></h6>&nbsp;
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small>(99)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php } } ?>
        </div>
    </div>
    <!-- Products End -->

    <!-- Vendor Start -->
    <div class="container-fluid py-5">
        <div class="row ">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    <?php foreach($estores  as $estore) {
                        if ($estore['status'] != 0) {
                        ?>
                    <div class="bg-light p-4">
                        <a href="estore-product.php?estore_id=<?=$estore['estore_id'] ?>">
                            <img src="uploads/estore/<?= $estore['estore_img'] ?>" width="150" height="150" alt="<?= $estore['estore_name'] ?>">
                        </a>
                    </div>
                    <?php } }?>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->

    <?php
    include_once("./inc/shop_footer.php");
    ?>

    <!-- Back to Top -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="shop/assets/lib/easing/easing.min.js"></script>
    <script src="shop/assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="shop/assets/mail/jqBootstrapValidation.min.js"></script>
    <script src="shop/assets/mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="shop/assets/js/main.js"></script>
</body>

</html>