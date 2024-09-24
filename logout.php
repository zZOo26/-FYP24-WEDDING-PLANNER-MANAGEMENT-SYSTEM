<?php

session_start();

// Check if the 'cust_id' session variable is set
if (isset($_SESSION['cust_id'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    die;
} else {
    // Handle cases where the user is not logged in
    //echo "Logout unsuccessful";
}
?>
