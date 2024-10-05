<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db =  new Database();
if(isset($_POST['submit'])){
    $product_id = $_POST['product_id'];

    $images = $_FILES['images'];
    $uniq = uniqid();
    foreach ($images['tmp_name'] as $index => $tmpName) {
        if ($tmpName) {
            $imageName = $uniq . '_' . $images['name'][$index];
            move_uploaded_file($tmpName, '../uploads/products/' . $imageName);
            $data  = ['product_id' => $product_id, 'url' => $imageName];
            $db->insert('product_images',$data);
        }
    }
    gotopage('product-list.php?status=5');
}

$product_id = $_GET['id'];

// $product = GetProduct($product_id);
$product = $db->select_single("SELECT * FROM product WHERE id = $product_id");
$product_images = $db->select("SELECT * FROM product_images WHERE product_id = '$product_id'");
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Add Product Images</title>

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
      <div class="fw-bold h4 mt-auto mb-auto">Add Product Images</div>
      <a class="btn btn-primary" href="product-list.php">Product List</a>
  </div>
</div>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xxl">
      <div class="card mb-4">
        <div class="card-body">
          <form enctype="multipart/form-data" method="POST">
            <div class="row mb-3">
              <div class="col-sm-10">
                <input class="form-control" name="product_id" type="text" value="<?= $product_id ?>" hidden  readonly >
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="name">Product Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="<?= $product['name']?>" readonly>
              </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Uploaded Images</label>
                <div class="col-sm-10">
                    <?php foreach($product_images as $prod_image): ?>
                        <div style="display: inline-block; position: relative; margin-right: 10px;" id="imageprev<?= $prod_image['id']; ?>">
                            <img src="../uploads/products/<?= htmlspecialchars($prod_image['url']); ?>" alt="Product Image" class="img-fluid" style="max-width: 100px;">
                            <a href="javascript:void(0);" 
                            onclick="deleteImage(<?= $prod_image['id']; ?>)" 
                            style="position: absolute; top: 0; right: 0; color: red;">
                                <i class="bx bx-trash"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="formFile">Images</label>
              <div class="col-sm-10">
                <input class="form-control" name="images[]" type="file" id="formFile" multiple >
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Preview</label>
              <div class="col-sm-10" id="imagePreview"></div>
            </div>
            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" name="submit" class="btn btn-primary">Upload Product Image</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        $.ajax({
            url: 'delete-product-image.php',
            type: 'POST',
            data: { id: imageId },
            success: function(response) {
                response = response.trim(); // Trim whitespace
                if (response === 'success') {
                    $('#imageprev' + imageId).remove(); 
                    alert('Image deleted successfully.');
                } else {
                    alert('Error deleting image: ' + response);
                }
            },
            error: function() {
                alert('Error processing request.');
            }
        });
    }
}
</script>
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