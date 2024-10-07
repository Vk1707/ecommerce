<?php
include("./inc/dbclass.php");
$db = new Database();
if(empty($_SESSION['p_id_cart'])) {
    header('location: cart.php');
    exit;
}

$userEmail = isset($_SESSION['user_email']);
$user = $db->select_single("SELECT * FROM customer WHERE cust_email ='$userEmail'");

$shipping = 100;


function calculate_totals() {
    $subtotal = 0;
    $shipping = 100;
    if (isset($_SESSION['p_id_cart'])) {
        foreach ($_SESSION['p_id_cart'] as $key => $value) {
            $subtotal += $_SESSION['p_unit_price_cart'][$key] * $_SESSION['p_qty_cart'][$key];
        }
    }
    $total = $subtotal + $shipping;
    return [$subtotal, $total,$shipping];
}
list($subtotal, $total) = calculate_totals();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Checkout</title>
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


<?php
include_once("./inc/shop_navbar.php");
?>

    <!-- Checkout Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-12 mb-5">
                <?php  $kk = 1;
                if (isset($_SESSION['p_id_cart']) && !empty($_SESSION['p_id_cart'])): ?>
                <div class="table-responsive">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Order Total</span></h5>
                    <table class="table table-light table-borderless text-center mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>S.NO.</th>
                                <th>Img</th>
                                <th>Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                                <?php foreach ($_SESSION['p_id_cart'] as $i => $product_id): ?> 
                                <tr>
                                    <td class="align-middle"><?= $kk++; ?></td>
                                    <td class="align-middle">
                                        <img src="./uploads/products/<?php echo $_SESSION['p_f_photo_cart'][$i]; ?>" alt="" style="width: 50px;">
                                    </td>
                                    <td class="align-middle"><?php echo $_SESSION['p_name_cart'][$i]; ?></td>
                                    <td class="align-middle"><?php echo "₹ " . $_SESSION['p_unit_price_cart'][$i]; ?></td>
                                    <td class="align-middle">
                                        <?php echo $_SESSION['p_qty_cart'][$i]; ?>
                                    </td>
                                    <td class="align-middle"> <?php echo "₹ " . $_SESSION['p_unit_price_cart'][$i] * $_SESSION['p_qty_cart'][$i]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <div class="d-flex justify-content-between">
                        <p>Your cart is empty</p>
                        </div>
                    <?php endif; ?>
                    </div>
                    <div class="pt-3 pb-2">
                        <table class="table bg-light table-bordered">
                            <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td><b><?= $subtotal ?></b></td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td><?= $shipping ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <td><b><?= $total ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row px-xl-5">
            <?php if(isset($_SESSION['user_email'])): ?>
                <div class="col-lg-6">
                    <h5 class="position-relative text-uppercase mb-3">
                        <span class="bg-secondary pr-3">Billing Address</span>
                    </h5>
                    <div class="bg-light p-30 mb-5">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Full Name</label>
                                <input class="form-control" type="text" name="billing_name" placeholder="John" value="<?= htmlspecialchars($user['cust_b_name']); ?>" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mobile No</label>
                                <input class="form-control" type="text" name="billing_mobile" placeholder="+123 456 789" value="<?= htmlspecialchars($user['cust_b_phone']); ?>" readonly>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" disabled><?= htmlspecialchars($user['cust_b_address']); ?></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>City</label>
                                <input class="form-control" type="text" name="billing_city" placeholder="New York" value="<?= htmlspecialchars($user['cust_b_city']); ?>" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>State</label>
                                <input class="form-control" type="text" name="billing_state" placeholder="New York" value="<?= htmlspecialchars($user['cust_b_state']); ?>" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Pin Code</label>
                                <input class="form-control" type="text" name="billing_pin" placeholder="12345" value="<?= htmlspecialchars($user['cust_b_pincode']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="position-relative text-uppercase mb-0 pr-3">
                            <span class="bg-secondary pr-3">Shipping Address</span>
                        </h5>
                        <div class="ml-auto">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="same_as_billing" name="same_as_billing" onclick="copyBillingInfo()">
                                <label class="custom-control-label" for="same_as_billing">Same as Billing</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light p-30">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Full Name</label>
                                <input class="form-control" type="text" name="shipping_name" placeholder="John" value="<?= htmlspecialchars($user['cust_s_name']); ?>" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mobile No</label>
                                <input class="form-control" type="text" name="shipping_mobile" placeholder="+123 456 789" value="<?= htmlspecialchars($user['cust_s_phone']); ?>" readonly>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Address</label>
                                <textarea name="shipping_address" class="form-control" id="shipping_address" disabled><?= htmlspecialchars($user['cust_s_address']); ?></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>City</label>
                                <input class="form-control" type="text" name="shipping_city" placeholder="New York" value="<?= htmlspecialchars($user['cust_s_city']); ?>" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>State</label>
                                <input class="form-control" type="text" name="shipping_state" placeholder="New York" value="<?= htmlspecialchars($user['cust_s_state']); ?>" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Pin Code</label>
                                <input class="form-control" type="text" name="shipping_pin" placeholder="12345" value="<?= htmlspecialchars($user['cust_s_pincode']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
                <?php
                    if (isset($_SESSION['customer'])) {
                        $customer = $_SESSION['customer'];
                        $checkout_access = 1;
                        
                        if (
                            empty($customer['cust_b_name']) ||
                            empty($customer['cust_b_phone']) ||
                            empty($customer['cust_b_address']) ||
                            empty($customer['cust_b_city']) ||
                            empty($customer['cust_b_state']) ||
                            empty($customer['cust_b_pincode']) ||
                            empty($customer['cust_s_name']) ||
                            empty($customer['cust_s_phone']) ||
                            empty($customer['cust_s_address']) ||
                            empty($customer['cust_s_city']) ||
                            empty($customer['cust_s_state']) ||
                            empty($customer['cust_s_pincode'])
                        ) {
                            $checkout_access = 0;
                        }
                    } else {
                        $checkout_access = 0;
                    }
                ?>
                <?php 
                if (!isset($_SESSION['user_email'])) {
                        // If not logged in, redirect to login page or show an error message
                        echo '<div class="col-md-12">';
                        echo '<div style="color:red;font-size:22px;margin-bottom:50px;">';
                        echo 'You need to be logged in to proceed with the checkout. Please <a href="signin.php" style="color:red;text-decoration:underline;">Sign here</a>.';
                        echo '</div>';
                        echo '</div>';
                    } else {
                
                if($checkout_access == 0): ?>
                    <div class="col-md-12">
                        <div style="color:red;font-size:22px;margin-bottom:50px;">
                            You must have to fill up all the billing and shipping information from your dashboard panel in order to checkout the order. Please fill up the information going to <a href="cust-bill-ship.php" style="color:red;text-decoration:underline;">this link</a>.
                        </div>
                    </div>
                <?php else: ?>
                <div class="col-lg-12">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Payment Method</span></h5>
                        <div class="form-group">
                            <select name="payment_method" class="form-control select" id="advFieldsStatus">
                                <option value="">Select a payment method</option>
                                <option value="COD">COD</option>
                                <option value="BankDeposit">Bank Deposit</option>
                            </select>
                        </div>
                        <form action="cod.php" method="post" id="cod_order" target="_blank">
                            <input type="hidden" name="amount" value="<?php echo $total; ?>">
                            <div class="col-md-12 form-group">
                                <input type="submit" class="btn btn-primary" value="Place Order" name="COD">
                            </div>
                        </form>

                        <form action="init.php" method="post" id="bank_form" enctype="multipart/form-data">
                            <input type="hidden" name="amount" value="<?php echo $total; ?>">
                            <div class="row">
                                <!-- Bank Details Section -->
                                <div class="col-lg-6 form-group">
                                    <label for="">Bank Details : </label><br>
                                    <div class="table table-bordered p-2 bg-white mt-2">
                                        <label for="">Send to this Details</label><br />
                                        <strong>Bank Name:</strong> WestView Bank<br />
                                        <strong>Account Number:</strong> CA100270589600<br />
                                        <strong>Branch Name:</strong> CA Branch<br />
                                        <strong>Country:</strong> USA
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="payment_img" class="form-label">Attach Screenshot:</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="payment_img" id="payment_img">
                                            <label class="custom-file-label" for="payment_img">Choose file...</label>
                                        </div>
                                    </div>

                                </div>

                                <!-- Transaction Information Section -->
                                <div class="col-md-6 form-group">
                                    <label for="transaction_info">Transaction Information:</label>
                                    <span style="font-size:12px;font-weight:normal;">(Provide your transaction details here)</span>
                                    <textarea name="transaction_info" class="form-control mt-2" rows="9" placeholder="Enter transaction details"></textarea>
                                </div>
                            </div>

                            <!-- Submit Button Section -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-block btn-primary font-weight-bold py-3" value="Place Order" name="BankDeposit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
                <?php } ?>
            </div>
        </div>  
    </div> 
    <!-- Checkout End -->

    <?php
    include_once("./inc/shop_footer.php");
    ?>
    
   
    <!-- Back to Top -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="shop/assets/lib/easing/easing.min.js"></script>
    <script src="shop/assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="shop/assets/mail/jqBootstrapValidation.min.js"></script>
    <script src="shop/assets/mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="shop/assets/js/main.js"></script>
    <script>
    // Display file name next to the file input field
    document.querySelector('#payment_img').addEventListener('change', function (e) {
        var fileName = document.getElementById("payment_img").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    $(document).ready(function () {
		advFieldsStatus = $('#advFieldsStatus').val();

		$('#cod_order').hide();
		$('#stripe_form').hide();
		$('#bank_form').hide();

        $('#advFieldsStatus').on('change',function() {
            advFieldsStatus = $('#advFieldsStatus').val();
            if ( advFieldsStatus == '' ) {
            	$('#cod_order').hide();
				$('#stripe_form').hide();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'COD' ) {
               	$('#cod_order').show();
				$('#stripe_form').hide();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'Stripe' ) {
               	$('#cod_order').hide();
				$('#stripe_form').show();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'BankDeposit' ) {
            	$('#cod_order').hide();
				$('#stripe_form').hide();
				$('#bank_form').show();
            }
        });
	});
    </script>
</body>

</html>
 