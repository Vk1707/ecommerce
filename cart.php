<?php
session_start();
include('./inc/dbclass.php');
$db = new Database();

function calculate_totals() {
    $subtotal = 0;
    $shipping = 100; // Static shipping cost
    if (isset($_SESSION['p_id_cart'])) {
        foreach ($_SESSION['p_id_cart'] as $key => $value) {
            $subtotal += $_SESSION['p_unit_price_cart'][$key] * $_SESSION['p_qty_cart'][$key];
        }
    }
    $total = $subtotal + $shipping;
    return [$subtotal, $total];
}

$error_message = '';

if (isset($_POST['update_quantity'])) {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        foreach ($_POST['product_id'] as $index => $product_id) {
            $new_qty = intval($_POST['quantity'][$index]);
            if ($new_qty > 0) {
                foreach ($_SESSION['p_id_cart'] as $key => $value) {
                    if ($value == $product_id) {
                        $_SESSION['p_qty_cart'][$key] = $new_qty;  // Update quantity in session
                        break;
                    }
                }
            }
        }
    }
}

// Handle product removal
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['p_id_cart'])) {
        foreach ($_SESSION['p_id_cart'] as $key => $value) {
            if ($value == $product_id) {
                unset($_SESSION['p_id_cart'][$key]);
                unset($_SESSION['p_qty_cart'][$key]);
                unset($_SESSION['p_unit_price_cart'][$key]);
                unset($_SESSION['p_name_cart'][$key]);
                unset($_SESSION['p_f_photo_cart'][$key]);
                $_SESSION['p_id_cart'] = array_values($_SESSION['p_id_cart']);
                $_SESSION['p_qty_cart'] = array_values($_SESSION['p_qty_cart']);
                $_SESSION['p_unit_price_cart'] = array_values($_SESSION['p_unit_price_cart']);
                $_SESSION['p_name_cart'] = array_values($_SESSION['p_name_cart']);
                $_SESSION['p_f_photo_cart'] = array_values($_SESSION['p_f_photo_cart']);
                break;
            }
        }
    }
}

// Handle coupon application
if (isset($_POST['apply_coupon'])) {
    $coupon_code = $_POST['coupon_code'];
    // Add your coupon validation logic here
}

list($subtotal, $total) = calculate_totals();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cart</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="shop/assets/img/shopify.svg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="shop/assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="shop/assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="shop/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include_once("./inc/shop_navbar.php"); ?>

<!-- Cart Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>S.NO.</th>
                        <th>Img</th>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php  $kk = 1;
                     if (isset($_SESSION['p_id_cart']) && !empty($_SESSION['p_id_cart'])): ?>
                        <?php foreach ($_SESSION['p_id_cart'] as $i => $product_id): ?> 
                        <tr>
                            <td><?= $kk++; ?></td>
                            <td class="align-middle">
                                <img src="./uploads/products/<?php echo $_SESSION['p_f_photo_cart'][$i]; ?>" alt="" style="width: 50px;">
                            </td>
                            <td class="align-middle"><?php echo $_SESSION['p_name_cart'][$i]; ?></td>
                            <td class="align-middle"><?php echo "₹ " . $_SESSION['p_unit_price_cart'][$i]; ?></td>
                            <td class="align-middle">
                            <form method="POST">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" type="button" onclick="updateQuantity(<?php echo $product_id; ?>, 'minus')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center" name="quantity[]" id="quantity_<?php echo $product_id; ?>" value="<?php echo $_SESSION['p_qty_cart'][$i]; ?>">
                                    <input type="hidden" name="product_id[]" value="<?php echo $product_id; ?>">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus" type="button" onclick="updateQuantity(<?php echo $product_id; ?>, 'plus')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" name="update_quantity" class="btn btn-sm btn-primary">Update</button>
                            </form>
                            </td>
                            <td class="align-middle"> <?php echo "₹ " . $_SESSION['p_unit_price_cart'][$i] * $_SESSION['p_qty_cart'][$i]; ?></td>
                            <td class="align-middle">
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" name="remove_product" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Your cart is empty</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <form class="mb-30" method="POST">
                <div class="input-group">
                    <input type="text" class="form-control border-0 p-4" placeholder="Coupon Code" name="coupon_code">
                    <div class="input-group-append">
                        <button type="submit" name="apply_coupon" class="btn btn-primary">Apply Coupon</button>
                    </div>
                </div>
            </form>
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart Summary</span></h5>
            <div class="bg-light p-30 mb-5">
                <div class="border-bottom pb-2">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Subtotal</h6>
                        <h6> <?php echo "₹ " . $subtotal; ?></h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Shipping</h6>
                        <h6 class="font-weight-medium">₹ 100</h6>
                    </div>
                </div>
                <div class="pt-2">
                    <div class="d-flex justify-content-between mt-2">
                        <h5>Total</h5>
                        <h5><?php echo "₹ " . $total; ?></h5>
                    </div>
                    <a href="checkout.php" class="btn btn-block btn-primary font-weight-bold my-3 py-3">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->

<?php include_once("./inc/shop_footer.php"); ?>

<!-- Back to Top -->
<a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="shop/assets/lib/easing/easing.min.js"></script>
<script src="shop/assets/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="shop/assets/js/main.js"></script>

<script>
    function updateQuantity(productId, action) {
        var quantityField = document.getElementById('quantity_' + productId);
        var currentQuantity = parseInt(quantityField.value);
        if (action === 'plus') {
            quantityField.value = currentQuantity + 1;
        } else if (action === 'minus' && currentQuantity > 1) {
            quantityField.value = currentQuantity - 1;
        }
    }
</script>
</body>
</html>
