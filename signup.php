<?php
include_once("./inc/dbclass.php");

$db = new Database();   
$name = $email = $password = $mobile = $address = $gender = $dob = $state = $city = $pincode ="";
$error = [];
if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $password = md5($pass);
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];

    $emailCheck = $db->select_single("SELECT * FROM customer WHERE cust_email ='$email'");
    
    if ($emailCheck) {
        $error['email'] =  'Email already exists';
    } else {
        $data = [
            'cust_name' => $name,
            'cust_email' => $email,
            'cust_password' => $password,
            'cust_phone' => $mobile,
            'cust_address' => $address,
            'cust_gender' => $gender,
            'cust_dob' => $dob,
            'cust_city' => $city,
            'cust_state' => $state,
            'cust_pincode' => $pincode
        ];
        if($db->insert('customer', $data)){
            gotopage("signin.php?status=1");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
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
                    <span class="breadcrumb-item active">Contact</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Contact Start -->
    <div class="container-fluid">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Sign Up</span></h2>
        <div class="row px-xl-5 d-flex justify-content-center">
            <div class="col-lg-12 mb-5">
                <div class="contact-form bg-light p-30 d-block">
                    <form class="row" enctype="" id="contactForm" method="POST" novalidate="novalidate">
                        <div class="col-lg-6">
                        <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Your Full Name"
                                required="required" value="<?= $name ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-lg-6">
                        <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email"
                                required="required" data-validation-required-message="Please enter your email" value="<?= $email ?>"  />
                            <p class="help-block text-danger"><?= isset($error['email'])?"Email Already Exist": "" ?></p>
                        </div>
                        
                        <div class="col-lg-6">
                        <label>Mobile</label>
                            <input type="text" class="form-control" name="mobile" placeholder="Mobile Number" required="required" value="<?= $mobile ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        

                        <div class="col-lg-3">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" id="dob" required="required" value="<?= $dob ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label>Gender</label>
                            <select class="form-control" name="gender" required="required">
                                <option value="">Select Gender</option>
                                <option value="male" <?= ($gender == 'male') ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= ($gender == 'female') ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                        <label>Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Address" required="required" value="<?= $address ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        
                        <div class="col-lg-4">
                        <label>State</label>
                            <input type="text" class="form-control" name="state" placeholder="State" required="required" value="<?= $state ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        
                        <div class="col-lg-4">
                        <label>City</label>
                            <input type="text" class="form-control" name="city" placeholder="City" required="required" value="<?= $city ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        
                        <div class="col-lg-4">
                        <label>Pincode</label>
                            <input type="text" class="form-control" name="pincode" placeholder="Pincode" required="required" value="<?= $pincode ?>" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-lg-6">
                        <label>Password</label>
                            <input type="password" class="form-control"  id="password" placeholder="Password"
                                required="required" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-lg-6">
                        <label>Confirm Password</label>
                            <input type="password" class="form-control" name="password" id="repassword" placeholder="Re-Enter Password"
                                required="required" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-center">
                            <button name="signup" class="btn btn-primary py-2 px-4" type="submit" id="sendMessageButton">Sign Up</button>
                        </div>
                    </form>
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
    <!-- <script src="shop/assets/mail/jqBootstrapValidation.min.js"></script> -->
    <script src="shop/assets/mail/signup.js"></script>

    <!-- Template Javascript -->
    <script src="shop/assets/js/main.js"></script>
</body>

</html>