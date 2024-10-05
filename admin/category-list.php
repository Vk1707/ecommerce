<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db = new Database();
$sql_category = "SELECT * FROM product_category ORDER BY id DESC";
$categories = $db->select($sql_category);
$count = 1;

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Category List</title>

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

<div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
  <div class="d-flex col-lg-12 justify-content-between">
      <div class="fw-bold h4 mt-auto mb-auto">Category List</div>
        <a class="btn btn-primary" href="add-category.php">Add Category </a>
    </div>
</div>
<?php
    if(isset($_GET['status']))
    {
        if($_GET['status']==1)
        echo "<div class='container-xxl mt-3 text-center text-success'><b>New Category Added Successfully</b></div>";
        if($_GET['status']==2)
        echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>Category Details Added Successfully <span class='text-dark'>&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp;</span> <span class='text-danger'> But Image Not Uploaded </span> </b> <br>
              </div>";
        if(isset($_SESSION['catImgUploadError'])) {
            foreach($_SESSION['catImgUploadError'] as $error){
                echo "<div class='container-xxl mt-3 text-center text-danger'><b class='bg-white rounded p-2'> $error</b> <br>
                </div>";
            }
        }
        if($_GET['status']==3)
        echo "<div class='container-xxl mt-3 text-center text-success'><b>Category Updated Successfully</b></div>";
        if($_GET['status']==4)
        echo "<div class='container-xxl mt-3 text-center text-success'><b class='bg-white rounded p-2'>Category Details Updated Successfully <span class='text-dark'>&nbsp;&nbsp;&nbsp;|| &nbsp;&nbsp;&nbsp;</span> <span class='text-danger'> But Image Not Uploaded </span> </b> <br>
              </div>";
        if(isset($_SESSION['catImgUpdateError'])) {
            foreach($_SESSION['catImgUpdateError'] as $error){
                echo "<div class='container-xxl mt-3 text-center text-danger'><b class='bg-white rounded p-2'> $error</b> <br>
                </div>";
            }
        }
        if($_GET['status']==5)
        echo "<div class='container-xxl mt-3 text-center text-danger'><b>Category Deleted Successfully</b></div>";
    }
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <table id="category-list" class="table">
        <thead>
            <tr>
                <th style="text-align: left;">Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Image</th>
                <th>Edit or Delete</th>                                
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <?php
            foreach($categories as $category){ 
                if(empty($category['deleted_at'])) { ?>
            <tr>
                <td style="text-align: left;"><?= $count++ ?></td>
                <td>
                    <?= $category['name']; ?>
                </td>
                <td><?= substr($category['description'], 0, 50)."..." ?></td>
                <td><?= $category['status'] ?></td>
                <td>
                    <img src="../uploads/category_img/<?= $category['image'] ?>" alt="Avatar" class="" width="80px" height="80px" style="border-radius: 50%;" />
                </td>
                <td>
                    <div class="dropdown">
                        <a class="dropdown-item" href="update-category.php?id=<?= $category['id'] ?>"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                        <a class="dropdown-item" href="delete-category.php?id=<?= $category['id'] ?>" onclick="return confirm('Are you sure you want to delete this product image?');"><i class="bx bx-trash me-2"></i> Delete</a>
                    </div>
                </td>
            </tr>
            <?php }  } ?>
        </tbody>
    </table>
</div>
<script>
    new DataTable('#category-list', {
        "order": false
    });
</script>
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