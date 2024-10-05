<?php
include_once("./inc/dbclass.php");
$db = new Database();

if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}

$userEmail = $_SESSION['user_email'];
$user = $db->select_single("SELECT * FROM customer WHERE cust_email ='$userEmail'");

if ($user) {
    $_SESSION['customer'] = $user;
}

if (!$user) {
    header("Location: signin.php");
    exit();
}

$successMessage = '';
$errorMessage = '';
$emailExist = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get billing details
    $billingName = trim($_POST['billing_name']);
    $billingMobile = trim($_POST['billing_mobile']);
    $billingAddress = trim($_POST['billing_address']);
    $billingCity = trim($_POST['billing_city']);
    $billingState = trim($_POST['billing_state']);
    $billingPin = trim($_POST['billing_pin']);

    // Get shipping details
    $shippingName = trim($_POST['shipping_name']);
    $shippingMobile = trim($_POST['shipping_mobile']);
    $shippingAddress = trim($_POST['shipping_address']);
    $shippingCity = trim($_POST['shipping_city']);
    $shippingState = trim($_POST['shipping_state']);
    $shippingPin = trim($_POST['shipping_pin']);

    // Check if "Same as billing" checkbox is checked
    if (isset($_POST['same_as_billing'])) {
        $shippingName = $billingName;
        $shippingMobile = $billingMobile;
        $shippingAddress = $billingAddress;
        $shippingCity = $billingCity;
        $shippingState = $billingState;
        $shippingPin = $billingPin;
    }

    // Validate required fields
    if (empty($billingName) || empty($billingMobile) || empty($billingAddress) ||
        empty($billingCity) || empty($billingState) || empty($billingPin)) {
        $errorMessage = 'All billing fields are required.';
    } else {
        $data = [
            'cust_b_name' => $billingName,
            'cust_b_phone' => $billingMobile,
            'cust_b_address' => $billingAddress,
            'cust_b_city' => $billingCity,
            'cust_b_state' => $billingState,
            'cust_b_pincode' => $billingPin,
            'cust_s_name' => $shippingName,
            'cust_s_phone' => $shippingMobile,
            'cust_s_address' => $shippingAddress,
            'cust_s_city' => $shippingCity,
            'cust_s_state' => $shippingState,
            'cust_s_pincode' => $shippingPin
        ];

        $updateSuccess = $db->update('customer', $data, 'cust_email = :id', ['id' => trim($userEmail)]);
        // echo $updateSuccess;
        if ($updateSuccess) {
            $successMessage = 'Your profile has been updated successfully.';

        } else {
            $errorMessage = 'There was an error updating your profile. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Billing & Shipping Address</title>
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

    <!-- Custom CSS for additional styling -->
</head>

<body>
<?php include_once("./inc/shop_navbar.php"); ?>
<?php include_once("customer-sidebar.php"); ?>

<div class="container-fluid">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Update Profile</span>
    </h2>

    <div class="row">
        <div class="col-lg-12">
            <div class="bg-light p-4 rounded shadow-sm">
                <!-- Success or Error Messages -->
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>

                <!-- Form Section -->
                <form action="" method="POST" class="row d-flex" >
                    <!-- Billing Address Section -->
                    <div class="col-lg-6 form-section border-right">
                        <h5 class="section-title mb-4">Billing Address</h5>
                        <div class="form-group">
                            <label for="billing_name">Full Name</label>
                            <input class="form-control" type="text" name="billing_name" id="billing_name" placeholder="Enter Your Name" value="<?= htmlspecialchars($user['cust_b_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="billing_mobile">Mobile No</label>
                            <input class="form-control" type="text" name="billing_mobile" id="billing_mobile" placeholder="Enter Your Mobile" value="<?= htmlspecialchars($user['cust_b_phone']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="billing_address">Address</label>
                            <textarea name="billing_address" id="billing_address" class="form-control" required><?= htmlspecialchars($user['cust_b_address']); ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="billing_city">City</label>
                                <input class="form-control" type="text" name="billing_city" id="billing_city" placeholder="Enter Your City" value="<?= htmlspecialchars($user['cust_b_city']); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="billing_state">State</label>
                                <input class="form-control" type="text" name="billing_state" id="billing_state" placeholder="Enter Your State" value="<?= htmlspecialchars($user['cust_b_state']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="billing_pin">Pin Code</label>
                            <input class="form-control" type="text" name="billing_pin" id="billing_pin" placeholder="Enter Your Pincode" value="<?= htmlspecialchars($user['cust_b_pincode']); ?>" required>
                        </div>
                    </div>

                    <!-- Shipping Address Section -->
                    <div class="col-lg-6 form-section">
                        <div class="d-flex align-items-center mb-4">
                            <h5 class="section-title position-relative text-uppercase mb-0 pr-3">Shipping Address</h5>
                            <div class="ml-auto">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="same_as_billing" name="same_as_billing" onclick="copyBillingInfo()">
                                    <label class="custom-control-label" for="same_as_billing">Same as Billing</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shipping_name">Full Name</label>
                            <input class="form-control" type="text" name="shipping_name" id="shipping_name" placeholder="Enter Your Name" value="<?= htmlspecialchars($user['cust_s_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="shipping_mobile">Mobile No</label>
                            <input class="form-control" type="text" name="shipping_mobile" id="shipping_mobile" placeholder="Enter Your Mobile" value="<?= htmlspecialchars($user['cust_s_phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="shipping_address">Address</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-control"><?= htmlspecialchars($user['cust_s_address']); ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="shipping_city">City</label>
                                <input class="form-control" type="text" name="shipping_city" id="shipping_city" placeholder="Enter Your City" value="<?= htmlspecialchars($user['cust_s_city']); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shipping_state">State</label>
                                <input class="form-control" type="text" name="shipping_state" id="shipping_state" placeholder="Enter Your State" value="<?= htmlspecialchars($user['cust_s_state']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="shipping_pin">Pin Code</label>
                            <input class="form-control" type="text" name="shipping_pin" id="shipping_pin" placeholder="Enter Your Picode" value="<?= htmlspecialchars($user['cust_s_pincode']); ?>">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-lg-12 d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once("./inc/shop_footer.php"); ?>

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
function copyBillingInfo() {
    if (document.getElementById('same_as_billing').checked) {
        document.querySelector('input[name="shipping_name"]').value = document.querySelector('input[name="billing_name"]').value;
        document.querySelector('input[name="shipping_mobile"]').value = document.querySelector('input[name="billing_mobile"]').value;
        document.querySelector('textarea[name="shipping_address"]').value = document.querySelector('textarea[name="billing_address"]').value;
        document.querySelector('input[name="shipping_city"]').value = document.querySelector('input[name="billing_city"]').value;
        document.querySelector('input[name="shipping_state"]').value = document.querySelector('input[name="billing_state"]').value;
        document.querySelector('input[name="shipping_pin"]').value = document.querySelector('input[name="billing_pin"]').value;
    } else {
        document.querySelector('input[name="shipping_name"]').value = "";
        document.querySelector('input[name="shipping_mobile"]').value = "";
        document.querySelector('textarea[name="shipping_address"]').value = "";
        document.querySelector('input[name="shipping_city"]').value = "";
        document.querySelector('input[name="shipping_state"]').value = "";
        document.querySelector('input[name="shipping_pin"]').value = "";
    }
}
    </script>
</body>
</html>
