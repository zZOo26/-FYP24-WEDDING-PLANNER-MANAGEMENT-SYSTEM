<?php
session_start();
include('../dbcon.php');

//addslot
if (isset($_POST['addslot'])) {
  $slot = $_POST['slot'];
  $start_time = $_POST['start_time'];

  $query = "INSERT INTO event_slot (slot, start_time) VALUES ('$slot', '$start_time')";
  $query_run = mysqli_query($con, $query);

  if ($query_run) {
    // slot successfully added
    echo "<script>
                alert('Slot added successfully.');
                window.location.href = 'pkg_bookings.php'; // Redirect to bookings page or any other suitable page
              </script>";
  } else {
    // Error inserting slot
    echo "<script>
                alert('Error adding slot. Please try again.');
                window.history.back(); // Go back to the previous page
              </script>";
  }

}


// Check if the addbooking form is submitted
if (isset($_POST['addbooking'])) {
  // Retrieve data from the form
  $cust_id = $_POST['cust_id'];
  $pkg_id = $_POST['pkg_id'];
  $event_date = $_POST['event_date'];
  $event_slot = $_POST['event_slot'];
  $event_loc = $_POST['event_loc'];
  $bride_name = $_POST['bride_name'];
  $groom_name = $_POST['groom_name'];
  $makeup_artist = isset($_POST['makeup_artist']) ? $_POST['makeup_artist'] : NULL;
  $event_host = isset($_POST['event_host']) ? $_POST['event_host'] : NULL;
  $photographer = isset($_POST['photographer']) ? $_POST['photographer'] : NULL;
  $attire_id = isset($_POST['attire_id']) ? $_POST['attire_id'] : NULL;
  $dais_id = isset($_POST['dais_id']) ? $_POST['dais_id'] : NULL;
  $total_payment = $_POST['total_payment'];
  $deposit = $_POST['deposit'];
  $deposit_status = $_POST['deposit_status'];
  $full_payment_date = $_POST['full_payment_date'];
  $booking_status = $_POST['booking_status'];
  $remarks = $_POST['remarks'];
  $promo_code = $_POST['promo_code'];

  $payment_bal = $total_payment - $deposit;

  // Prepare the SQL statement
  $stmt = $con->prepare("INSERT INTO package_bookings (cust_id, pkg_id, event_date, slot_id, event_loc, bride_name, groom_name, total_payment, deposit_status, payment_bal, full_payment_date, booking_status, remarks, promo_code, makeup_artist, event_host, photographer, attire_id, dais_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  // Bind parameters
  $stmt->bind_param("iisssssdsdsssssssss", $cust_id, $pkg_id, $event_date, $event_slot, $event_loc, $bride_name, $groom_name, $total_payment, $deposit_status, $payment_bal, $full_payment_date, $booking_status, $remarks, $promo_code, $makeup_artist, $event_host, $photographer, $attire_id, $dais_id);

  // Execute the statement
  if ($stmt->execute()) {
    // Booking successfully added
    echo "<script>
                alert('Booking added successfully.');
                window.location.href = 'pkg_bookings.php'; // Redirect to bookings page or any other suitable page
              </script>";
  } else {
    // Error inserting booking
    echo "<script>
                alert('Error adding booking. Please try again.');
                window.history.back(); // Go back to the previous page
              </script>";
  }

  // Close the statement
  $stmt->close();
}




//updatebookings
if (isset($_POST['updatebooking'])) {
    // Assuming $con is your mysqli connection object

    // Sanitize and assign variables
    $booking_id = mysqli_real_escape_string($con, $_POST['booking_id']);
    $pkg_id = mysqli_real_escape_string($con, $_POST['pkg_id']);
    $event_date = mysqli_real_escape_string($con, $_POST['event_date']);
    $event_slot = mysqli_real_escape_string($con, $_POST['event_slot']);
    $event_loc = mysqli_real_escape_string($con, $_POST['event_loc']);
    $bride_name = mysqli_real_escape_string($con, $_POST['bride_name']);
    $groom_name = mysqli_real_escape_string($con, $_POST['groom_name']);
    $makeup_artist = isset($_POST['makeup_artist']) ? mysqli_real_escape_string($con, $_POST['makeup_artist']) : NULL;
    $event_host = isset($_POST['event_host']) ? mysqli_real_escape_string($con, $_POST['event_host']) : NULL;
    $photographer = isset($_POST['photographer']) ? mysqli_real_escape_string($con, $_POST['photographer']) : NULL;
    $attire_id = isset($_POST['attire_id']) ? mysqli_real_escape_string($con, $_POST['attire_id']) : NULL;
    $dais_id = isset($_POST['dais_id']) ? mysqli_real_escape_string($con, $_POST['dais_id']) : NULL;
    $total_payment = mysqli_real_escape_string($con, $_POST['total_payment']);
    $payment_bal = mysqli_real_escape_string($con, $_POST['payment_bal']);
    $deposit_status = mysqli_real_escape_string($con, $_POST['deposit_status']);
    $full_payment_date = mysqli_real_escape_string($con, $_POST['full_payment_date']);
    $full_payment_status = mysqli_real_escape_string($con, $_POST['full_payment_status']);
    $booking_status = mysqli_real_escape_string($con, $_POST['booking_status']);
    $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
    $promo_code = mysqli_real_escape_string($con, $_POST['promo_code']);

    // Prepare the update query
    $update_booking_query = "UPDATE package_bookings 
                             SET pkg_id = ?,
                                 event_date = ?,
                                 event_loc = ?,
                                 slot_id = ?,
                                 bride_name = ?,
                                 groom_name = ?,
                                 makeup_artist = ?,
                                 event_host = ?,
                                 photographer = ?,
                                 attire_id = ?,
                                 dais_id = ?,
                                 total_payment = ?,
                                 deposit_status = ?,
                                 payment_bal = ?,
                                 full_payment_date = ?,
                                 full_payment_status = ?,
                                 booking_status = ?,
                                 remarks = ?,
                                 promo_code = ?
                             WHERE booking_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($con, $update_booking_query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'ississiiiiididsiissi', $pkg_id, $event_date, $event_loc, $event_slot, $bride_name, $groom_name, $makeup_artist, $event_host, $photographer, $attire_id, $dais_id, $total_payment, $deposit_status, $payment_bal, $full_payment_date, $full_payment_status, $booking_status, $remarks, $promo_code, $booking_id);

    // Execute the statement
    $update_booking_result = mysqli_stmt_execute($stmt);

    if ($update_booking_result) {
        // Booking successfully updated
        echo "<script>
                alert('Booking updated successfully.');
                window.location.href = 'pkg_bookings.php'; // Redirect to bookings page or any other suitable page
              </script>";
    } else {
        // Error updating booking
        echo "<script>
                alert('Error updating booking. Please try again.');
                window.history.back(); // Go back to the previous page
              </script>";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}



// button delete is clicked
if (isset($_POST['deletebooking'])) {
  $booking_id = mysqli_real_escape_string($con, $_POST['booking_id']);

  $query = mysqli_query($con, "DELETE FROM package_bookings WHERE booking_id = '$booking_id'");

  // if update expenses is successful
  if ($query) {
    echo "<script>
        alert('Booking Deleted Successfully.');
        document.location = 'pkg_bookings.php';
    </script>";
  } else {
    echo "<script>
        alert('Delete Unsuccessful.');
        document.location = 'pkg_bookings.php';
    </script>";
  }
}
?>
