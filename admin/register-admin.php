<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");
$db = new Database();

if(isset($_POST['submit'])){
    $name = $_POST['admin_name'];
    $email = $_POST['admin_email'];
    $mobile = $_POST['admin_mobile'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    if($emailCheck = $db->select_single("SELECT * FROM admin WHERE user_name ='$email'")){
      $emailExist = "Email is Already Exist";
    } else{
      $data = ['full_name' => $name, 'user_name' => $email,'mobile' =>$mobile, 'user_pass' => $password,'user_status'=> $status];
      if($db->insert('admin', $data)){
          gotopage("manage-admin.php?status=1");
      }
    }
}
?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Register Admin</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../admin/assets/img/icons/brands/slack.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->
<?php include_once("inc/admin_navbar.php") ?>
<div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
  <div class="d-flex col-lg-12 justify-content-between">
      <div class="fw-bold h4 m-auto">Register Admin 🚀</div>
        <!-- <a class="btn btn-primary" href="-admin.php"></a> -->
    </div>
</div>
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class=" container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              <form class="mb-3" action="" method="POST">
                <div class="mb-3">
                  <label for="admin_name" class="form-label">Username</label>
                  <input type="text" class="form-control" id="admin_name" name="admin_name" placeholder="Enter your admin name" autofocus required/>
                </div>
                <div class="mb-3">
                  <label for="admin_email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="Enter your email" required/>
                </div>
                <div class="mb-3">
                  <label for="admin_mobile" class="form-label">Mobile</label>
                  <input type="text" class="form-control" id="admin_mobile" name="admin_mobile" placeholder="Enter your mobile" required/>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                    <input class="form-check-input" type="radio" id="inlineRadio1" name="status" value="1" checked> <label class="form-check-label" for="inlineRadio1">Active</label>
                    <input class="form-check-input" type="radio" id="inlineRadio2" name="status" value="0" > <label class="form-check-label" for="inlineRadio2">Not Active</label>
                  </div>
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input  type="password"  id="password"  class="form-control"  name="password"  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"  aria-describedby="password" required />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary d-grid w-100">Register</button>
              </form>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>
    <?php include_once("inc/admin_footer.php") ?>

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
  </body>
</html>
