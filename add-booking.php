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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bookpkg'])) {
    // Capture all form data
    $booking_date = date('Y-m-d H:i:s');
    $slot_id = $_POST['eventslot']; // Assuming slot_id is passed via POST
    $event_date = $_POST['eventdate'];
    $pkg_id = $_POST['pkg_id'];
    $subtotal_after_discount = $_POST['subtotal_after_discount'];
    $pax = $_POST['pax'];
    $bride_name = $_POST['bride_name'];
    $groom_name = $_POST['groom_name'];
    $event_location = $_POST['streetAddress'];
    $deposit_amount = $_POST['deposit_amount'];
    // $total_payment = $_POST['subtotal_after_discount'];
    $promo_code = $_POST['promo_code'];
    $deposit_status = 0;

    $payment_bal = $subtotal_after_discount - $deposit_amount;

    // Calculate full payment date (2 weeks before event date)
    $full_payment_date = date('Y-m-d', strtotime($event_date . ' - 14 days'));

    // Prepare SQL statement
    $insert_rental_query = "INSERT INTO package_bookings (cust_id, event_date, event_loc, slot_id, pkg_id, pax, total_payment, deposit_status, payment_bal, full_payment_date, groom_name, bride_name, promo_code)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the statement
    $stmt = mysqli_prepare($con, $insert_rental_query);
    if ($stmt === false) {
        // Handle preparation error
        die('MySQL prepare error: ' . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "issiiididssss", $_SESSION['cust_id'], $event_date, $event_location, $slot_id, $pkg_id, $pax, $subtotal_after_discount, $deposit_status, $payment_bal, $full_payment_date, $groom_name, $bride_name, $promo_code);

    if (mysqli_stmt_execute($stmt)) {
        // Rental record inserted successfully
        $booking_id = mysqli_insert_id($con);
        mysqli_stmt_close($stmt);

        echo "<script>
        alert('Booking successful. Thank you for choosing us.');
        setTimeout(function() {
            window.location.href = 'booking_summary.php?booking_id=$booking_id';
        }, 500); // Adjust the delay as needed (2000 ms = 2 seconds)
        </script>";
        exit();
    } else {
        // Insertion failed
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "Form submission error or not authorized.";
}
?>
