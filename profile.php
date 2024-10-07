<?php
include_once("./inc/dbclass.php");
$db = new Database();


if (!isset($_SESSION['user_email'])) {
    // If not logged in, redirect to sign-in page
    header("Location: signin.php");
    exit();
}

// Fetch user email from session or cookie
$userEmail = $_SESSION['user_email'];

// Get user details from the database
$user = $db->select_single("SELECT * FROM customer WHERE cust_email ='$userEmail'");

// If user data is not found, redirect to sign-in page
if (!$user) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User Profile</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="User Profile Page" name="description">

    <!-- Favicon -->
    <link href="shop/assets/img/shopify.svg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="shop/assets/css/style.css" rel="stylesheet">
    <style>
        /* body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        } */
        /* .section-title {
            color: #333;
        } */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
        }
        .card-body p {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            transition: background-color 0.3s;
        }
        .card-body p:hover {
            background-color: #d9fdeebf;
            cursor: pointer;
        }
        .text-right {
            margin-top: 20px;
        }
        .addHeight{
            height: 106px;
        }
    </style>
</head>

<body>
    <?php include_once("./inc/shop_navbar.php"); ?>
    <?php include_once("customer-sidebar.php"); ?>

    <div class="container-fluid">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
            <span class="bg-secondary pr-3">User Profile</span>
        </h2>
        <div class="row px-xl-5">
            <div class="col-lg-12 mb-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= htmlspecialchars($user['cust_name']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($user['cust_email']) ?></p>
                                <p><strong>Mobile:</strong> <?= htmlspecialchars($user['cust_phone']) ?></p>
                                <p><strong>Gender:</strong> <?= htmlspecialchars($user['cust_gender']) ?></p>
                                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['cust_dob']) ?></p>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <p class="addHeight"><strong>Address:</strong> <?= htmlspecialchars($user['cust_address']) ?></p>
                                <p><strong>State:</strong> <?= htmlspecialchars($user['cust_state']) ?></p>
                                <p><strong>City:</strong> <?= htmlspecialchars($user['cust_city']) ?></p>
                                <p><strong>Pincode:</strong> <?= htmlspecialchars($user['cust_pincode']) ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="update-profile.php" class="btn btn-primary">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once("./inc/shop_footer.php"); ?>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="shop/assets/lib/easing/easing.min.js"></script>
    <script src="shop/assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="shop/assets/js/main.js"></script>
</body>

</html>
