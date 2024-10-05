  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class ="bg-menu-theme">
        <div class="app-brand demo">
          <a href="dashboard.php" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="assets/img/icons/brands/slack.png" height="30px" />
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2" style="text-transform: capitalize; font-size:22px;">Admin</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item">
            <a href="dashboard.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Dashboard</div>
            </a>
          </li>
          
          <!-- Product -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Manage Products</span></li>
          <li class="menu-item">
            <a href="add-product.php" class="menu-link">
              <i class="menu-icon tf-icons bx bxs-package"></i>
              <div>Add Product</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="product-list.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-ul"></i>
              <div>Product List</div>
            </a>
          </li>

          <!-- orders -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Manage Orders</span></li>
          <li class="menu-item">
            <a href="order-manage.php" class="menu-link">
              <i class="menu-icon tf-icons bx bxs-package"></i>
              <div>Order list</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="payments-manage.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-ul"></i>
              <div>Payment List</div>
            </a>
          </li>
          

           
         
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Setup & Tools</span></li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-dock-top"></i>
              <div>Setup</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="manage-admin.php" class="menu-link">
                  <div>Admin</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="manage-user.php" class="menu-link">
                  <div>User</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="category-list.php" class="menu-link">
                  <div>Category</div>
                </a>
              </li>
              <li class="menu-item">
                 <a href="estore-list.php" class="menu-link">
                  <div>eStore</div>
                </a>
              </li>
              <li class="menu-item">
                 <a href="manage-banner.php" class="menu-link">
                  <div>Banners</div>
                </a>
              </li>
            </ul>
          </li>
            <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-cube-alt"></i>
              <div>Tools</div>
            </a>
              <ul class="menu-sub">
              <li class="menu-item">
                <a href="onsite-clicks.php" class="menu-link">
                  <div>Onsite Clicks</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="external-clicks.php" class="menu-link">
                  <div>External Clicks</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="website-visitors.php" class="menu-link">
                  <div>Visitor Counter</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="database-backup.php" class="menu-link">
                  <div>Backup Database</div>
                </a>
              </li>

            </ul>
          </li>
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Manage Account</span></li>
          <li class="menu-item">
            <a href="admin-profile.php" class="menu-link ">
              <i class="menu-icon tf-icons bx bxs-user-detail"></i>
              <div>Admin Profile</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="change-password.php" class="menu-link ">
              <i class="menu-icon tf-icons bx bxs-lock"></i>
              <div>Change Password</div>
            </a>
          </li>  
          <li class="menu-item">
            <a href="logout.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-log-out-circle"></i>
              <div>Logout</div>
            </a>
          </li>  
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">