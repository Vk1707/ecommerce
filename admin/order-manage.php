<?php
use PHPMailer\PHPMailer\PHPMailer;
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db = new Database();
$sql = "SELECT od.*, p.*, pd.name as prod_name, pd.id as prod_id
FROM order_details od
JOIN payment p ON od.payment_id = p.payment_id
JOIN product pd ON od.prod_id = pd.id ORDER BY od.od_id DESC";

$orders = $db->select($sql);
$count = 1;

// Check if status update form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];
    $remarkDate = date('Y-m-d H:i:s');

    $data = [
        'order_status' => $status,
        'remark' => $remark,
        'remark_date' => $remarkDate
    ];

    // Update the order status in the database
    $db->update("order_details", $data, 'od_id= :id', ['id' => $orderId]);

    // Redirect with success message
    header("Location: order-manage.php");
    exit;
}


// Check if mark received form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_id']) && isset($_POST['payment_status'])) {
    $paymentId = $_POST['payment_id'];
    $status = $_POST['order_status'];
    $data = [
        'payment_status' => 'Received' // Set the new payment status
    ];

    // Update the payment status in the database
    $db->update("payment", $data, 'payment_id= :id', ['id' => $paymentId]);

    if(($status == "Pending")){
        $orderData = [
            'order_status' => 'Processing' // Set the new order status
        ];
        $db->update("order_details", $orderData, 'payment_id = :payment_id', ['payment_id' => $paymentId]);
    }
    // Redirect with success message
    header("Location: order-manage.php");
    exit;
}   
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Order Management</title>
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
        table tr td {
            font-size: 12px;
        }
    </style>
</head>


<body>
    <!-- Navbar -->
    <?php include_once("inc/admin_navbar.php"); ?>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-2 text-center bg-light rounded">Order Management</h4>

        <table id="order-list" class="table table-bordered">
            <thead class="bg-secondary fw-wold">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Product Detail</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Order Date</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                    <?php $total = $order['unit_price'] * $order['qty']; ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td>
                            <b>Id:</b> <?= $order['cust_id']; ?><br>
                            <b>Name: </b> <?= $order['cust_name']; ?><br>
                            <b>Email: </b><?= $order['cust_email']; ?><br><br>
                        </td>
                        <td>
                            <b>Order No : </b> <?= $order['order_no']; ?><br>
                            <b>P Id : </b> <?= $order['prod_id']; ?><br>
                            <b>Prod Name: </b><?= $order['prod_name']; ?><br>
                            <b>QTY: </b><?= $order['qty']; ?><br><br>
                        </td>
                        <td><?= number_format($order['unit_price'], 2) ?></td>
                        <td><?= number_format($total, 2) ?></td>
                        <td><?= $order['payment_method'] ?></td>
                        <td>
                            <span class="rounded p-1 
                                <?= ($order['payment_status'] == 'Pending') ? 'btn-danger' : 
                                    (($order['payment_status'] == 'Received') ? 'btn-success' : 'btn-secondary'); ?>">
                                <?= $order['payment_status'] ?>
                            </span>

                            <?php if ($order['payment_status'] == 'Pending') { ?>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="payment_id" value="<?= $order['payment_id'] ?>" />
                                <input type="hidden" name="order_status" value="<?= $order['order_status'] ?>" />
                                <button type="submit" name="payment_status" class="mt-1 rounded btn-success "><small>Mark Received</small></button>
                            </form>
                            <?php } ?>
                        </td>
                        <td>
                            <span class="rounded p-1 
                                <?= ($order['order_status'] == 'Pending') ? 'btn-danger' : 
                                    (($order['order_status'] == 'Processing') ? 'btn-warning' : 
                                    (($order['order_status'] == 'Shipped') ? 'btn-info' : 
                                    (($order['order_status'] == 'Delivered') ? 'btn-success' : 
                                    (($order['order_status'] == 'Cancelled') ? 'btn-dark' : 'btn-secondary')))); ?>">
                                <?= $order['order_status'] ?>
                            </span>
                        </td>
                        <td><?= date("d M Y", strtotime($order['payment_date'])) ?></td>
                        <td>
                            <b>Remark : </b> <?= $order['remark'] ?><br>
                            <b>Date : </b> <?= !empty($order['remark_date'])?date('d-m-Y H:i:s', strtotime($order['remark_date'])):"" ?><br>
                        </td>
                        <td>
                            <button type="button" class="mt-2 rounded btn-primary p-1"
                                data-bs-toggle="modal"
                                data-bs-target="#updateStatusModal"
                                onclick="setModalData(<?= $order['od_id'] ?>, '<?= htmlspecialchars($order['order_status']) ?>', '<?= htmlspecialchars($order['remark']) ?>', '<?= $order['remark_date'] ?>')">
                                Update
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStatusForm" method="POST">
                        <input type="hidden" name="order_id" id="modalOrderId" />
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Status</label>
                            <select name="status" id="statusSelect" class="form-select">
                                <option value="Pending" <?= $order['order_status']=="Pending"?"Selected":""?>>Pending</option>
                                <option value="Processing" <?= $order['order_status']=="Processing"?"Selected":""?>>Processing</option>
                                <option value="Shipped" <?= $order['order_status']=="Shipped"?"Selected":""?>>Shipped</option>
                                <option value="Delivered" <?= $order['order_status']=="Delivered"?"Selected":""?>>Delivered</option>
                                <option value="Cancelled" <?= $order['order_status']=="Cancelled"?"Selected":""?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="remarkInput" class="form-label">Remark</label>
                            <textarea name="remark" id="remarkInput" class="form-control" rows="3"><?= $order['remark'] ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once("inc/admin_footer.php"); ?>
    <script src="../admin/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../admin/assets/vendor/js/bootstrap.js"></script>
    <script src="../admin/assets/vendor/libs/popper/popper.js"></script>
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

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        new DataTable('#order-list', {
            "order": [[0]]
        });

        function setModalData(orderId, currentStatus, remark) {
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('remarkInput').value = remark;
        }

    </script>
</body>
</html>
