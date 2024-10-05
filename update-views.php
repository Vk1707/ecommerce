<?php
$product_id=$_GET['product_id'];
$sql="select views from product where id='$product_id'";
$rwT=$db->select_single($sql);

$views=$rwT['views'];
$views=$views+1;

$data=['views'=>$views];
$db->update('product', $data, 'id = :id', ['id' => $product_id]);
?>