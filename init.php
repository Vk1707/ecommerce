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


if (!isset($_REQUEST['msg'])) {
    if (isset($_POST['payment_method']) && $_POST['payment_method'] == "BankDeposit" && empty($_POST['transaction_info'])) {
        gotopage("checkout.php");
    } else {
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

        gotopage('payment-success.php?orderno='.$order_no);
    }
}
elseif (isset($_POST['payment_method']) && $_POST['payment_method'] == "PayPal") { 
    $payment_date = date('Y-m-d H:i:s');
    $payment_id = time();
    $order_no = generateOrderNo($db);

    // Prepare data for payment
    $data = [
        'cust_id' => $_SESSION['customer']['cust_id'],
        'cust_name' => $_SESSION['customer']['cust_name'],
        'cust_email' => $_SESSION['customer']['cust_email'],
        'payment_date' => $payment_date,
        'txnid' => '', // No transaction ID for COD
        'paid_amount' => $_POST['amount'],
        'card_no' => '',
        'card_cvv' => '',
        'card_month' => '',
        'card_year' => '',
        'bank_txn_info' => '', // No transaction info for COD
        'payment_method' => 'Cash on Delivery',
        'payment_status' => 'Pending',
        'shipping_status' => 'Pending',
        'payment_id' => $payment_id,
        'order_no' => $order_no
    ];

    // Insert payment data into the database
    $db->insert("payment", $data);   

    // Initialize arrays using foreach for cart data
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
            'total_price' => $arr_p_unit_price_cart[$i], // You might want to calculate total price properly
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

    // Redirect to success page
    gotopage('payment-success.php?orderno='.$order_no);
}

?>
