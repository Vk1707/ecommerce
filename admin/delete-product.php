<?php
include_once("inc/chkAuth.php");
include("../inc/dbclass.php");

$db=new Database();

$id=$_GET['id'];

$query = "SELECT url FROM product_images WHERE id = $id";
$image = $db->select_single($query);

if($db->delete('product', 'id = :id', ['id' => $id])){
        $result = $db->delete('product_images','product_id = :id',['id' => $id]);
        if($result)
        $file_path = '../uploads/products/' . $image['url'];
        if (file_exists($file_path)) {
                unlink($file_path);
        }
}

gotopage('product-list.php');
?>
