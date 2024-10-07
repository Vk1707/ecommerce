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

// Ensure the user is logged in
if (!isset($_SESSION['customer'])) {
    header("Location: signin.php"); // Redirect to login page if not logged in
    exit();
}

if (!isset($_SESSION['p_id_cart']) || empty($_SESSION['p_id_cart'])) {
    header("Location: cart.php"); // Redirect to cart if it is empty
    exit();
}

$payment_id = time();

// Generate order number
$order_no = generateOrderNo($db);

// Prepare payment data
$data = [
    'cust_id' => $_SESSION['customer']['cust_id'],
    'cust_name' => $_SESSION['customer']['cust_name'],
    'cust_email' => $_SESSION['customer']['cust_email'],
    'payment_date' => date('Y-m-d H:i:s'),
    'txnid' => '',
    'paid_amount' => $_POST['amount'], // Ensure this comes from a form
    'card_no' => '',
    'card_cvv' => '',
    'card_month' => '',
    'card_year' => '',
    'bank_txn_info' => '', // Not applicable for COD
    'payment_method' => 'Cash on Delivery',
    'payment_status' => 'Pending',
    'shipping_status' => 'Pending',
    'payment_id' => $payment_id,
    'order_no' => $order_no
];

// Insert payment details into the database
$db->insert("payment", $data);

// Prepare order details arrays
$arr_p_id_cart = $_SESSION['p_id_cart'];
$arr_p_name_cart = $_SESSION['p_name_cart'];
$arr_p_qty_cart = $_SESSION['p_qty_cart'];
$arr_p_unit_price_cart = $_SESSION['p_unit_price_cart'];

// Fetch product details for stock update
$products = $db->select("SELECT * FROM product");
$arr_p_id = array_column($products, 'id');
$arr_p_qty = array_column($products, 'qty');

// Prepare and insert order details
foreach ($arr_p_id_cart as $i => $product_id) {
    $order_detail_data = [
        'prod_id' => $product_id,
        'prod_name' => $arr_p_name_cart[$i],
        'qty' => $arr_p_qty_cart[$i],
        'unit_price' => $arr_p_unit_price_cart[$i],
        'total_price' => $arr_p_qty_cart[$i] * $arr_p_unit_price_cart[$i], // Calculate total price
        'payment_id' => $payment_id, // Payment ID should match the one in payment
        'order_no' => $order_no
    ];

    $db->insert("order_details", $order_detail_data);

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

// Redirect to success page
gotopage('payment-success.php?orderno='.$order_no);
exit();
?>
