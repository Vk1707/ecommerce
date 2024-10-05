<?php
session_start();

if (isset($_SESSION['userName'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
