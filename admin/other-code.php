<?php
use PHPMailer\PHPMailer\PHPMailer;
include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

$db = new Database();
$sql = "SELECT 
    od.order_no,
    GROUP_CONCAT(DISTINCT pd.name SEPARATOR '<br>') AS product_names,
    GROUP_CONCAT(od.qty SEPARATOR '<br>') AS quantities,
    GROUP_CONCAT(od.unit_price SEPARATOR '<br>') AS unit_prices,
    SUM(od.qty * od.unit_price) AS total_amount,
    GROUP_CONCAT(p.payment_status SEPARATOR '<br>') AS payment_statuses,
    p.payment_id,
    p.cust_id,
    c.cust_name AS cust_name,
    c.cust_email AS cust_email,
    GROUP_CONCAT(od.order_status SEPARATOR '<br>') AS order_statuses,
    p.payment_date
FROM 
    order_details od
JOIN 
    payment p ON od.payment_id = p.payment_id
JOIN 
    product pd ON od.prod_id = pd.id
JOIN 
    customer c ON p.cust_id = c.cust_id
GROUP BY 
    od.order_no
ORDER BY 
    od.od_id DESC;
";

$orders = $db->select($sql);

// print_r($orders);
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
    $db->update("order_details", $data, 'payment_id= :id', ['id' => $orderId]);

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
    <link rel="icon" type="image/x-icon" href="../admin/assets/img/icons/brands/slack.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../admin/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../admin/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../admin/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="../admin/assets/css/demo.css" />
    <link rel="stylesheet" href="../admin/assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <script src="../admin/assets/vendor/js/helpers.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
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
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Order Date</th>
                    <th>Remarks</th>
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
            <b>Order No: </b> <?= $order['order_no']; ?><br>
            <b>Products:</b><br> <?= $order['product_names']; ?><br>
            <b>Qty:</b><br> <?= $order['quantities']; ?><br>
            <b>Unit Price:</b><br> <?= $order['unit_prices']; ?><br>
        </td>
        <td><?= $order['quantities'] ?></td>
        <td><?= $order['unit_prices'] ?></td>
        <td><?= number_format($order['total_amount'], 2) ?></td>
        <td>
            <span class="rounded p-1 <?= ($order['payment_statuses'] == 'Pending') ? 'btn-danger' : 
                                (($order['payment_statuses'] == 'Received') ? 'btn-success' : 'btn-secondary'); ?>">
                <?= $order['payment_statuses'] ?>
            </span>
        </td>
        <td>
            <span class="rounded p-1 <?= ($order['order_statuses'] == 'Pending') ? 'btn-danger' : 
                                (($order['order_statuses'] == 'Processing') ? 'btn-warning' : 
                                (($order['order_statuses'] == 'Shipped') ? 'btn-info' : 
                                (($order['order_statuses'] == 'Delivered') ? 'btn-success' : 
                                (($order['order_statuses'] == 'Cancelled') ? 'btn-dark' : 'btn-secondary')))); ?>">
                <?= $order['order_statuses'] ?>
            </span>
        </td>
        <td><?= date("d M Y", strtotime($order['payment_date'])) ?></td>
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
















<?php
ob_start();

include("./inc/dbclass.php");
$db = new Database();

    function generateOrderNo($db) {
        // Step 1: Get the last order number
        $result = $db->select("SELECT MAX(order_no) AS last_order FROM order_details");
        $last_order_no = $result[0]['last_order'];

        if ($last_order_no) {
            // Extract the numeric part from the last order number
            $last_numeric = (int)substr($last_order_no, 3); // Removes 'HTS' and converts the rest to an integer
            
            $new_numeric = $last_numeric + 1;

            $new_order_no = 'HTS' . str_pad($new_numeric, 4, '0', STR_PAD_LEFT);
        } else {
            $new_order_no = 'HTS0001';
        }

        return $new_order_no;
    }

    // Handling BankDeposit payment method
    if (isset($_POST['payment_method']) && $_POST['payment_method'] == "BankDeposit") {
        $payment_date = date('Y-m-d H:i:s');
        $payment_id = time();
        $order_no = generateOrderNo($db);

        $data = [
            'cust_id' => $_SESSION['customer']['cust_id'],
            'cust_name' => $_SESSION['customer']['cust_name'],
            'cust_email' => $_SESSION['customer']['cust_email'],
            'payment_date' => $payment_date,
            'txnid' => '',
            'paid_amount' => $_POST['amount'],
            'card_no' => '',
            'card_cvv' => '',
            'card_month' => '',
            'card_year' => '',
            'bank_txn_info' => $_POST['transaction_info'],
            'payment_method' => 'Bank Deposit',
            'payment_status' => 'Pending',
            'shipping_status' => 'Pending',
            'payment_id' => $payment_id,
            'order_no' => $order_no
        ];

        $db->insert("payment", $data);

        // Initialize arrays using foreach
        $arr_p_id_cart = [];
        $arr_p_name_cart = [];
        $arr_p_qty_cart = [];
        $arr_p_unit_price_cart = [];

        foreach ($_SESSION['p_id_cart'] as $value) {
            $arr_p_id_cart[] = $value;
        }

        foreach ($_SESSION['p_name_cart'] as $value) {
            $arr_p_name_cart[] = $value;
        }

        foreach ($_SESSION['p_qty_cart'] as $value) {
            $arr_p_qty_cart[] = $value;
        }

        foreach ($_SESSION['p_unit_price_cart'] as $value) {
            $arr_p_unit_price_cart[] = $value;
        }

        // Fetch product details
        $products = $db->select("SELECT * FROM product");
        $arr_p_id = [];
        $arr_p_qty = [];

        foreach ($products as $row) {
            $arr_p_id[] = $row['id'];
            $arr_p_qty[] = $row['qty'];
        }

        // Prepare and insert order data
        foreach ($arr_p_id_cart as $i => $product_id) {
            $data = [
                'prod_id' => $product_id,
                'prod_name' => $arr_p_name_cart[$i],
                'qty' => $arr_p_qty_cart[$i],
                'unit_price' => $arr_p_unit_price_cart[$i],
                'total_price' => $arr_p_unit_price_cart[$i],
                'payment_id' => $payment_id,
                'order_no' => $order_no
            ];

            $db->insert("order_details", $data);

            // Update stock
            $key = array_search($product_id, $arr_p_id);
            if ($key !== false) {
                $current_qty = $arr_p_qty[$key];
                $final_quantity = $current_qty - $arr_p_qty_cart[$i];
                $statement = $db->prepare("UPDATE product SET qty=? WHERE id=?");
                $statement->execute([$final_quantity, $product_id]);
            }
        }

        // Clear session cart data
        unset($_SESSION['p_id_cart'], $_SESSION['p_qty_cart'], $_SESSION['p_unit_price_cart'], $_SESSION['p_name_cart'], $_SESSION['p_f_photo_cart']);

        gotopage('payment-success.php?orderno=' . $order_no);
    }
    // Handling COD payment method
    elseif (isset($_POST['payment_method']) && $_POST['payment_method'] == "COD") {
        $payment_date = date('Y-m-d H:i:s');
        $payment_id = time();
        $order_no = generateOrderNo($db);

        $data = [
            'cust_id' => $_SESSION['customer']['cust_id'],
            'cust_name' => $_SESSION['customer']['cust_name'],
            'cust_email' => $_SESSION['customer']['cust_email'],
            'payment_date' => $payment_date,
            'txnid' => '',
            'paid_amount' => $_POST['amount'],
            'card_no' => '',
            'card_cvv' => '',
            'card_month' => '',
            'card_year' => '',
            'bank_txn_info' => '',
            'payment_method' => 'Cash on Delivery',
            'payment_status' => 'Pending',
            'shipping_status' => 'Pending',
            'payment_id' => $payment_id,
            'order_no' => $order_no
        ];

        // $db->insert("payment", $data);
        $insert_result = $db->insert("payment", $data);
        if (!$insert_result) {
            error_log("Insert failed: " . json_encode($data));
        }

        // Initialize arrays using foreach
        $arr_p_id_cart = [];
        $arr_p_name_cart = [];
        $arr_p_qty_cart = [];
        $arr_p_unit_price_cart = [];

        foreach ($_SESSION['p_id_cart'] as $value) {
            $arr_p_id_cart[] = $value;
        }

        foreach ($_SESSION['p_name_cart'] as $value) {
            $arr_p_name_cart[] = $value;
        }

        foreach ($_SESSION['p_qty_cart'] as $value) {
            $arr_p_qty_cart[] = $value;
        }

        foreach ($_SESSION['p_unit_price_cart'] as $value) {
            $arr_p_unit_price_cart[] = $value;
        }

        // Fetch product details
        $products = $db->select("SELECT * FROM product");
        $arr_p_id = [];
        $arr_p_qty = [];

        foreach ($products as $row) {
            $arr_p_id[] = $row['id'];
            $arr_p_qty[] = $row['qty'];
        }

        // Prepare and insert order data
        foreach ($arr_p_id_cart as $i => $product_id) {
            $data = [
                'prod_id' => $product_id,
                'prod_name' => $arr_p_name_cart[$i],
                'qty' => $arr_p_qty_cart[$i],
                'unit_price' => $arr_p_unit_price_cart[$i],
                'total_price' => $arr_p_unit_price_cart[$i],
                'payment_id' => $payment_id,
                'order_no' => $order_no
            ];

            $db->insert("order_details", $data);

            // Update stock
            $key = array_search($product_id, $arr_p_id);
            if ($key !== false) {
                $current_qty = $arr_p_qty[$key];
                $final_quantity = $current_qty - $arr_p_qty_cart[$i];
                $statement = $db->prepare("UPDATE product SET qty=? WHERE id=?");
                $statement->execute([$final_quantity, $product_id]);
            }
        }

        // Clear session cart data
        unset($_SESSION['p_id_cart'], $_SESSION['p_qty_cart'], $_SESSION['p_unit_price_cart'], $_SESSION['p_name_cart'], $_SESSION['p_f_photo_cart']);

        gotopage('payment-success.php?orderno=' . $order_no);
    }
?>
