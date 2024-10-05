<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db = new Database();


?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Visitor Counter</title>

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
    <style>
        .table th , .table td{text-align: center !important;}
  </style>
</head>

<body>

<!-- Navbar -->
<?php include_once("inc/admin_navbar.php"); ?>

<div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
  <div class="d-flex col-lg-12 justify-content-between">
      <!-- <div class="fw-bold h4 m-auto">Visitors Counter</div> -->
      <div class="fw-bold h4 mt-auto mb-auto">Visitors Counter</div>
      <a class="btn btn-primary" href="website-visitors.php">Back</a>
    </div>
</div>
<div class="container-xxl flex-grow-1 container-p-y">
   <!-- <a href="website-visitors.php">Back</a> -->
    <h6>Website Visitors Datewise</h6>
               
              <div class="table-responsive p-0">
                <table class="table align-items-left mb-0">
                  <thead>
                    <tr>
                 
                    <th class=""><b>SNO</b></td> 
                
                    <td class=""><b>Date</b></td>
                    <th class=""><b>IP Address</b></td> 
                   
                    </tr>
                  </thead>
                  <?php
        $i=1;$vd=$_GET['vd'];
        $sql="SELECT * from visitors where date(update_time)='$vd' order by update_time";

        $rs=$db->select($sql);
        foreach ($rs as $row) 
                  {
                   
                   ?>
                  <tbody>
          <tr>
             <td class="text-left text-sm">
            <p class="text-xs font-weight-bold mb-0"><?php echo $i;?></p>
          </td>
           <td class="text-left text-sm">
            <p class="text-xs font-weight-bold mb-0"><?php echo $row['update_time'];?></p>
          </td>
           <td class="text-left text-sm">
            <p class="text-xs font-weight-bold mb-0"><?php echo $row['ip_address'];?></p>
          </td>
                    
          </tr>
                  <?php
                  $i=$i+1;
                   } 
                   ?>
                  </tbody>
                </table>
                <!-- Table Start -->
              </div>
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
    </body>
</html>