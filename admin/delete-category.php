<?php
include_once("inc/chkAuth.php");
include("../inc/dbclass.php");


$db=new Database();

$id = $_GET['id'];

$db->delete('product_category', 'id = :id', ['id' => $id]);

$url = 'category-list.php?status=5';
gotopage($url);

?>