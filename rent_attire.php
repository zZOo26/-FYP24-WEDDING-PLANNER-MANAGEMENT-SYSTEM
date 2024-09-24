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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_order'])) {
    // Capture all form data
    $rental_date = date('Y-m-d H:i:s');
    $rental_type = 'attire'; // Assuming this is for attire rental
    $item_id = $_GET['attire_id']; // Assuming attire_id is passed via GET
    $event_date = $_POST['eventdate'];
    $event_location = $_POST['streetAddress'] . ', ' . $_POST['city'] . ', ' . $_POST['postcode'] . ' ' . $_POST['state'];
    $return_date = date('Y-m-d', strtotime($event_date . ' + 3 days'));
    $deposit_amount = $_POST['deposit_amount'];
    $total_payment = $_POST['total_price_after_discount'];
    $promo_code = $_POST['promo_code'];
    $deposit_status = 0;

    $payment_bal = $total_payment - $deposit_amount;

    // Calculate full payment date (2 weeks before event date)
    $full_payment_date = date('Y-m-d', strtotime($event_date . ' - 14 days'));

    // Prepare SQL statement
    $insert_rental_query = "INSERT INTO rentals (cust_id, rental_type, item_id, event_date, event_loc, return_date, deposit, deposit_status, payment_bal, full_payment_date,total_payment, promo_code)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the statement
    $stmt = mysqli_prepare($con, $insert_rental_query);
    mysqli_stmt_bind_param($stmt, "isisssdidsds", $_SESSION['cust_id'], $rental_type, $item_id, $event_date, $event_location, $return_date, $deposit_amount, $deposit_status, $payment_bal, $full_payment_date,$total_payment, $promo_code);

    if (mysqli_stmt_execute($stmt)) {
        // Rental record inserted successfully
        $rental_id = mysqli_insert_id($con);
        mysqli_stmt_close($stmt);

        echo "<script>
        alert('Booking successful. Thank you for choosing us.');
        setTimeout(function() {
            window.location.href = 'rental_confirmation.php?rental_id=$rental_id';
        }, 500); // Adjust the delay as needed (2000 ms = 2 seconds)
        </script>";
        exit();
    } else {
        // Insertion failed
        echo "Error: " . mysqli_error($con);
    }
}
?>
