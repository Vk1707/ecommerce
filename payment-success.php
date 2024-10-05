<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Order Placed</title>
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

    <?php
    if(isset($_GET['status']))
    {
        if($_GET['status']==1)
        echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>You are Successfully Registered, Now You Can Sign in</b></div>";

    }
    ?>    
    <div class="container-fluid">
    <div class="row px-xl-5 d-flex justify-content-center">
        <div class="col-lg-7  mb-5">
            <div class="contact-form bg-light p-30">
                <h1>Order Placed Successfully</h1>
                <p>Thank you for your purchase. Your transaction was completed successfully.</p>
                <p>Your order number is: <strong>#<?= $_GET['orderno'] ?></strong></p>
                <p>A confirmation email has been sent to your email address.</p>
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>
</div>
    <!-- Contact End -->

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