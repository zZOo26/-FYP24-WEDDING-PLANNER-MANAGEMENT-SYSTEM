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
                                    <br>
                                    <span class="text-dark">Notes: </span>
                                    <p class="text-secondary"> Please set an appointment after booking is made to choose bridal attire or dais for your package if included.</p>
                                    <br>


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
                                                <a class="btn btn-outline-warning btn-sm me-2" href="packages.php"><span class="fas fa-angle-left "></span> Cancel</a>
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