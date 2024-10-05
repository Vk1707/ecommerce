<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");
$db = new Database();
$products = $db->select("SELECT * FROM product ORDER BY id DESC");
$count =1;
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Product List</title>

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
      <div class="fw-bold h4 mt-auto mb-auto">Product List</div>
      <a class="btn btn-primary" href="add-product.php">Add Product</a>
  </div>
</div>
</div>
<?php
    if(isset($_GET['status']))
    {
        if($_GET['status']==1)
        echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>Product Added Successfully with images</b></div>";
        if($_GET['status']==2)
        echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>Product Details Added Successfully <span class='text-dark'>&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp;</span> <span class='text-danger'> But Image Not Uploaded </span> </b> <br>
              </div>";
        if(isset($_SESSION['imageUploadErrors'])) {
        foreach($_SESSION['imageUploadErrors'] as $error){
            echo "<div class='container-xxl mt-3 text-center text-danger'><b class='bg-white rounded p-2'> $error</b> <br>
            </div>";
        }
        }
        if($_GET['status']==3){
            echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white p-2'>Product Updated Successfully</b></div>";
        }
        if($_GET['status']==4){
            if(isset($_SESSION['imageUpdateError'])) {
                echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>Product Details Updated Successfully <span class='text-dark'>&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp;</span> <span class='text-danger'> But Image Not Uploaded </span></b><br>
                </div>";
                foreach($_SESSION['imageUpdateError'] as $error){
                    echo "<div class='container-xxl mt-3 text-center text-danger'><b class='bg-white rounded p-2'> $error</b> <br>
                    </div>";
                }
            }
        }   
        if($_GET['status']==5)
        echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>Product Images Updated Successfully</b>
              </div>";
    }
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <table id="product-list" class="table">
        <thead>
            <tr>
                <th>Sn</th>
                <th width="20%">Product</th>
                <th>Category</th>
                <th>QTY</th>
                <th>Mrp</th>
                <th>Sale Price</th>
                <th>Views</th>
                <th>Featured</th>
                <th>URL</th>
                <th>Action</th>                                
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <?php  
            foreach($products as $product){
                $id = $product['id']; 
                if(empty($product['deleted_at'])) { ?>
            <tr>
                <td style="text-align: left;"><?= $count++ ?></td>
                <td>
                    <?= substr($product['name'],0,25)?>
                </td>
                <td>
                    <?php $category = $db->select_single("SELECT pc.name FROM product p JOIN product_category pc ON p.category_id = pc.id WHERE p.id = $id;");
                    ?>
                    <?= $category['name'] ?>
                </td>
                <td style="text-align: center;">
                    <?= $product['qty'] ?>
                </td>
                <td>
                    <?= $product['mrp'] ?>
                </td>
                <td>
                    <?= $product['sale_price'] ?>
                </td>
                <td>
                    <?= $product['views'] ?>
                </td>
                <td style="text-align: center;">
                    <?= $product['featured']==1?'Yes':'No' ?>  
                </td>
                <?php if(empty($product['product_url'])) { ?>
                <td style="text-align: center;">
                    NA
                </td>
                <?php } else{ ?>
                    <td style="text-align: center;">
                        <a href="<?= $product['product_url'] ?>" target="_blank">Url</a>
                    </td>
                <?php } ?>
                <td>
                    <a class="dropdown-item admin-actions" href="product-images.php?id=<?= $product['id'] ?>"><i class="bx bx-image me-2"></i></a>
                    <a class="dropdown-item admin-actions" href="update-product.php?id=<?= $product['id'] ?>"><i class="bx bx-edit-alt me-2"></i></a>
                    <a class="dropdown-item admin-actions" href="delete-product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');"><i class="bx bx-trash me-2"></i></a>
                </td>
            </tr>
            <?php
                } 
            } 
            ?>
        </tbody>
    </table>
</div>
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
<script>
    new DataTable('#product-list', {
        "order": false,
        });

</script>
</body>
</html>