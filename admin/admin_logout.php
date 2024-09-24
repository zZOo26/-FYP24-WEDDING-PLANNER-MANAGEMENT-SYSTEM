<?php

session_start();

// Check if the 'admin_id' session variable is set
if (isset($_SESSION['admin_id'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: admin_login.php");
    die;
} else {
    // If the 'admin_id' session variable is not set, redirect to the login page
    header("Location: admin_login.php");
    die;
}
