<?php
session_start(); // Start the session

include('dbcon.php');
include('functions.php');



if (isset($_POST['bookapp'])) {
    // Check if cust_id is set in the session
    if (isset($_SESSION['cust_id'])) {
        $cust_id = $_SESSION['cust_id'];

        $purpose = $_POST['purpose'];
        $location = $_POST['location'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        // Insert into appointments table
        $insert_query = "INSERT INTO appointments (purpose, location, date, time, cust_id) VALUES ('$purpose','$location','$date','$time', '$cust_id')";
        $insert_result = mysqli_query($con, $insert_query);

        if ($insert_result) {

            // Appointment booked successfully, you can redirect or show a success message
            echo "<script>
                        alert('Appointment booked successfully.');
                        document.location = 'index.php';
                        </script>";
        } else {
            // Appointment booking failed
            echo "<script>
                    alert('Error booking appointment.');
                    document.location = 'index.php';
                    </script>";
        }
    } else {
        echo "<script>
                    alert('Error: cust_id is not set in the session');
                    document.location = 'index.php';
                    </script>";
    }
}
