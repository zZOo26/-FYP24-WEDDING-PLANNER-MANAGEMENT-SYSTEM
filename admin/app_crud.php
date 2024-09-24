<?php
include('../dbcon.php');


if (isset($_POST['addapp'])) {
    $cust_id = $_POST['cust_id'];
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
                        document.location = 'appointment.php';
                        </script>";
    } else {
        // Appointment booking failed
        echo "<script>
                    alert('Error booking appointment.');
                    document.location = 'appointment.php';
                    </script>";
    }
}

// button edit app_slot is clicked
if (isset($_POST['editapp'])) {
    $app_id = $_POST['app_id'];
    $purpose = $_POST['purpose'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = $_POST['status'];


    // Insert into appointments table
    $update_query = "UPDATE appointments
                        SET 
                        purpose = '$purpose',
                        location = '$location',
                        date = '$date',
                        time = '$time',
                        status = '$status'
                        WHERE app_id = '$app_id'";

    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {

        // Appointment booked successfully, you can redirect or show a success message
        echo "<script>
                        alert('Appointment updated successfully.');
                        document.location = 'appointment.php';
                        </script>";
    } else {
        // Appointment booking failed
        echo "<script>
                    alert('Error booking appointment.');
                    document.location = 'appointment.php';
                    </script>";
    }
}

//button delete app is clicked
if (isset($_POST['deleteapp'])) {
    $app_id = mysqli_real_escape_string($con, $_POST['app_id']);

    $query = mysqli_query($con, "DELETE FROM appointments WHERE app_id = '$app_id'");

    // if update app is successful
    if ($query) {
        echo "<script>
            alert('Appointment Deleted Successfully.');
            document.location = 'appointment.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'appointment.php';
        </script>";
    }
}
