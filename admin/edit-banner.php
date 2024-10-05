<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db = new Database();

// Get banner ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the banner details
$banner = $db->select_single("SELECT * FROM banners WHERE id =  $id");

// Fetch categories for the dropdown
$categories = $db->select("SELECT *  FROM product_category WHERE status = 1");

if (!$banner) {
    die("Banner not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['banner_title'];
    $sub_title = $_POST['sub_title'];
    $cat_id = $_POST['banner_link'];
    $status = $_POST['banner_status'];
    
    // Handle file upload
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $image = $_FILES['banner_image'];
        $imageName = $image['name'];
        $image_path = '../uploads/banner/' . basename($image['name']);
        if(move_uploaded_file($image['tmp_name'], $image_path)){
            $old_image = "../uploads/banner/" . $banner['image'];
            unlink($old_image);
        }
    } else {
        $imageName = $banner['image'];
    }
    $data = ['title' => $title, 'sub_title' => $sub_title, 'image' =>$imageName, 'cat_id' => $cat_id, 'status' => $status ];
    // Update the banner

    $db->update('banners', $data,'id = :id', ['id' => $id]);


    gotopage("manage-banner.php?status=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Banner</title>
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

<?php include_once("inc/admin_navbar.php"); ?>
<div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
  <div class="d-flex col-lg-12 justify-content-between">
      <div class="fw-bold h4 mt-auto mb-auto">Edit Banner</div>
        <a class="btn btn-primary" href="manage-banner.php">Manage Banners</a>
    </div>
</div>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                    <form enctype="multipart/form-data" method="POST" action="" class="card p-4" id="bannerForm">
                        <h3 class="mb-4">Edit Banner</h3>
                        <input type="hidden" name="form_type" value="banner">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" name="banner_title" id="banner_title" class="form-control" placeholder="Enter title" value="<?= htmlspecialchars($banner['title']); ?>" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="banner_link" class="form-label">Category <sup class="text-danger">*</sup></label>
                                <select class="form-select" name="banner_link" id="banner_link" required>
                                    <option value="0">Select Category</option>
                                    <?php foreach($categories as $category) { ?>
                                        <option value="<?= $category['id'] ?>" <?= $banner['cat_id'] == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="sub_title" class="form-label">Sub Title</label>
                                <textarea name="sub_title" id="sub_title" class="form-control" rows="1" placeholder="Enter description" required><?= htmlspecialchars($banner['sub_title']); ?></textarea>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="banner_status" class="form-label">Status</label>
                                <select class="form-select" name="banner_status" id="banner_status" required>
                                    <option value="1" <?= $banner['status'] == '1' ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= $banner['status'] == '0' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="col-sm-2 col-form-label">Uploaded Image</label>
                            <div class="col-sm-10">
                                <img src="../uploads/banner/<?= htmlspecialchars($banner['image']); ?>" alt="Banner" class="img-fluid" style="max-width: 300px;">
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="formFile" class="form-label">Image (JPG/PNG)</label>
                            <input type="file" name="banner_image" id="formFile" class="form-control" accept="image/*">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="col-sm-2 col-form-label">Preview</label>
                            <div class="col-sm-10" id="imagePreview"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Banner</button>
                    </form>
            </div>
        </div>
    </div>
</div>

<?php include_once("inc/admin_footer.php"); ?>

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>
</html>
