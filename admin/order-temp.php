<?php
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db = new Database();
$sql = "SELECT od.order_no, p.cust_id, p.cust_name, p.cust_email, 
            GROUP_CONCAT(CONCAT('</br> Name : ', pd.name) SEPARATOR ', ') AS product_names, 
            GROUP_CONCAT(CONCAT('Qty: ', od.qty) SEPARATOR ', ') AS product_qty,
            SUM(od.qty) as total_qty, 
            SUM(od.unit_price * od.qty) as total_price,
            p.payment_status, 
            od.order_status,
            MAX(p.payment_date) as payment_date
        FROM order_details od
        JOIN payment p ON od.payment_id = p.payment_id
        JOIN product pd ON od.prod_id = pd.id
        GROUP BY od.order_no, p.cust_id, p.cust_name, p.cust_email, p.payment_status, od.order_status";

$orders = $db->select($sql);
$count = 1;

// Check if status update form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];
    $data = ['order_status' => $status];
    // Update the order status in the database
    $db->update("order_details",$data, 'od_id= :id', ['id' => $orderId]);

    // Redirect with success message
    header("Location: order-manage.php");
    exit;
}

// Check if payment status update form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_id']) && isset($_POST['payment_status'])) {
    $paymentId = $_POST['payment_id'];
    $data = ['payment_status' => "Received"];
    // Update the payment status in the database
    $db->update("payment", $data, 'payment_id= :id', ['id' => $paymentId]);

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

  <title>Order Manage</title>

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
        font-size:"10px";
    }
  </style>
</head>

<body>
    <!-- Navbar -->
    <?php include_once("inc/admin_navbar.php"); ?>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold mb-2 text-center">Order Management</h4>

        <?php if (isset($_GET['status']) && $_GET['status'] == 1) { ?>
            <div class="alert alert-success">Order status updated successfully!</div>
        <?php } ?>

        <table id="order-list" class="table table-bordered" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Product Details</th>
                    <th>Total Qty</th>
                    <th>Total Price</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td>
                            <b>Id:</b> <?= $order['cust_id']; ?><br>
                            <b>Name: </b> <?= $order['cust_name']; ?><br>
                            <b>Email: </b><?= $order['cust_email']; ?><br><br>
                        </td>
                        <td>
                            <b>Product(s): </b><?= $order['product_names']; ?><br>
                            <b>Quantities: </b><?= $order['product_qty']; ?><br><br>
                        </td>
                        <td><?= $order['total_qty'] ?></td>
                        <td><?= number_format($order['total_price'], 2) ?></td>
                        <td>
                            <span class="rounded p-1 
                                <?= ($order['payment_status'] == 'Pending') ? 'btn-danger' : 
                                    (($order['payment_status'] == 'Received') ? 'btn-success' : 'btn-secondary'); ?>">
                                <?= $order['payment_status'] ?>
                            </span>

                            <?php if ($order['payment_status'] == 'Pending') { ?>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="payment_id" value="<?= $order['payment_id'] ?>" />
                                    <button type="submit" name="payment_status" class="mt-1 p-1 rounded btn-primary">Mark Received</button>
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
                            <form action="" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['order_no'] ?>" />
                                <select name="status" class="rounded p-1">
                                    <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Processing" <?= $order['order_status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="Shipped" <?= $order['order_status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="Delivered" <?= $order['order_status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="Cancelled" <?= $order['order_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="mt-2 rounded">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<!-- Footer -->
<?php include_once("inc/admin_footer.php"); ?>

<script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
<script>
    new DataTable('#order-list', {
        "order": [[ 0 ]]
    });
</script>
</body>
</html>