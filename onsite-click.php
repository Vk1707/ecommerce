<?php
    // include('./inc/dbclass.php');
    $db = new Database();
    $user ="";
    // Check if the user is logged in
    if (isset($_SESSION['user_email'])) {
        $userEmail = $_SESSION['user_email'];
        $user = $db->select_single("SELECT * FROM customer WHERE cust_email = '$userEmail'");
    }
    
    if ($user) {
        $userId = $user['cust_id'];
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
            // $existingClick = $db->select_single("SELECT * FROM onsite_clicks WHERE prod_id = $product_id AND user_id = $userId");
    
            // if (!$existingClick) {
               $data = ['prod_id' => $product_id, 'user_id' => $userId, 'click_date' => date("Y-m-d H:i:s")];
                $db->insert('onsite_clicks', $data);
            // }
        }
    } else{
        // for Guest user
        $userId = 0;
        $sessionId = session_id();  // Get the session ID of the guest user
        $product_id = $_GET['product_id'];

        // Check for existing click for guest user based on session ID
        // $existingClick = $db->select_single("SELECT * FROM onsite_clicks WHERE prod_id = $product_id AND session_id = '$sessionId'");

        // if(!$existingClick){
            $data = [
                'prod_id' => $product_id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'click_date' => date("Y-m-d H:i:s")
            ];
            
            $db->insert('onsite_clicks', $data);
        // }
    }
?>