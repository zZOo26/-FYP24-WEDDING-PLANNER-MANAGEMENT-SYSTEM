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

$cust_id = $_SESSION['cust_id'];

if (isset($_POST['submit_feedback_rental'])) {
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];
    $rental_id = $_POST['rental_id'];

    $query = "INSERT INTO feedback (cust_id, feedback, rating, rental_id) VALUES (?, ?, ?, ?) LIMIT 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("isii", $cust_id, $feedback, $rating, $rental_id);

    if ($stmt->execute()) {
        echo "<script>
        alert('Feedback submitted successfully.');
        window.history.back();
        </script>";
    } else {
        echo "<script>
        alert('Error adding feedback.');
        window.history.back();
        </script>";
    }

    die;
}


if (isset($_POST['submit_feedback_booking'])) {
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];
    $booking_id = $_POST['booking_id'];

    // Check if booking_id already exists in the feedback table
    $check_query = "SELECT COUNT(*) FROM feedback WHERE booking_id = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("i", $booking_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        echo "<script>
        alert('Feedback for this booking ID already exists.');
        window.history.back();
        </script>";
    } else {
        $query = "INSERT INTO feedback (cust_id, feedback, rating, booking_id) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("isii", $cust_id, $feedback, $rating, $booking_id);

        if ($stmt->execute()) {
            echo "<script>
            alert('Feedback submitted successfully.');
            window.history.back();
            </script>";
        } else {
            echo "<script>
            alert('Error adding feedback.');
            window.history.back();
            </script>";
        }

        $stmt->close();
    }

    die;
}
