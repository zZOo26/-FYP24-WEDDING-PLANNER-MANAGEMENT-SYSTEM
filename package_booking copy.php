<?php
session_start();
include('dbcon.php');
include('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['cust_id'])) {
    // Capture the current URL and redirect to login with it as a query parameter
    $redirect_url = $_SERVER['REQUEST_URI'];
    header("Location: login.php?redirect=" . urlencode($redirect_url));
    die;
}

$user_data = check_login($con);

$_SESSION;
$page_title = "Book Package";
include('includes/header.php');

$promo_message = "";
$discount_amount = 0;
$promo_code = "";

if (isset($_POST['apply_promo'])) {
    $promo_code = $_POST['promo_code'];
    // echo "Promo code received: " . htmlspecialchars($promo_code) . "<br>"; // Debugging statement

    $promo_query = "SELECT a.*, b.* FROM promotions a INNER JOIN promotion_category b ON a.promo_ctg_id=b.promo_ctg_id 
    WHERE promo_code = ? AND promo_status = 'Ongoing' AND ctg_name='Package' LIMIT 1";
    $stmt = mysqli_prepare($con, $promo_query);
    mysqli_stmt_bind_param($stmt, "s", $promo_code);
    mysqli_stmt_execute($stmt);
    $promo_result = mysqli_stmt_get_result($stmt);

    if ($promo_result) {
        // echo "Number of rows: " . mysqli_num_rows($promo_result) . "<br>"; // Debugging statement
        if (mysqli_num_rows($promo_result) > 0) {
            $promo_data = mysqli_fetch_assoc($promo_result);
            $discount_amount = $promo_data['amount_off'];
            $promo_message = "Promo code applied successfully!";

            // echo "Promo applied: Discount amount is " . $discount_amount . "<br>"; // Debugging statement
        } else {
            $promo_message = "Invalid or expired promo code.";
            // echo "No matching promo code found.<br>"; // Debugging statement
        }
    } else {
        $promo_message = "Query execution failed.";
        // echo "Error executing query: " . mysqli_error($con) . "<br>"; // Debugging statement
    }

    mysqli_stmt_close($stmt);
}

if (isset($_GET['pkg_id']) && isset($_GET['date']) && isset($_GET['slot']) && isset($_GET['total_pax'])) {
    $pkg_id = $_GET['pkg_id'];
    $event_date = $_GET['date'];
    $slot_id = $_GET['slot'];
    $total_pax = $_GET['total_pax'];

    // Query to fetch package details
    $pkg_query = "SELECT * FROM packages WHERE pkg_id = ?";
    $stmt = mysqli_prepare($con, $pkg_query);
    mysqli_stmt_bind_param($stmt, "i", $pkg_id);
    mysqli_stmt_execute($stmt);
    $pkg_result = mysqli_stmt_get_result($stmt);
    $package = mysqli_fetch_array($pkg_result);

    if (!$package) {
        echo "Package not found.";
        exit();
    }

    // Calculate total menu price for the selected package
    $menu_query = "SELECT SUM(total_price) AS total_menu_price 
                        FROM package_menu 
                        WHERE pkg_id = ?";

    $stmt = mysqli_prepare($con, $menu_query);
    mysqli_stmt_bind_param($stmt, "i", $pkg_id);
    mysqli_stmt_execute($stmt);
    $menu_result = mysqli_stmt_get_result($stmt);
    $menu_data = mysqli_fetch_assoc($menu_result);

    $total_menu_price = $menu_data['total_menu_price'];
    // echo  $total_menu_price;

    // Recalculate total menu price according to total_pax
    $recalculated_total_menu_price = ($total_menu_price / 100)  * $total_pax;
    // echo $recalculated_total_menu_price;

    // Query to fetch booked attire and dais for the selected event date (from package_bookings table)
    $booking_query = "
        SELECT dais_id, attire_id
        FROM package_bookings 
        WHERE event_date = ? AND pkg_id = ?
    ";
    $stmt = mysqli_prepare($con, $booking_query);
    mysqli_stmt_bind_param($stmt, "si", $event_date, $pkg_id);
    mysqli_stmt_execute($stmt);
    $booking_result = mysqli_stmt_get_result($stmt);
    $booked_items = mysqli_fetch_all($booking_result, MYSQLI_ASSOC);

    // Query to fetch rented attire and dais for the selected event date (from rentals table)
    $rental_query = "
        SELECT item_id, rental_type
        FROM rentals
        WHERE event_date = ? AND rental_type IN ('attire', 'dais')
    ";
    $stmt = mysqli_prepare($con, $rental_query);
    mysqli_stmt_bind_param($stmt, "s", $event_date);
    mysqli_stmt_execute($stmt);
    $rental_result = mysqli_stmt_get_result($stmt);
    $rented_items = mysqli_fetch_all($rental_result, MYSQLI_ASSOC);

    // Fetching available dais that are not booked or rented
    $available_dais_query = "
        SELECT d.dais_id, d.name, d.image
        FROM bridal_dais d
        WHERE d.dais_id NOT IN (
            SELECT pb.dais_id FROM package_bookings pb WHERE pb.event_date = ? AND pb.pkg_id = ?
            UNION
            SELECT r.item_id FROM rentals r WHERE r.event_date = ? AND r.rental_type = 'dais'
        )
    ";
    $stmt = mysqli_prepare($con, $available_dais_query);
    mysqli_stmt_bind_param($stmt, "sis", $event_date, $pkg_id, $event_date);
    mysqli_stmt_execute($stmt);
    $available_dais = mysqli_stmt_get_result($stmt);

    // Fetching available attire that are not booked or rented
    $available_attire_query = "
        SELECT a.attire_id, a.name, a.image
        FROM bridal_attire a
        WHERE a.attire_id NOT IN (
            SELECT pb.attire_id FROM package_bookings pb WHERE pb.event_date = ? AND pb.pkg_id = ?
            UNION
            SELECT r.item_id FROM rentals r WHERE r.event_date = ? AND r.rental_type = 'attire'
        )
    ";
    $stmt = mysqli_prepare($con, $available_attire_query);
    mysqli_stmt_bind_param($stmt, "sis", $event_date, $pkg_id, $event_date);
    mysqli_stmt_execute($stmt);
    $available_attire = mysqli_stmt_get_result($stmt);
} else {
    echo "Required parameters missing.";
    exit();
}

if ($package) {
    $pkg_price = $package['pkg_price'];
    $subtotal = ($pkg_price - $total_menu_price) + $recalculated_total_menu_price;
    $deposit = $package['deposit'];
    $subtotal_after_discount = $subtotal - $discount_amount;
?>
    <div class="wrapper">
        <div class="section-shaped my-0 skew-separator skew-mini">
            <div class="page-header min-vh-65 opacity-9" style="background-image: url('assets/img/IMG_1010.jpg');">
                <div class="container">
                    <div class="header-body text-center mb-7">
                        <div class="row justify-content-center">
                            <div class="col-xl-5 col-lg-6 col-md-8 px-5 mt-6">
                                <h1 class="text-white">Checkout </h1>
                                <p class="text-lead text-white">"Step into your fairy-tale wedding effortlessly with our beautiful dresses and daises!"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-relative overflow-hidden" style="height:36px;margin-top:-33px;">
                <div class="w-full absolute bottom-0 start-0 end-0" style="transform: scale(2);transform-origin: top center;color: #fff;">
                    <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="upper">
            <div class="container">
                <div class="row mt-n9 pb-4 p-3 mx-sm-0 mx-1 position-relative">
                    <div class="col-lg-4">
                        <div class="container">
                            <div class="row">
                                <h3 class="title text-white mt-0">Order summary</h3>
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <img src="admin/uploads/<?= $package['pkg_img'] ?>" alt="Rounded image" class="avatar avatar-xl">
                                            </div>
                                            <div class="col-md-7">
                                                <h2 class="h6"><?= $package['pkg_name'] ?></h2>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <label for="">Total Pax:</label>
                                            </div>
                                            <div class="col-md-7 ">
                                                <span><?= $total_pax ?></span>
                                            </div>
                                        </div>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 50px; margin-bottom: 1rem;">
                                        <form method="POST">
                                            <label>Discount</label>
                                            <div class="row mb-3">
                                                <div class="col-md-9" style="margin-right: 0px;">
                                                    <input type="text" class="form-control form-control-md" name="promo_code" placeholder="Discount Code" aria-label="Discount" value="<?= $promo_code ?>">
                                                </div>
                                                <div class="col-md-3 mr-2">
                                                    <button type="submit" name="apply_promo" class="btn btn-warning px-3 mb-0">Apply</button>
                                                </div>
                                                <span class="text-sm opacity-8 text-success">
                                                    <?php
                                                    if ($promo_message == 'Promo code applied successfully!') {

                                                    ?>
                                                        <span class="text-sm opacity-8 text-success"><?= $promo_message ?></span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <span class="text-sm opacity-8 text-danger"><?= $promo_message ?></span>
                                                    <?php
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </form>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 50px; margin-bottom: 1rem;">
                                        <div class="row  align-items-center">
                                            <div class="col-md-4">
                                                <span class="h6 opacity-8 mr-3">Subtotal</span>
                                            </div>
                                            <div class="col-md-8 text-end">

                                                <span>RM<?= $subtotal ?></span>
                                            </div>
                                        </div>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 50px; margin-bottom: 1rem;">
                                        <div class="row  align-items-center">
                                            <div class="col-md-4">
                                                <span class="h6 opacity-8 mr-3">Discount</span>
                                            </div>
                                            <div class="col-md-8 text-end">
                                                <span> - RM<?= $discount_amount ?></span>
                                            </div>
                                        </div>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 50px; margin-bottom: 1rem;">
                                        <div class="row  align-items-center">
                                            <div class="col-md-4">
                                                <span class="h6 opacity-8 mr-3">Subtotal After Discount</span>
                                            </div>
                                            <div class="col-md-8 text-end">
                                                <span>RM<?= $subtotal_after_discount ?></span>
                                            </div>
                                        </div>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 50px; margin-bottom: 1rem;">
                                        <div class="row  align-items-center">
                                            <div class="col-md-4">
                                                <span class="h6">Deposit</span>
                                            </div>
                                            <div class="col-md-8 text-end">
                                                <span class="font-weight-semi-bold">RM<?= $deposit ?> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card shadow-lg">
                            <form action='add-booking.php' method="POST">
                                <input type="hidden" name="pkg_id" value="<?= $pkg_id ?>">
                                <input type="hidden" name="pax" value="<?= $total_pax ?>">
                                <input type="hidden" name="subtotal_after_discount" value="<?= $subtotal_after_discount ?>">
                                <input type="hidden" name="deposit_amount" value="<?= ceil($deposit) ?>">
                                <input type="hidden" name="promo_code" value="<?= htmlspecialchars($promo_code) ?>">
                                <div class="container">
                                    <h3 class="title mt-3">Billing address</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="labels">
                                                First name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="firstName" value="<?= $user_data['firstname'] ?>" aria-label="Cristopher" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="labels">
                                                Last name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="lastName" value="<?= $user_data['lastname'] ?>" aria-label="Thompson" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="js-form-prmo_message">
                                                <label class="labels">
                                                    Email address
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" class="form-control" name="emailAddress" value="<?= $user_data['email'] ?>" aria-label="thompson@gmail.com" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="js-form-prmo_message">
                                                <label class="labels">
                                                    Phone
                                                </label>
                                                <input type="text" class="form-control" value="+60<?= $user_data['phoneNo'] ?>" aria-label="+4 (0762) 230991" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                    <h4 class="title">Event Details</h4>
                                    <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                    <div class="row mb-4">
                                        <div class="col-md-6 js-form-prmo_message">
                                            <label class="labels">
                                                Event Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" name="eventdate" value="<?= $id = $_GET['date'] ?>" readonly>
                                        </div>
                                        <div class="col-md-6 js-form-prmo_message">
                                            <label class="labels">
                                                Event Slot
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="eventslot" value="<?= $id = $_GET['slot'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="js-form-prmo_message">
                                            <label class="labels">
                                                Event Location
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="streetAddress" placeholder="No 20, Jalan Lestari 3, Taman Lestari Jaya" aria-label="420 Long Beach, CA" required="">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="js-form-prmo_message mb-4">
                                                <label class="labels">
                                                    Bride's Name
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="bride_name" aria-label="London" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="labels">
                                                    Groom's Name
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="groom_name" aria-label="London" required="">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attire Carousel -->
                                    <?php if (mysqli_num_rows($available_attire) > 0) : ?>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                        <h4 class="title">Select Attire:</h4>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                        <div id="attireCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <?php
                                                $attireCount = 0; // Counter for tracking items
                                                while ($attire = mysqli_fetch_assoc($available_attire)) : ?>
                                                    <?php if ($attireCount % 4 == 0) : ?>
                                                        <div class="carousel-item <?= $attireCount === 0 ? 'active' : '' ?>">
                                                            <div class="row">
                                                            <?php endif; ?>
                                                            <div class="col-md-3 mb-2">
                                                                <div class="position-relative attire-card" data-attire-id="<?= $attire['attire_id'] ?>">
                                                                    <div class="card shadow-lg min-height-250 max-height-300 text-white" style="background-image: url('admin/uploads/<?= htmlspecialchars($attire['image']); ?>'); background-size: cover; background-position: center;">
                                                                        <div class="card-body d-flex flex-column justify-content-end">
                                                                            <h6 class="card-title mt-3 bg-light opacity-8 text-center text-dark"><?= htmlspecialchars($attire['name']); ?></h6>
                                                                            <div class="position-absolute top-0 start-0 p-2">
                                                                                <i class="fas fa-check-circle fa-2x text-success checked-icon d-none"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="selected_attire" value="<?= $attire['attire_id'] ?>" class="d-none">
                                                                </div>
                                                            </div>
                                                            <?php $attireCount++; ?>
                                                            <?php if ($attireCount % 4 == 0 || $attireCount === mysqli_num_rows($available_attire)) : ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </div>
                                            <!-- Attire Carousel Controls -->
                                            <button class="carousel-control-prev" type="button" data-bs-target="#attireCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#attireCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <!-- End Attire Carousel -->



                                    <!-- Dais Carousel -->
                                    <?php if (mysqli_num_rows($available_dais) > 0) : ?>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                        <h4 class="title">Select Dais:</h4>
                                        <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                        <div id="daisCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <?php
                                                $daisCount = 0; // Counter for tracking items
                                                while ($dais = mysqli_fetch_assoc($available_dais)) : ?>
                                                    <?php if ($daisCount % 4 == 0) : ?>
                                                        <div class="carousel-item <?= $daisCount === 0 ? 'active' : '' ?>">
                                                            <div class="row">
                                                            <?php endif; ?>
                                                            <div class="col-md-3 mb-2">
                                                                <div class="position-relative dais-card" data-dais-id="<?= $dais['dais_id'] ?>">
                                                                    <div class="card shadow-lg min-height-250 max-height-300 text-white" style="background-image: url('admin/uploads/<?= htmlspecialchars($dais['image']); ?>'); background-size: cover; background-position: center;">
                                                                        <div class="card-body d-flex flex-column justify-content-end">
                                                                            <h6 class="card-title mt-3 bg-light opacity-8 text-center text-dark"><?= htmlspecialchars($dais['name']); ?></h6>
                                                                            <div class="position-absolute top-0 start-0 p-2">
                                                                                <i class="fas fa-check-circle fa-2x text-success checked-icon d-none"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="selected_dais" value="<?= $dais['dais_id'] ?>" class="d-none">
                                                                </div>
                                                            </div>
                                                            <?php $daisCount++; ?>
                                                            <?php if ($daisCount % 4 == 0 || $daisCount === mysqli_num_rows($available_dais)) : ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </div>
                                            <!-- Dais Carousel Controls -->
                                            <button class="carousel-control-prev" type="button" data-bs-target="#daisCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#daisCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <!-- End Dais Carousel -->




                                    <!-- Remaining HTML and PHP code remains unchanged -->


                                    <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                    <h4 class="title">Payment method</h4>
                                    <hr style="border: none; height: 2px; background-color: #ffc107; width: 150px; margin-bottom: 1rem;">
                                    <div class="nav nav-tabs nav-tabs-primary btn-group" role="tablist">
                                        <a class="btn btn-sm btn-outline-warning active" data-toggle="tab" href="#link1" role="tablist">
                                            Credit Card
                                        </a>
                                        <!-- <a class="btn btn-sm btn-outline-warning" data-toggle="tab" href="#link2" role="tablist">
                                            Offline
                                        </a> -->
                                    </div>
                                    <div class="tab-content tab-space">
                                        <div id="link1" class="tab-pane active">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="js-form-prmo_message">
                                                        <label class="form-label">
                                                            Card number
                                                        </label>
                                                        <input type="text" class="form-control" name="cardNumber" placeholder="**** **** **** ***" aria-label="**** **** **** ***" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="js-form-prmo_message mb-4">
                                                        <label class="form-label">
                                                            Card holder
                                                        </label>
                                                        <input type="text" class="form-control" name="cardHolder" placeholder="Jack Wayley" aria-label="Jack Wayley" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="js-form-prmo_message mb-4">
                                                        <label class="form-label">
                                                            Expiration
                                                        </label>
                                                        <input type="text" class="form-control" name="cardExpirationDate" placeholder="MM/YY" aria-label="MM/YY" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="js-form-prmo_message mb-4">
                                                        <label class="form-label">
                                                            CVC
                                                        </label>
                                                        <input type="text" class="form-control" name="cardCVC" placeholder="***" aria-label="***" required="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end mt-3">
                                                <a class="btn btn-outline-warning btn-sm" href="packages.php"><span class="fas fa-angle-left mr-2"></span> Cancel</a>
                                                <button type="submit" class="btn btn-warning btn-sm" name="bookpkg">Book now</button>
                                            </div>

                                            <!-- </div>
                                        <div id="link2" class="tab-pane">
                                            <div class="text-center">
                                                <p>Please Make Deposit Payment to the Account Number Below:</p>
                                                <p>MAYBANK</p>
                                                <p>1602515483</p>
                                                <p>WANIEY BRIDAL WEDDING PLANNER</p>
                                            </div>

                                            <label class="form-label mt-3">
                                                Submit Receipt
                                            </label>
                                            <input type="file" class="form-control" placeholder="Payment Receipt" required="">
                                            <br>
                                            <div class="d-flex justify-content-end mt-3">
                                                <a class="btn btn-outline-warning btn-sm mr-2" href="packages.php"><span class="fas fa-angle-left mr-2"></span> Cancel</a>
                                                <button type="submit" class="btn btn-warning btn-sm" name="bookpkg">Book now</button>
                                            </div>
                                        </div> -->
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'Product not found.';
}
?>

<?php include('includes/footer.php') ?>

<script>
    $(document).ready(function() {
        // Attire card click handler
        $('.attire-card').on('click', function(e) {
            e.preventDefault();
            // Reset all checked icons
            $('.attire-card .checked-icon').addClass('d-none');
            // Show checked icon for the clicked card
            $(this).find('.checked-icon').removeClass('d-none');
            // Simulate radio button click
            $(this).find('input[type="radio"]').prop('checked', true).change();
        });

        // Dais card click handler
        $('.dais-card').on('click', function(e) {
            e.preventDefault();
            // Reset all checked icons
            $('.dais-card .checked-icon').addClass('d-none');
            // Show checked icon for the clicked card
            $(this).find('.checked-icon').removeClass('d-none');
            // Simulate radio button click
            $(this).find('input[type="radio"]').prop('checked', true).change();
        });
    });
</script>