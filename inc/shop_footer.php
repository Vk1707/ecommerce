
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Popular Categories</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <?php
                            $sql="select * from product_category where status=1";
                            $rs=$db->select($sql);
                            foreach($rs as $row)
                            {
                                $catId=$row['id'];
                                $catName=$row['name'];
                                echo "<a href=shop.php?category_id=$catId class='text-secondary mb-2'><i class='fa fa-angle-right mr-2'></i>$catName</a>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-3" href="index.php"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-secondary mb-3" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            <div class="nav-item dropdown mb-3">
                                <a href="#" class="text-secondary mb-2 dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-angle-right mr-2"></i>E Stores</a>
                                <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
                                <?php
                                $sql="select * from ecommerce_store where status=1";
                                $rs=$db->select($sql);
                                foreach($rs as $row)
                                {
                                   $eid=$row['estore_id'];
                                   $ename=$row['estore_name'];
                                   echo "<a href='shop.php?estore_id=$eid' class='dropdown-item'>$ename</a>";
                                }
                                ?>
                                </div>
                            </div>
                            <a class="text-secondary mb-3" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                        <h5 class="text-secondary text-uppercase mb-4">Follow Us</h5>
                        <!-- <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor. Rebum tempor no vero est magna amet no</p>
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3 mb-4"></i>+012 345 67890</p> -->
                        <!-- <h5 class="text-secondary text-uppercase mb-4">Follow Us</h5> -->
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="https://www.facebook.com/hotshoppingdealss"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="https://www.instagram.com/hot_shoppingdeals?igsh=aXY5M2JsejBocmo2"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="https://t.me/+wQiswS-ITJs3MDVl"><i class="fab fa-telegram"></i></a>
                            <a class="btn btn-primary btn-square" href="https://in.pinterest.com/pcsaini0507/hot-shopping-deals/"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="https://www.hotshoppingdeals.in/">hotshoppingdeals.in</a>. All Rights Reserved. Designed & Developed
                    by
                    <a class="text-primary" href="https://www.ndspl.in/">NDSPL</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->