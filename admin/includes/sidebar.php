<?php
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-gradient-dark fixed-start " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-light opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand d-flex align-items-center m-0" style="font-family: 'Allura', sans-serif;  font-size: 30px; color: #ffd700" href=" index.php " target="_blank">
      <span class="font-weight-bold text-xl " >Waniey Bridal</span>
    </a>
  </div>
  <ul class="navbar-nav">
    <div class="collapse navbar-collapse px-4 w-auto" id="sidenav-collapse-main">
      <li class="nav-item">
        <a class="nav-link  <?= $page == "index.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./index.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <svg width="30px" height="30px" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title>dashboard</title>
              <g id="dashboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="template" transform="translate(12.000000, 12.000000)" fill="#FFFFFF" fill-rule="nonzero">
                  <path class="color-foreground" d="M0,1.71428571 C0,0.76752 0.76752,0 1.71428571,0 L22.2857143,0 C23.2325143,0 24,0.76752 24,1.71428571 L24,5.14285714 C24,6.08962286 23.2325143,6.85714286 22.2857143,6.85714286 L1.71428571,6.85714286 C0.76752,6.85714286 0,6.08962286 0,5.14285714 L0,1.71428571 Z" id="Path"></path>
                  <path class="color-background" d="M0,12 C0,11.0532171 0.76752,10.2857143 1.71428571,10.2857143 L12,10.2857143 C12.9468,10.2857143 13.7142857,11.0532171 13.7142857,12 L13.7142857,22.2857143 C13.7142857,23.2325143 12.9468,24 12,24 L1.71428571,24 C0.76752,24 0,23.2325143 0,22.2857143 L0,12 Z" id="Path"></path>
                  <path class="color-background" d="M18.8571429,10.2857143 C17.9103429,10.2857143 17.1428571,11.0532171 17.1428571,12 L17.1428571,22.2857143 C17.1428571,23.2325143 17.9103429,24 18.8571429,24 L22.2857143,24 C23.2325143,24 24,23.2325143 24,22.2857143 L24,12 C24,11.0532171 23.2325143,10.2857143 22.2857143,10.2857143 L18.8571429,10.2857143 Z" id="Path"></path>
                </g>
              </g>
            </svg>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $page == "appointment.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./appointment.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-calendar-check " style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Appointments</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $page == "quotation.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./quotation.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-quote-left" style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Quotation Requests</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $page == "promotion.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./promotion.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-rectangle-ad" style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Promotions</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="navbarDropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-clipboard" style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Bookings & Rentals</span>
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-angle-down opacity-5"></i>
          </div>
        </a>
        <ul class="dropdown-menu bg-secondary mt-2" aria-labelledby="navbarDropdownMenuLink1">
          <li class="dropdown-item">
            <a class="nav-link position-relative ms-0 ps-2 py-2" href="pkg_bookings.php">
              <span class="nav-link-text ms-1">Package Bookings</span>
            </a>
          </li>
          <li class="dropdown-item">
            <a class="nav-link position-relative ms-0 ps-2 py-2 " href="./rentals.php">
              <span class="nav-link-text ms-1">Rentals</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item mt-2 ">
        <a class="nav-link  <?= $page == "packages.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./packages.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-box " style="font-size: 16px;"></i>
          </div>
          <span class="nav-link-text ms-1">Packages</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <a class="nav-link  <?= $page == "dais.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./dais.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-hand-holding-heart " style="font-size: 16px;"></i>
          </div>
          <span class="nav-link-text ms-1">Bridal Dais</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <a class="nav-link  <?= $page == "attire.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./attire.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-person-half-dress " style="font-size: 16px;"></i>
          </div>

          <span class="nav-link-text ms-1">Bridal Attires</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link  <?= $page == "menu.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./menu.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-bowl-food " style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Catering Menus</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link  <?= $page == "gallery.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./gallery.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-image " style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Manage Gallery</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link  <?= $page == "expenses.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./expenses.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-money-bill-trend-up" style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Company Expenses</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link  <?= $page == "external_resource.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./external_resource.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2" style="font-size: 16px; color: #ffffff">
              <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
            </svg>
          </div>
          <span class="nav-link-text ms-1">External Resources</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link  <?= $page == "customer.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./customer.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2" style="font-size: 16px; color: #ffffff">
              <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
            </svg>
          </div>
          <span class="nav-link-text ms-1">Customer Accounts</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link  <?= $page == "feedback.php" ? 'active bg-gradient-secondary' : ''; ?>" href="./feedback.php">
          <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
            <i class="fas fa-comments" style="font-size: 16px; color: #ffffff"></i>
          </div>
          <span class="nav-link-text ms-1">Manage Feedbacks</span>
        </a>
      </li>




    </div>
  </ul>

</aside>