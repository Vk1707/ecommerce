<?php
include_once("../inc/dbclass.php");
$db = new Database();

if (isset($_SESSION['estoreImageError']) || isset($_SESSION['estoreadd']) ) {
    unset($_SESSION['estoreImageError']);
    unset($_SESSION['estoreadd']);
}

if (isset($_POST['submit'])) {
    $estore_id = $_POST['estore_id'];
    $estore_name = $_POST['estore_name'];
    $status = $_POST['status'];
    $timestamp = date("Y-m-d h:i:s");

    $data = ['estore_name' => $estore_name, 'status' => $status, 'updated_at' => $timestamp];
    $db->update('ecommerce_store', $data, 'estore_id = :estore_id', ['estore_id' => $estore_id]);

    // Handle image uploads if a new image is selected
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $tmpName = $image['tmp_name'];
        $imageUploadErrors = [];
        $imageName =  $image['name'];
        $imageType = mime_content_type($tmpName);
        $imageSize = getimagesize($tmpName);
        
        // Validate image type
        if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/JPG'])) {
            $imageUploadErrors[] = "Invalid image type for $imageName. Only JPEG, JPG, and PNG are allowed.";
        }

        // Validate image dimensions (600x600)
        if ($imageSize[0] != 600 || $imageSize[1] != 600) {
            $imageUploadErrors[] = "Invalid image dimensions for $imageName. Image must be 600x600 pixels.";
        }

        // If there are no image validation errors, proceed to upload the image
        if (empty($imageUploadErrors)) {
            // Remove old image if it exists
            if (!empty($estore['estore_img']) && file_exists('../uploads/estore/' . $estore['estore_img'])) {
                unlink('../uploads/estore/' . $estore['estore_img']); // Remove the old image
            }

            // Upload the new image
            if (move_uploaded_file($tmpName, '../uploads/estore/' . $imageName)) {
                // Update the database with the new image name
                $db->update('ecommerce_store', ['estore_img' => $imageName], 'estore_id = :id', ['id' => $estore_id]);
            } else {
                $imageUploadErrors[] = "Failed to upload image $imageName.";
            }
        }

        // If image upload errors exist, save them to session
        if (!empty($imageUploadErrors)) {
            $_SESSION['estoreImageError'] = $imageUploadErrors;
            gotopage("estore-list.php?status=4");
            exit();
        }
    }

    // Redirect on success
    gotopage("estore-list.php?status=3");
    exit();
}
$estore_id = $_GET['estore_id']; // Get the store ID
$estore = $db->select_single("SELECT * FROM ecommerce_store WHERE estore_id = $estore_id");
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">
<head>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Update Product</title>

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
    <?php include_once("inc/admin_navbar.php") ?>
    <!-- Content -->
    <div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
  <div class="d-flex col-lg-12 justify-content-between">
      <div class="fw-bold h4 mt-auto mb-auto">Update Ecommerce</div>
      <a class="btn btn-primary" href="estore-list.php">Ecommerce List</a>
  </div>
</div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST">
                            <!-- Form Fields -->
                             <input type="hidden" name="estore_id" id="estore_id" value="<?= $estore['estore_id'] ?>">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="estore_name">Ecommerce Store Name <sup class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="estore_name" id="estore_name" value="<?= $estore['estore_name'] ?>" placeholder="Ecommerce Name" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-message">Status</label>
                                <div class="col-sm-10">
                                    <input class="form-check-input" type="radio" id="inlineRadio1" name="status" value="1" <?= $estore['status'] == '1' ? 'checked' : '' ?>> <label class="form-check-label" for="inlineRadio1">Active</label>
                                    <input class="form-check-input" type="radio" id="inlineRadio2" name="status" value="0" <?= $estore['status'] == '0' ? 'checked' : '' ?>> <label class="form-check-label" for="inlineRadio2">Not Active</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Uploaded Image</label>
                                <div class="col-sm-10">
                                    <img src="../uploads/estore/<?= htmlspecialchars($estore['estore_img']); ?>" alt="Store Image" class="img-fluid" style="max-width: 100px;">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="formFile">Upload New Image</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="image" type="file" id="formFile" onchange="displayImagePreview(this)">
                                    <small class="form-text text-danger">Image must be 600x600 pixels and in JPEG, JPG or PNG format.</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Preview</label>
                                <div class="col-sm-10" id="imagePreview"></div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" name="submit" class="btn btn-primary">Update Ecommerce Store</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / Content -->
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
