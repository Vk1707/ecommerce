<?php
include_once("./inc/dbclass.php");
$db = new Database();

// Set how many products per page
$limit = 16;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_SESSION['products'])) {
    $search = $_SESSION['products'];

    $prod_sql = "SELECT * FROM product WHERE name like '%$search%' LIMIT $limit OFFSET $offset";
    $products = $db->select($prod_sql);
    
    // Get the total count for pagination
    $total_products = $db->select_single("SELECT COUNT(*) as total FROM product WHERE name like '%$search%'");
    // print_r($total_products);


// Calculate the total number of pages
$total_pages = ceil($total_products['total'] / $limit);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Shop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

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

</head>

<body>

<?php
include_once("./inc/shop_navbar.php");
?>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="index.php">Home</a>
                    <span class='breadcrumb-item active'>Search Result</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
    

    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Product Start -->
            <div class="col-lg-12 col-md-12 ">
                <div class="row pb-3">
                <?php if(empty($products)){
                echo '
                    <div class="container-fluid">
                        <div class="col-lg-12 col-md-6 col-sm-6  pb-1">
                            <div class="text-center py-4 px-4">
                                <h1>0 Products</h1>
                            </div>
                        </div>
                    </div>';
                    unset($_SESSION['products']);
                }
                ?>
                    <?php foreach($products as $product) { if( empty($product['deleted_at']) ) { ?>
                    <div class="col-lg-3 col-md-6 col-sm-6  pb-1">
                        <a href="detail.php?product_id=<?= $p_id = $product['id'] ?>">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                            <?php 
                                $p_image = $db->select_single("SELECT url FROM product_images WHERE product_id= $p_id LIMIT 6"); ?>
                                <img class="img-fluid" style="object-fit:cover !important"  src="<?= !empty($p_image['url'])?'uploads/products/' . $p_image['url']: 'uploads/products/image-not-available.png'?>" alt="Product_Image">
                                <div class="discount-percent">
                                    <?php
                                        $mrpPrice = $product['mrp'];
                                        $discountedPrice = $product['sale_price'];
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
                                <a class="h6 text-decoration-none text-truncate" href="detail.php?product_id=<?= $product['id'] ?>"><?= $product['name'] ?></a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    <h6 style="color:#079707;"><?= "₹ " . $product['sale_price'] ?></h6>
                                    <h6 class="text-muted ml-2"><del><?= "₹ " . $product['mrp'] ?></del></h6>&nbsp;
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
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->

    <!-- Pagination Start -->
    <?php if(!empty($products)){ ?>
    <div class="col-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Previous Button -->
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next Button -->
                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php } ?>
    
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