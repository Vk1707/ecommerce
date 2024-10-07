<?php
    include('./inc/dbclass.php');
    include('onsite-click.php');
    $db =  new Database();


    if (isset($_POST['form_add_to_cart'])) {
        $p_id = $_GET['product_id'];

        // Get the current stock of the product
        $result = $db->select("SELECT * FROM product WHERE id= $p_id");				
        foreach ($result as $row) {
            $current_p_qty = $row['qty'];
        }

        // Check if the quantity selected is greater than the available stock
        if ($_POST['qty'] > $current_p_qty):
            $temp_msg = 'Sorry! There are only ' . $current_p_qty . ' item(s) in stock';
            ?>
            <script type="text/javascript">
                alert('<?php echo $temp_msg; ?>');
            </script>
            <?php
                else:
            // Check if the product is already in the cart
            if (isset($_SESSION['p_id_cart'])) {
                $arr_p_id_cart = array();
                $arr_p_qty_cart = array();
                $arr_p_unit_price_cart = array();

                $i = 0;
                foreach ($_SESSION['p_id_cart'] as $key => $value) {
                    $i++;
                    $arr_p_id_cart[$i] = $value;
                }

                $added = 0;
                for ($i = 1; $i <= count($arr_p_id_cart); $i++) {
                    if ($arr_p_id_cart[$i] == $_REQUEST['product_id']) {
                        $added = 1;
                        break;
                    }
                }

                if ($added == 1) {
                    $error_message1 = 'This product is already added to the shopping cart.';
                } else {
                    // Add the product to the cart
                    $i = 0;
                    foreach ($_SESSION['p_id_cart'] as $key => $res) {
                        $i++;
                    }
                    $new_key = $i + 1;

                    $_SESSION['p_id_cart'][$new_key] = $_POST['product_id'];
                    $_SESSION['p_qty_cart'][$new_key] = $_POST['qty'];
                    $_SESSION['p_unit_price_cart'][$new_key] = $_POST['sale_price'];
                    $_SESSION['p_name_cart'][$new_key] = $_POST['p_name'];
                    $_SESSION['p_f_photo_cart'][$new_key] = $_POST['p_photo'];

                    $success_message1 = 'Product is added to the cart successfully!';
                }
            } else {
                // If no cart session exists, create a new one
                $_SESSION['p_id_cart'][1] = $_POST['product_id'];
                $_SESSION['p_qty_cart'][1] = $_POST['qty'];
                $_SESSION['p_unit_price_cart'][1] = $_POST['sale_price'];
                $_SESSION['p_name_cart'][1] = $_POST['p_name'];
                $_SESSION['p_f_photo_cart'][1] = $_POST['p_photo'];

                $success_message1 = 'Product is added to the cart successfully!';
            }
        endif;
    }

    // Fetch the product details and other relevant information
    $product_id = $_GET['product_id'];
    $product = $db->select_single("SELECT * FROM product WHERE id = $product_id");
    $products = $db->select("SELECT * FROM product WHERE featured=1 ORDER BY id DESC LIMIT 8");
    $productImages = $db->select("SELECT * FROM product_images WHERE product_id=$product_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Hot Shoppinng Deals : <?= $product['seo_title'] ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="<?= $product['seo_keyword']?>" name="keywords">
    <meta content="<?= $product['seo_description'] ?>" name="description">

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
    <style>
        .table td, .table tr{
            padding: 5px;
            width: auto;
        }
    </style>
</head>

<body>
<?php
if (!empty($error_message1)) {
    echo "<script>alert('" . $error_message1 . "')</script>";
}
if (!empty($success_message1)) {
    echo "<script>alert('" . $success_message1 . "')</script>";
    header('location: detail.php?product_id=' . $product_id);
}
?>
<?php
    include_once("./inc/shop_navbar.php");
?>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="index.php">Home</a>
                    <?php 
                        $catId = $product['category_id'];
                        $catName = $db->select_single("SELECT name FROM product_category WHERE id = $catId");
                    ?>
                    <a class="breadcrumb-item text-dark" href="shop.php?category_id=<?= $catId ?>"><?= $catName['name'] ?></a>
                    <span class="breadcrumb-item active"><?= $product['name'] ?></span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        <?php if(empty($productImages)){
                           echo "<div class='carousel-item active'>
                                 <img class='w-100 h-100' src='uploads/products/image-not-available.png' alt='Image'>
                                </div>";
                        }
                        else {
                        foreach($productImages as $index=>$image)
                        {
                        $active = $index==0?'active':'';
                        ?>
                            <div class="carousel-item <?= $active ?>">
                                <img class="w-100 h-100" src="uploads/products/<?= $image['url'] ?>" alt="Image">
                            </div>
                        <?php 
                        } }
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7  mb-30">
                <div class="h-100 bg-light p-30">
                    <h1 class="h3"><?= $product['name'] ?></h1>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1"><?="(". $product['views']. " Views)"?></small>
                    </div>
                    <form action="" method= "post">
                        <div class="d-flex  mt-2">
                            <span class="h5" style="color:#079707;"><?= "₹ " . $product['sale_price']?></span>
                            <span class="text-muted ml-2 h6"><del><?= "₹ " . $product['mrp'] ?></del></span>&nbsp;
                            <span class="h5">                                        
                                <?php
                                    $mrpPrice = $product['mrp'];
                                    $discountedPrice = $product['sale_price'];
                                    // Ensure no division by zero
                                    if ($mrpPrice > 0) {
                                        $discountPercentage = (($mrpPrice - $discountedPrice) / $mrpPrice) * 100;
                                        echo "-". round($discountPercentage,2) . '%';
                                    } else {
                                        echo '0%';
                                    }
                                ?>
                            </span>
                        </div>    
                        <p class="mb-4"><?= $product['short_description']?></p>

                        <div class="d-flex align-items-center mb-4 pt-2">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="sale_price" value="<?= $product['sale_price'] ?>">
                            <input type="hidden" name="p_name" value="<?= $product['name'] ?>">
                            <input type="hidden" name="p_photo" value="<?= $productImages[0]['url'] ?>">
                            <div class="input-group quantity mr-3" style="width: 130px;">
                                <div class="input-group-btn">
                                    <span class="btn btn-primary btn-minus">
                                        <i class="fa fa-minus"></i>
                                </span>
                                </div>
                                <input type="number" step="1" min="1" max="" name="qty" value="1" size="4" pattern="[0-9]*" class="form-control bg-secondary border-0 text-center" value="1">
                                <div class="input-group-btn">
                                    <a class="btn btn-primary btn-plus">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <button type="submit" name="form_add_to_cart" id="form_add_to_cart" class="btn btn-primary px-3"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</button>
                        </div>
                    </form>
                    <!-- <a href="#" class="btn btn-primary px-5" onclick="handleBuyNowClick('<?= $product['product_url'] ?>', <?= $product_id ?>)" target="_blank"><i class="fa fa-shopping-cart mr-1"></i> Buy Now</a> -->
                    <div class="d-flex pt-2">
                        <strong class="text-dark mr-2">Share on:</strong>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="https://www.facebook.com/sharer/sharer.php?u=<?="https://www.hotshoppingdeals.in/detail.php?product_id=". $product_id ?>" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-dark px-2" href="https://twitter.com/intent/tweet?url=<?="https://www.hotshoppingdeals.in/detail.php?product_id=". $product_id ?>&text=Check%20out%20this%20product:%20<?= urlencode($product['name']) ?>" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <?php     
                                $whatsappMessage = "Check out this product: " . $product['name'] . " - " ."https://www.hotshoppingdeals.in/detail.php?product_id=". $product_id;
                                $whatsappUrl = "https://api.whatsapp.com/send?text=" . urlencode($whatsappMessage);
                            ?>
                            <a class="text-dark px-2" href="<?= $whatsappUrl ?>" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a class="text-dark px-2" href="https://pinterest.com/pin/create/button/?url=<?="https://www.hotshoppingdeals.in/detail.php?product_id=". $product_id ?>&media=<?= urlencode('https://www.hotshoppingdeals.in/uploads/products/' . $productImages[0]['url']) ?>&description=<?= urlencode($product['name']) ?>" target="_blank">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <span class="mb-3 h4">Product Description</span>
                                <div class="description-p"><?= $product['description'] ?></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->
    <!-- Featuured Products Start -->
    <div class="container-fluid  pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Featured Products</span></h2>
        <div class="row px-xl-5">
            <?php foreach($products as $product){  
                ?>
                <a href="detail.php?product_id=<?= $product['id'] ?>">
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" height="318px" width="318px !important" src="uploads/products/<?= GetProductThumbnail($product['id']) ?>" alt="">
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
                                <a class="h6 text-decoration-none text-truncate" href="detail.php?product_id=<?= $product['id'] ?>"><h2 class="h6"><?= $product['name'] ?></h2></a>
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
                </a>    
            <?php } ?>
        </div>
    </div>
    <!-- Products End -->

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
    <script>
        function handleBuyNowClick(url, productId) {
            let xhr = new XMLHttpRequest();
            
            xhr.open('GET', 'external_click.php?product_id=' + productId, true);

            // Send the request
            xhr.send();

            window.open(url, '_blank');
        }
        // Product Quantity
            $(document).ready(function () {
                $('.quantity').each(function () {
                    var quantityInput = $(this).find('input');
                    var buttonMinus = $(this).find('.btn-minus');
                    var buttonPlus = $(this).find('.btn-plus');

                    buttonMinus.on('click', function () {
                        var oldValue = parseFloat(quantityInput.val());
                        var newVal = oldValue > 1 ? oldValue - 1 : 1;
                        quantityInput.val(newVal);
                    });

                    buttonPlus.on('click', function () {
                        var oldValue = parseFloat(quantityInput.val());
                        var newVal = oldValue + 1;
                        quantityInput.val(newVal);
                    });
                });
            });

    </script>

</body>

</html>