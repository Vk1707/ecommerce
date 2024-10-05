
<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

if (isset($_SESSION['imageUploadErrors']) || isset($_SESSION['imageUpdateError'])) {
  unset($_SESSION['imageUploadErrors']);
  unset($_SESSION['imageUpdateError']);
}
// Initialize variables
$name = $short_description = $description = $category_id = $qty = $mrp = $sale_price = $estore_id = $featured = $product_url = $seo_title = $seo_keyword = $seo_description ='';
$errors = [];
$db = new Database();

if (isset($_POST['add_product'])) {
    $prod_name = $_POST['prod_name'];
    $description = $_POST['description'];
    $short_description = $_POST['short_description'];
    $category_id = $_POST['category_id'];
    $qty = $_POST['qty'];
    $mrp = $_POST['mrp']; 
    $sale_price = $_POST['sale_price'];
    $estore_id = $_POST['estore_id'];
    $featured = $_POST['featured'];
    $product_url = $_POST['product_url'];
    $seo_title = $_POST['seo_title'];
    $seo_keyword = $_POST['seo_keyword'];
    $seo_description = $_POST['seo_description'];

    // Validate MRP and discounted price
    if ($mrp <= $sale_price) {
        $errors['priceError'] = "**MRP should be greater than the discounted price";
    }

    if (empty($errors)) {
        $timestamp = date("Y-m-d H:i:s");
        $data = [
            'name' => $prod_name,
            'description' => $description,
            'short_description' => $short_description,
            'category_id' => $category_id,
            'qty' => $qty,
            'mrp' => $mrp,
            'sale_price' => $sale_price,
            'estore_id' => $estore_id,
            'featured' => $featured,
            'product_url' => $product_url,
            'seo_title' => $seo_title,
            'seo_keyword' => $seo_keyword,
            'seo_description' => $seo_description,
            'created_at' => $timestamp
        ];
        
        if ($db->insert('product', $data)) {
          $trans_id = $db->lastInsertId();
          // Handle image uploads
          $images = $_FILES['images'];
          $imageUploadErrors = [];

            foreach ($images['tmp_name'] as $index => $tmpName) {
                if ($tmpName) {
                    $imageName = uniqid() . "_" . $images['name'][$index];
                    $imageType = mime_content_type($tmpName);
                    $imageSize = getimagesize($tmpName);

                    // Validate image type
                    if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/jpg'])) {
                        $imageUploadErrors[] = "Invalid image type for $imageName. Only JPEG, JPG, and PNG are allowed.";
                        continue;
                    }

                    // Validate image dimensions (600x600)
                    if ($imageSize[0] != 600 || $imageSize[1] != 600) {
                        $imageUploadErrors[] = "Invalid image dimensions for $imageName. Image must be 600x600 pixels.";
                        continue;
                    }

                    // Upload image
                    if (move_uploaded_file($tmpName, '../uploads/products/' . $imageName)) {
                        $data = ['product_id' => $trans_id, 'url' => $imageName];
                        $db->insert('product_images', $data);
                    } else {
                        $imageUploadErrors[] = "Failed to upload image $imageName.";
                    }
                }
            }

            if (empty($imageUploadErrors)) {
                // Redirect to product list page with success status
                gotopage("product-list.php?status=1");
                exit();
            } else {
                $_SESSION['imageUploadErrors'] = $imageUploadErrors;
                gotopage("product-list.php?status=2");
                exit();
            }
        } else {
            $errors['general'] = "Sorry, there was an error adding the product. Please try again later.";
        }
    }
}

// Fetch categories and stores
$categories = $db->select("SELECT * FROM product_category");
$stores = $db->select("SELECT * FROM ecommerce_store");
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Add Product</title>

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
  <!-- Datatable Css -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css" />

  <!-- Summernote Css -->
  <link href="../admin/assets/summernote/summernote-lite.css" rel="stylesheet">


  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../admin/assets/vendor/js/helpers.js"></script>
  <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
  <!-- Summernote CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
  <!-- Summernote JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>


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
      <div class="fw-bold h4 mt-auto mb-auto">Add Product</div>
      <a class="btn btn-primary" href="product-list.php">Product List</a>
  </div>
</div>
<?php 
 if(isset($error['general'])){
    echo "<div class='container-xxl mt-3 text-center text-danger'><b class='bg-white p-2'>Sorry, there was some error, please try again</b></div>";
 }
?>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xxl">
      <div class="card mb-4">
        <div class="card-body">
          <form enctype="multipart/form-data" method="POST" id="productForm">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label class="col-sm-2 col-form-label" for="prod_name">Product Name <sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" id="prod_name" name="prod_name" value="<?= htmlspecialchars($name) ?>" placeholder="Product Name" required>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label class="col-sm-2 col-form-label" for="short_description">Short Description<sup class="text-danger">*</sup></label>
                <textarea id="short_description" class="form-control" placeholder="Short Product Description" aria-label="Short Product Description" name="short_description" required><?= htmlspecialchars($short_description) ?></textarea>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <label class="col-sm-2 col-form-label" for="description">Description<sup class="text-danger">*</sup></label>
                <textarea id="description" class="form-control" placeholder="Product Description" aria-label="Product Description" name="description" required><?= htmlspecialchars($description) ?></textarea>
            </div>
            <div class="row mb-3">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="category_id">Category <sup class="text-danger">*</sup></label>
                  <select class="form-select" name="category_id" id="category_id" required>
                    <option value="0">Select Category</option>
                    <?php foreach($categories as $category){ if($category['status']==1 ) {  ?>
                    <option value="<?= $category['id'] ?>" <?= $category_id == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']); ?></option>
                    <?php } } ?>
                  </select>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="estore_id">Ecommerce Store <sup class="text-danger">*</sup></label>
                  <select class="form-select" name="estore_id" id="estore_id" required>
                    <option value="0">Select Ecommerce</option>
                    <?php foreach($stores as $store){ if($store['status']==1 ) { ?>
                    <option value="<?= $store['estore_id'] ?>" <?= $estore_id == $store['estore_id'] ? 'selected' : '' ?>><?= htmlspecialchars($store['estore_name']); ?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-lg-4 col-md-4 col-sm-12">
                <label for="qty">QTY <sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" name="qty" id="qty" placeholder="Quantity" value="1" required>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <label for="mrp">MRP <sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" name="mrp" id="mrp" step="0.01" min="0" placeholder="MRP" value="<?= htmlspecialchars($mrp) ?>" required>
                <div class="col-12 text-danger" style="font-size: 12px;" id="priceError"><?= isset($errors['priceError']) ? $errors['priceError'] : ''; ?></div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <label for="sale_price">Sale Price <sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" name="sale_price" id="sale_price" step="0.01" min="0" placeholder="Sale Price" value="<?= htmlspecialchars($sale_price) ?>" required>
              </div>
            </div>
            <div class="row mb-4">
              <label class="col-sm-2 col-form-label">Featured</label>
              <div class="col-sm-10">
                <input class="form-check-input" type="radio" id="inlineRadio1" name="featured" value="1" <?= $featured == '1' ? 'checked' : '' ?>><label class="form-check-label" for="inlineRadio1">Yes</label> 
                &nbsp;&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="radio" id="inlineRadio2" name="featured" value="0" <?= $featured == '0' ? 'checked' : '' ?> checked> <label class="form-check-label" for="inlineRadio2">No</label>
              </div>
            </div>
            <div class="row mb-4">
              <label class="col-sm-2 col-form-label" for="product_url">Product Url <sup class="text-danger">*</sup></label>
              <div class="col-sm-10">
                <input class="form-control" name="product_url" id="product_url" type="text" placeholder="Product Url" value="<?= htmlspecialchars($product_url) ?>" required>
              </div>
            </div>
            <div class="row mb-4">
              <label class="col-sm-2 col-form-label" for="seo_title">SEO Title <sup class="text-danger">*(35 to 65)</sup></label>
              <div class="col-sm-10">
                <input class="form-control" name="seo_title" id="seo_title" type="text" placeholder="SEO Title" value="<?= htmlspecialchars($seo_title) ?>" maxlength="65" required>
              </div>
            </div>

            <div class="row mb-4">
              <label class="col-sm-2 col-form-label" for="seo_keyword">SEO Keyword <sup class="text-danger">*(120 to 160)</sup></label>
              <div class="col-sm-10">
                <input class="form-control" name="seo_keyword" id="seo_keyword" type="text" placeholder="SEO Keyword" value="<?= htmlspecialchars($seo_keyword) ?>" maxlength="160" required>
              </div>
            </div>

            <div class="row mb-4">
              <label class="col-sm-2 col-form-label" for="seo_description">SEO Description <sup class="text-danger">*(120 to 160)</sup></label>
              <div class="col-sm-10">
                <textarea class="form-control" name="seo_description" id="seo_description" placeholder="SEO Description" maxlength="160" required><?= htmlspecialchars($seo_description) ?></textarea>
              </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-2 col-form-label" for="formFile">Images <sup class="text-danger">*</sup></label>
                <div class="col-sm-10">
                    <input class="form-control" name="images[]" type="file" id="formFile" multiple onchange="displayImagePreview(this)" required>
                    <small class="form-text text-danger">Images must be 600x600 pixels and in JPEG , JPG or PNG format.</small>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
              <label class="col-sm-2 col-form-label">Preview</label>
                <div style="display: inline-block; position: relative; margin-right: 10px;" id="imagePreview">
              </div>
            </div>
            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button name="add_product" id="add_product" type="submit" class="btn btn-primary">Add Product</button>
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

    <script src="../admin/assets/js/form-basic-inputs.js"></script>
    <!-- Page JS -->
    <script src="../admin/assets/js/dashboards-analytics.js"></script>
    <!-- Summernote Text Editor -->
    <script src="../admin/assets/summernote/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#description, #short_description').summernote({
                height: 300, // Set the height of the editor
                placeholder: 'Write the product description here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });
        });
    </script>
    
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
    </body>
</html>
