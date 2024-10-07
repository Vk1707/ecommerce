<?php
include_once("dbclass.php");
$db = new Database();
include("counter.php");
if(isset($_POST['submit_search'])){
    $search = $_POST['search'];
if ($search) {
    $products = $db->select("SELECT * FROM product WHERE name like '%$search%'");
    if (count($products) > 0) {
        $_SESSION['products'] = $search;
        header("Location: search-result.php");
        exit();
    }
}
}

$user = null;
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $user = $db->select_single("SELECT * FROM customer WHERE cust_email ='$email'");
}

$estores = $db->select("SELECT * FROM ecommerce_store where status=1");
?>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="https://www.hotshoppingdeals.in/" class="text-decoration-none">
                    <span class="h3 text-uppercase text-primary bg-dark px-2">Hot Shopping</span>
                    <span class="h3 text-uppercase text-dark bg-primary px-2 ml-n1">Deals</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="" method="post">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <button class="input-group-text bg-transparent text-primary" name="submit_search">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <span class="text-dark m-0 h6"><i class="fa fa-bars mr-2"></i>Categories</span>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                    <?php
                    $sql="select * from product_category where status=1";
                    $rs=$db->select($sql);
                    foreach($rs as $row)
                    {
                         $catId=$row['id'];
                         $catName=$row['name'];
                         echo "<a href=shop.php?category_id=$catId class='nav-item nav-link'>$catName</a>";
                    } ?>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="index.php" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Hot Shopping</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Deals</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav py-0">
                            <a href="index.php" class="nav-item nav-link">Home</a>
                            <a href="shop.php" class="nav-item nav-link">Shop</a>
                            <div class="nav-item dropdown">
                                <a href="estores.php" id="estorePage" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                    E Stores <i class="fa fa-angle-down mt-1"></i>
                                </a>
                                <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
                                    <?php foreach($estores as $estore) { ?>
                                        <a href="estore-product.php?estore_id=<?= $estore['estore_id'] ?>" class="dropdown-item"><?= $estore['estore_name'] ?> </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <a href="contact.php" class="nav-item nav-link">Contact Us</a>
                        </div>
                        <a href="cart.php" class="btn px-0 ml-2">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">
                            <?php
                            if(isset($_SESSION['p_id_cart'])) {
                                $table_total_price = 0;
                                $i=0;
                                foreach($_SESSION['p_qty_cart'] as $key => $value) 
                                {
                                    $i++;
                                    $arr_p_qty_cart[$i] = $value;
                                }                    $i=0;
                                foreach($_SESSION['p_unit_price_cart'] as $key => $value) 
                                {
                                    $i++;
                                    $arr_p_unit_price_cart[$i] = $value;
                                }
                                // for($i=1;$i<=count($arr_p_qty_cart);$i++) {
                                //     $row_total_price = $arr_p_unit_price_cart[$i]*$arr_p_qty_cart[$i];
                                //     $table_total_price = $table_total_price + $row_total_price;
                                // }
                                echo count($_SESSION['p_id_cart']);
                            } else {
                                echo '0';
                            }
                            ?>
                            </span>
                        </a>
                        <?php if ($user): ?>
                        <div class="nav-item dropdown pt-1 hover-border-0">
                            <a href="#" class="nav-link dropdown-toggle p-0 hover-border-0" data-toggle="dropdown">
                            <?= $user['cust_name'] ?> <i class="fa fa-angle-down ml-3"></i></a>
                            <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
                                <a href="dashboard.php" class="dropdown-item">Dashboard</a>
                                <a href="profile.php" class="dropdown-item">Profile</a>
                                <a href="order-history.php" class="dropdown-item">My Orders</a>
                                <a href="logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </div>        
                        <?php else: ?>
                        <div class="navbar-nav ml-lg-auto py-0">
                            <a href="signin.php" class="nav-link">Sign In</a>
                            <a href="signup.php" class=" nav-link">Sign Up</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </nav>
        </div>
    </div>
    </div>
    <!-- Navbar End -->
    <script>
        // Function to check if the user is on a mobile device
        function isMobileDevice() {
            return /Mobi|Android/i.test(navigator.userAgent);
        }

        // Only add the event listener if not on a mobile device
        if (!isMobileDevice()) {
            let estorePage = document.getElementById("estorePage");
            if (estorePage) {
            estorePage.addEventListener("click", function(e) {
                window.location.href = 'estores.php';
            });
            }
        }
    </script>