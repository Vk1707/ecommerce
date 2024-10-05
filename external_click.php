<?php
include('./inc/dbclass.php');
$db = new Database();

$userId ="";


// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $user = $db->select_single("SELECT * FROM user WHERE email = '$userEmail'");
}

if ($user) {
    $userId = $user['user_id'];
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
            $data = ['prod_id' => $product_id, 'user_id' => $userId, 'click_date' => date("Y-m-d H:i:s")];
            $db->insert('external_clicks', $data);
    }
} else{
    $userId = 0;
    $product_id = $_GET['product_id'];
        $data = [
            'prod_id' => $product_id,
            'user_id' => $userId,
            'click_date' => date("Y-m-d H:i:s")
        ];
        
        $db->insert('external_clicks', $data);
    }
$product_id = $_GET['product_id'];
?>
