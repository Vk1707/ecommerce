<?php
include_once("./inc/dbclass.php");

$db = new Database();
$estores = $db->select("SELECT * FROM ecommerce_store");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Estore Products</title>
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
    <style>
        .store-item {
            border: 1px solid #e0e0e0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            margin-bottom: 20px;
        }

        .store-item:hover {
            box-shadow: 0px 6px 16px rgba(255, 223, 0, 0.5); 
            transform: translateY(-10px); 
        }

        .store-img {
            margin: 15px;
            overflow: hidden;
            position: relative;
        }

        .store-img img {
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }

        .store-img:hover img {
            transform: scale(1.1);
        }

        .store-item .btn {
            border-color: #ffc107;
            color: #ffc107;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .store-item .btn:hover {
            background-color: #ffc107;
            color: #fff;
        }

        .text-center h5 {
            margin: 15px 0;
            font-weight: 600;
            color: #333;
        }
    </style>


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
                    <!-- <a class="breadcrumb-item text-dark" href="#">E Store</a> -->
                    <span class="breadcrumb-item active">All Stores</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Stores List Start -->
    <div class="container-fluid">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">All Stores</span></h2>
        <div class="row px-xl-5">
            <?php foreach($estores as $estore) { 
                if ($estore['status'] != 0) { ?>
            <div class="col-lg-3 col-md-4 col-sm-4 pb-1">
                <div class="store-item bg-light mb-4 text-center">
                    <div class="store-img position-relative overflow-hidden">
                        <a href="estore-product.php?estore_id=<?= $estore['estore_id'] ?>">
                            <img class="img-fluid" src="uploads/estore/<?= $estore['estore_img'] ?>" alt="<?= $estore['estore_name'] ?>">
                        </a>
                    </div>
                    <div class="text-center py-3">
                        <h5 class="font-weight-semi-bold"><?= $estore['estore_name'] ?></h5>
                        <a class="btn btn-outline-warning py-2 px-4 mt-2" href="estore-product.php?estore_id=<?= $estore['estore_id'] ?>">View Products</a>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
    <!-- Stores List End -->

    <!-- Footer Start -->
    <?php include_once("./inc/shop_footer.php"); ?>
    <!-- Footer End -->

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
