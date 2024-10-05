<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");
$db = new Database();
$successMessage = '';
$passError = '';
$admin_id = $_SESSION['userID'];

if(isset($_POST['submit'])){
    $admin_id = $_SESSION['userID'];
    $current_password = trim($_POST['current_password']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    $sql = "SELECT * FROM admin WHERE user_pass = '$current_password' AND user_id = '$admin_id'";
    $urow = $db->select_single($sql);
    if(empty($urow)){
      $passError = "Password is Wrong";
    } 
    else{
      $data = ['user_pass' => $confirm_password];
      if($db->update('admin', $data, 'user_id = :user_id',['user_id'=>$admin_id])){
          $successMessage = "Your Password Change Successfully";
      }
    }
}


// echo $admin_id;
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Add Category</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../admin/assets/img/icons/brands/slack.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="../admin/assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../admin/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../admin/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../admin/assets/css/demo.css" />
  <link rel="stylesheet" href="../admin/assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../admin/assets/vendor/js/helpers.js"></script>
  <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../admin/assets/js/config.js"></script>
</head>

<body>
    <?php include_once("inc/admin_navbar.php") ?>
    <div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
    <div class="d-flex col-lg-12 justify-content-between">
        <div class="fw-bold h4 m-auto">Change Password 🔒</div>
            <!-- <a class="btn btn-primary" href="-admin.php"></a> -->
        </div>
    </div>
    <div class="container-xxl flex-grow-1 container-p-y">
    <div class=" container-p-y">
        <div class="authentication-inner">
            <div class="card">
                <div class="card-body">
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>
                    <form id="formAuthentication" class="mb-3" action="" method="POST">
                        <input type="hidden" name="user_id" value="<?= $admin_id['user_id'] ?>">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter your current password" required />
                            <p class="help-block text-danger"></p>
                            <?php if (isset($_POST['submit']) && !empty($passError)): ?>
                                <p class="help-block text-danger"><?= htmlspecialchars($passError); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required />
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required />
                            <p class="help-block text-danger"></p>

                        </div>
                        <button type="submit" name="submit" class="btn btn-primary d-grid w-100">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include_once("inc/admin_footer.php") ?>
</div>

    <!-- / Content -->
  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script>
    $(document).ready(function () {
        $('#formAuthentication').on('submit', function (e) {
            let isValid = true;
            // Validate Password
            let password = $('input#new_password').val().trim();

            // Validate Re-entered Password
            let repassword = $('input#confirm_password').val().trim();
            if (password !== repassword) {
                $('input#confirm_password').next('.help-block').text('Passwords do not match.');
                isValid = false;
            } else {
                $('input#confirm_password').next('.help-block').text('');
            }

            // Prevent form submission if there are validation errors
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
  </script>
</body>

</html>