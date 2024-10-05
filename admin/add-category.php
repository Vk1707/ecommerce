<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");


if (isset($_SESSION['catImgUploadError']) || isset($_SESSION['catImgUpdateError']) ) {
  unset($_SESSION['catImgUploadError']);
  unset($_SESSION['catImgUpdateError']);
}

// include_once("../inc/dal.php");
$name = $description = $status ="";

$db = new Database();
if(isset($_POST['add_category'])){
  $cat_name = $_POST['cat_name'];
  $description = $_POST['description'];
  $status = $_POST['status'];

  $timestamp = date("Y-m-d h:i:s");
  $data = ['name' => $cat_name, 'description' => $description, 'status' => $status, 'created_at' => $timestamp, 'modified_at' => $timestamp];
  $db->insert('product_category', $data);
  $trans_id = $db->lastInsertId();

  if($trans_id){
  // Handle image uploads
  $image = $_FILES['image'];
  $tmpName = $image['tmp_name'];
  $imageUploadErrors = [];
  $imageName = $image['name'];
  $imageType = mime_content_type($tmpName);
  $imageSize = getimagesize($tmpName);

  // Validate image type
  if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/jpg'])) {
      $imageUploadErrors[] = "Invalid image type for $imageName. Only JPEG, JPG, and PNG are allowed. Type $imageType Size $imageSize";
  }

  // Validate image dimensions (600x600)
  if ($imageSize[0] != 600 || $imageSize[1] != 600) {
      $imageUploadErrors[] = "Invalid image dimensions for $imageName. Image must be 600x600 pixels.";
  }

  if (empty($imageUploadErrors)) {
    if (move_uploaded_file($tmpName, '../uploads/category_img/' . $imageName)) {
      $timestamp = date("Y-m-d h:i:s");
      $data = ['image' => $imageName, 'modified_at' => $timestamp];
      $db->update('product_category', $data,'id = :id', ['id' => $trans_id]);
    } else {
        $imageUploadErrors[] = "Failed to upload image $imageName.";
    }
    gotopage("category-list.php?status=1");
  } else{
    $_SESSION['catImgUploadError'] = $imageUploadErrors;
    gotopage("category-list.php?status=2");
  }
}
}
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
<!-- Navbar -->
<?php include_once("inc/admin_navbar.php"); ?>
<!-- Content -->
<div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
  <div class="d-flex col-lg-12 justify-content-between">
      <div class="fw-bold h4 mt-auto mb-auto">Add Category</div>
      <a class="btn btn-primary" href="category-list.php">Category List</a>
  </div>
</div>
<?php
    if(isset($_POST['add_category']))
        echo "<div class='container-xxl mt-3 text-center text-danger'><b>$error</b></div>";
?>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xxl">
      <div class="card mb-4">
        <div class="card-body">
          <form enctype="multipart/form-data" method="POST">
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="cat_name">Name <sup class="text-danger">*</sup></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="cat_name" id="cat_name" value="<?= $name ?>" placeholder="Category Name" required />
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="description">Description <sup class="text-danger">*</sup></label>
              <div class="col-sm-10">
                <textarea id="description" class="form-control" placeholder="Category Description" aria-label="Category Description" name="description" required><?= $description ?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Active</label>
              <div class="col-sm-10">
                <input class="form-check-input" type="radio" name="status" id="yesActive" value="1" <?= $status==1?'checked':'' ?> /> <label class="form-check-label" for="yesActive">Yes</label>
                <input class="form-check-input" type="radio" name="status" id="noActive" value="0" <?= $status==0?'checked':'' ?> /> <label class="form-check-label" for="noActive">No</label>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="formFile">Image (Size 600x600) <sup class="text-danger">*</sup></label>
              <div class="col-sm-10">
                <input class="form-control" name="image" type="file" id="formFile" required/>
                <small class="form-text text-danger">Images must be 600x600 pixels and in JPEG , JPG or PNG format.</small>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Preview</label>
              <div class="col-sm-10" id="imagePreview"></div>
            </div>
            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button name="add_category" id="add_category" type="submit" class="btn btn-primary">Add Category</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- / Content -->
<?php
include_once("inc/admin_footer.php");
?>

<div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../admin/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../admin/assets/vendor/libs/popper/popper.js"></script>
    <script src="../admin/assets/vendor/js/bootstrap.js"></script>
    <script src="../admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../admin/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../admin/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- Main JS -->
    <script src="../admin/assets/js/main.js"></script>

    <!-- Page JS -->

    <script src="../admin/assets/js/form-basic-inputs.js"></script>
    <!-- Page JS -->
    <script src="../admin/assets/js/dashboards-analytics.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>
</html>