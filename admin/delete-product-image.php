<?php
include_once("inc/chkAuth.php");
include('../inc/dbclass.php');

$db = new Database();
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Retrieve the image file path from the database before deleting
    $query = "SELECT url FROM product_images WHERE id = $id";
    $image = $db->select_single($query);
    // $image = mysqli_fetch_assoc($result);

    if ($image) {
        $result = $db->delete('product_images', 'id = :id', ['id' => $id]);
    
        if ($result > 0) {
            $file_path = '../uploads/products/' . $image['url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo 'success';  // Only this line should be echoed on success
        } else {
            echo 'Error deleting image from database.';
        }
    } else {
        echo 'Image not found.';
    }
    
} else {
    echo 'Invalid image ID.';
}

?>