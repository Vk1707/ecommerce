<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

if (isset($_SESSION['imageUploadErrors']) || isset($_SESSION['imageUpdateError'])) {
    unset($_SESSION['imageUploadErrors']);
    unset($_SESSION['imageUpdateError']);
}
$errors = [];
$imageUploadErrors = []; 
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];  
    $name = $_POST['name'];
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
    $product_image = $db->select_single("SELECT url FROM product_images WHERE product_id = :id", ['id' => $id]);

    // Validate MRP and discounted price
    if ($mrp <= $sale_price) {
        $errors['priceError'] = "**MRP should be greater than the discounted price";
    }

    if(empty($errors)){
        $timestamp = date("Y-m-d H:i:s");
        $data = ['name' => $name, 'short_description'=> $short_description, 'description' => $description, 'category_id' => $category_id, 'qty' => $qty, 'mrp' => $mrp, 'sale_price' => $sale_price, 'estore_id' => $estore_id, 'featured' =>$featured, 'product_url' => $product_url,'seo_title' => $seo_title,'seo_keyword' => $seo_keyword,
        'seo_description' => $seo_description];
        $result = $db->update('product',$data,'id = :id', ['id' => $id]);
    
        if($result){
            $images = $_FILES['images'];
            foreach ($images['tmp_name'] as $index => $tmpName) {
                if ($tmpName) {
                    $imageName = $images['name'][$index];
                    $imageType = mime_content_type($tmpName);
                    $imageSize = getimagesize($tmpName);
                    
                    // Validate image type
                    if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/jpg'])) {
                        $imageUploadErrors[] = "Invalid image type for $imageName. Only JPEG, JPG and PNG are allowed.";
                        continue;
                    }
                    
                    // Validate image dimensions (600x600)
                    if ($imageSize[0] != 600 || $imageSize[1] != 600) {
                        $imageUploadErrors[] = "Invalid image dimensions for $imageName. Image must be 600x600 pixels.";
                        continue;
                    }
                }
            }
            if(empty($imageUploadErrors)){
                if (!empty($_FILES['images']['name'][0])) {
                    foreach ($images['tmp_name'] as $index => $tmpName) {
                        if ($tmpName && empty($errors)) {
                            $imageName = uniqid(). $images['name'][$index];
                            if (move_uploaded_file($tmpName, '../uploads/products/' . $imageName)) {
                                if(empty($product_image)){
                                    $data = ['product_id' => $id, 'url' => $imageName];
                                    $db->insert('product_images',$data);
                                } else if (!empty($product_image)){
                                    $data = ['product_id' => $id, 'url' => $imageName];
                                    $ql = $db->insert('product_images',$data);
                                }
                            } else {
                                $errors[] = "Failed to upload image $imageName.";
                            }
                        }
                    }
                }
                gotopage('product-list.php?status=3');
            } else {
                $_SESSION['imageUpdateError'] = $imageUploadErrors;
                gotopage('product-list.php?status=4');
            } 
        }
    }
}

$id = $_GET['id'];
$prod_sql = "SELECT * FROM product WHERE id = :id";
$product = $db->select_single($prod_sql, ['id' => $id]);
$product_images = $db->select("SELECT * FROM product_images WHERE product_id = :id", ['id' => $id]);
$thumbnail = $db->select("SELECT * FROM product_images WHERE product_id = :id LIMIT 2", ['id' => $id]);
$categories = $db->select("SELECT * FROM product_category");
$stores = $db->select("SELECT * FROM ecommerce_store");
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

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
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>

  <!-- Core CSS -->
  <link rel="stylesheet" href="../admin/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../admin/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../admin/assets/css/demo.css" />
  <link rel="stylesheet" href="../admin/assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
      <div class="fw-bold h4 mt-auto mb-auto">Update Product</div>
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
                    <form enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label  for="name">Product Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" />
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label class="col-sm-2 col-form-label" for="short_description">Short Description<sup class="text-danger">*</sup></label>
                            <textarea id="short_description" class="form-control" placeholder="Short Product Description" aria-label="Short Product Description" name="short_description" required><?= htmlspecialchars($product['short_description']) ?></textarea>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label  for="description">Description <sup class="text-danger">*</sup></label>
                            <textarea id="description" class="form-control" name="description"><?= htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        <div class="row mb-3">
                          <div class="col-lg-6 col-md-6 col-sm-12">
                            <label  for="category_id">Category <sup class="text-danger">*</sup></label>
                            <select class="form-select" name="category_id" id="category_id">
                                <option value="0">Select Category</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id']); ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-12">
                            <label  for="estore_id">Ecommerce Store <sup class="text-danger">*</sup></label>
                            <select class="form-select" name="estore_id" id="estore_id">
                                <option value="0">Select Ecommerce</option>
                                <?php foreach($stores as $store): ?>
                                    <option value="<?= htmlspecialchars($store['estore_id']); ?>" <?= $store['estore_id'] == $product['estore_id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($store['estore_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label  for="qty">QTY <sup class="text-danger">*</sup></label>
                                <input type="number" class="form-control" name="qty" id="qty" value="<?= htmlspecialchars($product['qty']); ?>" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label  for="mrp">MRP<sup class="text-danger">*</sup></label>
                                <input type="number" class="form-control" name="mrp" id="mrp" value="<?= htmlspecialchars($product['mrp']); ?>" />
                                <div class="col-12 text-danger" style="font-size: 12px;" id="priceError"><?= isset($errors['priceError']) ? $errors['priceError'] : ''; ?></div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label  for="discount">Sale Price <sup class="text-danger">*</sup></label>
                                <input type="number" class="form-control" name="sale_price" id="discount" value="<?= htmlspecialchars($product['sale_price']); ?>" />
                            </div>
                        </div>    
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Featured</label>
                            <div class="col-sm-10">
                                <input class="form-check-input" type="radio" name="featured" value="1" <?= $product['featured'] == '1' ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="radio" name="featured" value="0" <?= $product['featured'] == '0' ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="product_url">Product URL <sup class="text-danger">*</sup></label>
                            <div class="col-sm-10">
                                <input class="form-control" name="product_url" type="text" id="product_url" required placeholder="Product URL" value="<?= htmlspecialchars($product['product_url']); ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="seo_title">SEO Title <sup class="text-danger">*(35 to 65)</sup></label>
                        <div class="col-sm-10">
                            <input class="form-control" name="seo_title" id="seo_title" type="text" placeholder="SEO Title" value="<?= htmlspecialchars($product['seo_title']) ?>" maxlength="65" required>
                        </div>
                        </div>

                        <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="seo_keyword">SEO Keyword <sup class="text-danger">*(120 to 160)</sup></label>
                        <div class="col-sm-10">
                            <input class="form-control" name="seo_keyword" id="seo_keyword" type="text" placeholder="SEO Keyword" value="<?= htmlspecialchars($product['seo_keyword']) ?>" maxlength="160" required>
                        </div>
                        </div>

                        <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="seo_description">SEO Description <sup class="text-danger">*(120 to 160)</sup></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="seo_description" id="seo_description" placeholder="SEO Description" maxlength="160" required><?= htmlspecialchars($product['seo_description']) ?></textarea>
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
                                <br>
                                <small class="form-text text-muted">Delete Image If You Want To Remove And Choose New Images</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="formFile">New Images</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="images[]" type="file" id="formFile" multiple>
                                <small class="form-text text-danger">Images must be 600x600 pixels and in JPEG , JPG or PNG format.</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label">Preview</label>
                          <div class="col-sm-10" id="imagePreview"></div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update Product</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                    ['fontsize', ['fontsize']], 
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ],
                fontsize: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '36', '48', '64', '82', '100']
            });
        });

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
    </body>
</html>