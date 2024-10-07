<?php
include_once("./inc/dbclass.php");
$db = new Database();
if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

$user = $db->select_single("SELECT * FROM customer WHERE cust_email ='$userEmail'");
$userid = $user['cust_id'];

if (!$user) {
    header("Location: signin.php");
    exit();
}

$successMessage = '';
$errorMessage = '';
$emailExist = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $password = trim($_POST['password']);
    $userEmail = $_SESSION['user_email'];

    if (empty($name) || empty($email) || empty($mobile) || empty($address) || empty($gender) || empty($dob) || empty($state) || empty($city) || empty($pincode)) {
        $errorMessage = 'All fields are required.';
    } 

    if($emailCheck = $db->select_single("SELECT * FROM customer WHERE cust_email ='$email' AND cust_id != '$userid'")){
        $emailExist = "Email already exists.";
    } else {
        $data = [
            'cust_name' => $name,
            'cust_email' => $email,
            'cust_phone' => $mobile,
            'cust_address' => $address,
            'cust_gender' => $gender,
            'cust_dob' => $dob,
            'cust_city' => $city,
            'cust_state' => $state,
            'cust_pincode' => $pincode
        ];
        if (!empty($password)) {
            $data['password'] = $password;
        }

        $updateSuccess = $db->update('customer', $data, 'cust_email = :id', ['id' => trim($userEmail)]);
        if ($updateSuccess) {
            $successMessage = 'Your profile has been updated successfully.';
            $_SESSION['user_email'] = $email;
        } else {
            $errorMessage = 'There was an error updating your profile. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Update Profile</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="User Settings Page" name="description">

    <!-- Favicon -->
    <link href="shop/assets/img/shopify.svg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="shop/assets/css/style.css" rel="stylesheet">
</head>

<body>
<?php include_once("./inc/shop_navbar.php"); ?>
<?php include_once("customer-sidebar.php"); ?>

<div class="container-fluid">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Update Profile</span></h2>
    <div class="row px-xl-5">
        <div class="col-lg-12 mb-5">
            <div class="bg-light p-30">
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>
                <?php if (!empty($emailExist)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($emailExist); ?></div>
                <?php endif; ?>

                <form method="POST" class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['cust_name']); ?>" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['cust_email']); ?>" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="mobile">Mobile:</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?= htmlspecialchars($user['cust_phone']); ?>" required>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?= !empty($user['cust_dob']) ? date('Y-m-d', strtotime($user['cust_dob'])) : ''?>" required>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label>Gender:</label>
                        <select class="form-control" name="gender" required="required">
                            <option value="male" <?= ($user['cust_gender'] == 'male') ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= ($user['cust_gender'] == 'female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($user['cust_address']); ?>" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="state">State:</label>
                        <input type="text" class="form-control" id="state" name="state" value="<?= htmlspecialchars($user['cust_state']); ?>" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="city">City:</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($user['cust_city']); ?>" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="pincode">Pincode:</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?= htmlspecialchars($user['cust_pincode']); ?>" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="password">New Password (optional):</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="col-lg-12 d-flex justify-content-center"><button type="submit" class="btn btn-primary">Update Profile</button></div>
                </form>
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
