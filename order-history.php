<?php
// Start session
session_start();
include_once("./inc/dbclass.php");

// Initialize database connection
$db = new Database();

// Check if the user is logged in
if (!isset($_SESSION['customer']['cust_id'])) {
    header("Location: signin.php");
    exit();
}

// Get the logged-in customer ID
$customer_id = $_SESSION['customer']['cust_id'];

// Fetch orders based on the customer ID
$sql = "SELECT od.*, p.*, MIN(pi.url) as purl 
            FROM order_details od
            JOIN payment p ON od.payment_id = p.payment_id
            JOIN product_images pi ON od.prod_id = pi.product_id
            WHERE p.cust_id = $customer_id
            GROUP BY od.od_id";

$orders = $db->select($sql);
$count = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Your Orders</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="shop/assets/img/shopify.svg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link href="shop/assets/css/style.css" rel="stylesheet">

    <style>
        th, td {
            white-space: nowrap; /* Prevent wrapping of text in table headers and cells */
        }

        /* Ensure text is readable on smaller screens */
        @media (max-width: 768px) {
            th, td {
                font-size: 14px; /* Adjust table font size for mobile */
            }
            h2 {
                font-size: 20px;
            }
            .btn {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

<?php include_once("./inc/shop_navbar.php"); ?>
<?php include_once("customer-sidebar.php"); ?>


<div class="container-fluid mt-2">
    <h2>Order History</h2>
    
    <?php if (count($orders) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>S.No.</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Order No.</th>
                        <th>QTY</th>
                        <th>Unit Price</th>
                        <th>Payment Date</th>
                        <th>Txn ID</th>
                        <th>Paid Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td style="font-size: 12px;"><img src="uploads/products/<?= $order['purl']; ?>" alt="" width="50px" class="rounded"></td>
                            <td style="font-size: 12px;"><?= substr($order['prod_name'],0,35) ?></td>
                            <td>#<?= $order['order_no']; ?></td>
                            <td align="center"><?= $order['qty']; ?></td>
                            <td>₹ <?= $order['unit_price']; ?></td>
                            <td><?= $order['payment_date']; ?></td>
                            <td><?= $order['txnid']; ?></td>
                            <td><b>₹ <?= $order['paid_amount']; ?></b></td>
                            <td><span class="badge badge-<?= $order['payment_status'] == 'Received' ? 'success' : 'danger'; ?>"><?= $order['payment_status']; ?></span></td>
                            <td>
                            <span class="badge <?= ($order['order_status'] == 'Pending') ? 'badge-danger' : 
                                (($order['order_status'] == 'Processing') ? 'badge-warning' :
                                (($order['order_status'] == 'Shipped') ? 'badge-info' :
                                (($order['order_status'] == 'Delivered') ? 'badge-success' :
                                (($order['order_status'] == 'Cancelled') ? 'badge-dark' : 'badge-secondary')))); ?>">
                                <?= $order['order_status'] ?>
                            </span>
                            </td>
                            <td><?= $order['payment_method'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            You have no orders.
        </div>
    <?php endif; ?>
    
    <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
</div>

<?php include_once("./inc/shop_footer.php"); ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="shop/assets/js/main.js"></script>

</body>

</html>
