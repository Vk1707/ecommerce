<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");
$db = new Database();

$oc_sql = "SELECT p.id, p.name, pc.name AS category_name, pi.url AS url, COUNT(oc.prod_id) AS click_count
FROM product p
LEFT JOIN onsite_clicks oc ON p.id = oc.prod_id
LEFT JOIN product_category pc ON p.category_id = pc.id
LEFT JOIN (
    SELECT product_id, MIN(url) AS url
    FROM product_images
    GROUP BY product_id
) pi ON p.id = pi.product_id
GROUP BY p.id, p.name, pc.name, pi.url
ORDER BY click_count DESC
LIMIT 10
";

$oc_products  = $db->select($oc_sql);
$oc_total  = $db->select_single("SELECT COUNT(*) as total FROM onsite_clicks");

$ec_sql = "SELECT p.id, p.name, pc.name AS category_name, pi.url AS url, COUNT(ec.prod_id) AS click_count
FROM product p
LEFT JOIN external_clicks ec ON p.id = ec.prod_id
LEFT JOIN product_category pc ON p.category_id = pc.id
LEFT JOIN (
    SELECT product_id, MIN(url) AS url
    FROM product_images
    GROUP BY product_id
) pi ON p.id = pi.product_id
GROUP BY p.id, p.name, pc.name, pi.url
ORDER BY click_count DESC
LIMIT 10
";

$ec_products  = $db->select($ec_sql);
$ec_total  = $db->select_single("SELECT COUNT(*) as total FROM external_clicks");

?>      
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Admin Dashboard</title>

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

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- <div class="col-12 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Congratulations Vivek 🎉</h5>
                          <p class="mb-4">
                            You have done <span class="fw-bold">72%</span> more sales today. Check your new badge in
                            your profile.
                          </p>

                          <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="./assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->
                <div class="col-12 order-1">
                  <div class="row">
                    <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <a href="product-list.php">
                            <div class="avatar flex-shrink-0" style='color:#114be2;width:38px;height:38px'>
                            <i class='bx bx-md bxl-product-hunt'></i>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Products</span>
                            <h3 class="card-title mb-2">
                              <?php 
                              $sql = "SELECT COUNT(*) as total FROM product";
                              // echo $sql;
                              $total = $db->select_single($sql); ?>
                              <?= $total['total'] ?>
                            </h3>
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="website-visitors.php">
                            <div class="avatar flex-shrink-0" style='color:#114be2;width:38px;height:38px'>
                            <i class='bx bx-md bx-body'></i>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Visitor</span>
                            <h3 class="card-title mb-2">
                              <?php $total = $db->select_single("SELECT COUNT(*) as total FROM visitors"); ?>
                              <?= $total['total'] ?>
                            </h3>
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="manage-user.php">
                            <div class="avatar flex-shrink-0">
                            <i class='bx bx-md bxs-user-rectangle'></i>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Users</span>
                            <h3 class="card-title mb-2">
                              <?php $total = $db->select_single("SELECT COUNT(*) as total FROM user"); ?>
                              <?= $total['total'] ?>
                            </h3>
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="estore-list.php">
                            <div class="avatar flex-shrink-0">
                            <i class='bx bx-md bx-store' style='color:#18d7c3' ></i>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">estores</span>
                            <h3 class="card-title mb-2">
                              <?php $total = $db->select_single("SELECT COUNT(*) as total FROM ecommerce_store"); ?>
                              <?= $total['total'] ?>
                            </h3>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 order-3 order-md-2">
                  <div class="row">
                    <!-- Order Statistics -->
                    <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
                      <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                          <div class="card-title m-auto">
                            <h5 class="m-0 me-2">Onsite Clicks</h5>
                            <a href="onsite-clicks.php"><small class="text-muted"><?= $oc_total['total'] ?> Total Clicks</small></a>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                              <span class="mt-2">Top 10 Onsite Clicked Products</span>
                            </div>
                            <!-- <div id="orderStatisticsChart"></div> -->
                          </div>
                          <ul class="p-0 m-0">
                            <?php foreach($oc_products as $oc_prod) { ?>
                            <li class="d-flex mb-4 pb-1">
                              <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                  <img src="../uploads/products/<?= $oc_prod['url'] ?>" class="rounded" alt="">
                                </span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                  <h6 class="mb-0"><?= substr($oc_prod['name'],0,40) ?></h6>
                                  <small class="text-muted"><?=$oc_prod['category_name'] ?></small>
                                </div>
                                <div class="user-progress">
                                  <small class="fw-semibold"><?= $oc_prod['click_count'] . " Clicks" ?></small>
                                </div>
                              </div>
                            </li>
                            <?php } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <!-- Transactions -->
                    <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
                      <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                          <div class="card-title m-auto">
                            <h5 class="m-0 me-2">External Clicks</h5>
                            <a href="external-clicks.php"><small class="text-muted"><?= $ec_total['total'] ?> Total Clicks</small></a>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                              <span class="mt-2">Top 10 External Clicked Products</span>
                            </div>
                            <!-- <div id="orderStatisticsChart"></div> -->
                          </div>
                          <ul class="p-0 m-0">
                            <?php foreach($ec_products as $prod) { ?>
                            <li class="d-flex mb-4 pb-1">
                              <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                  <img src="../uploads/products/<?= $prod['url'] ?>" class="rounded" alt="">
                                </span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                  <h6 class="mb-0"><?= substr($prod['name'],0,40) ?></h6>
                                  <small class="text-muted"><?=$prod['category_name'] ?></small>
                                </div>
                                <div class="user-progress">
                                  <small class="fw-semibold"><?= $prod['click_count'] . " Clicks" ?></small>
                                </div>
                              </div>
                            </li>
                            <?php } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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