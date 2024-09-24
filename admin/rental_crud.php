<?php
session_start();
include('../dbcon.php');

if (isset($_POST['addrental'])) {
  // Debugging output
  // echo "Rental Type: " . htmlspecialchars($_POST['rental_type']) . "<br>";
  // echo "Item ID: " . htmlspecialchars($_POST['item_id']) . "<br>";

  // Retrieve data from the form
  $cust_id = $_POST['cust_id'];
  $rental_type = $_POST['rental_type'];
  $item_id = $_POST['item_id'];
  $event_date = $_POST['event_date'];
  $event_loc = $_POST['event_loc'];
  $return_date = $_POST['return_date'];
  $total_payment = $_POST['total_payment'];
  $deposit = $_POST['deposit'];
  $deposit_status = $_POST['deposit_status'];
  $full_payment_date = $_POST['full_payment_date'];
  // $rental_status = $_POST['rental_status'];
  $remark = $_POST['remark'];
  $promo_code = $_POST['promo_code'];

  $payment_bal = $total_payment - $deposit;

  $insert_rental_query = "INSERT INTO rentals (cust_id, rental_type, item_id, event_date, event_loc, return_date, total_payment, deposit_status, payment_bal, full_payment_date, remark, promo_code) 
  VALUES ('$cust_id', '$rental_type', '$item_id', '$event_date', '$event_loc', '$return_date', '$total_payment', '$deposit_status', '$payment_bal', '$full_payment_date', '$remark', '$promo_code')";

  $insert_rental_result = mysqli_query($con, $insert_rental_query);

  if ($insert_rental_result) {
      echo "<script>
              alert('Rental added successfully.');
              window.location.href = 'rentals.php';
            </script>";
  } else {
      echo "<script>
              alert('Error adding rental. Please try again.');
              window.history.back();
            </script>";
  }
}



//updaterentals
if (isset($_POST['updaterental'])) {
    // Retrieve data from the form
    $rental_id = $_POST['rental_id']; // Make sure you have this in your form as a hidden field
    // $cust_id = $_POST['cust_id'];
    $rental_type = $_POST['rental_type'];
    $item_id = $_POST['item_id'];
    $event_date = $_POST['event_date'];
    $event_loc = $_POST['event_loc'];
    $return_date = $_POST['return_date'];
    $total_payment = $_POST['total_payment'];
    $deposit = $_POST['deposit'];
    $deposit_status = $_POST['deposit_status'];
    $payment_bal = $_POST['payment_bal'];
    $full_payment_date = $_POST['full_payment_date'];
    $full_payment_status = $_POST['full_payment_status'];
    $rental_status = $_POST['rental_status'];
    $remark = $_POST['remark'];
    $promo_code = $_POST['promo_code'];

    // Update the rentals table
    $update_rental_query = "UPDATE rentals 
                            SET rental_type = '$rental_type', 
                                item_id = '$item_id', 
                                event_date = '$event_date', 
                                event_loc = '$event_loc', 
                                return_date = '$return_date', 
                                total_payment = '$total_payment', 
                                deposit = '$deposit',
                                deposit_status = '$deposit_status', 
                                payment_bal = '$payment_bal', 
                                full_payment_date = '$full_payment_date', 
                                full_payment_status = '$full_payment_status', 
                                rental_status = '$rental_status', 
                                remark = '$remark', 
                                promo_code = '$promo_code' 
                            WHERE rental_id = '$rental_id'";

    $update_rental_result = mysqli_query($con, $update_rental_query);

    if ($update_rental_result) {
        // Rental successfully updated
        echo "<script>
                alert('Rental updated successfully.');
                window.location.href = 'rentals.php'; // Redirect to rentals page or any other suitable page
              </script>";
    } else {
        // Error updating rental
        echo "<script>
                alert('Error updating rental. Please try again.');
                window.history.back(); // Go back to the previous page
              </script>";
    }
}






// button delete is clicked
if (isset($_POST['deleterental'])) {
    $rental_id = mysqli_real_escape_string($con, $_POST['rental_id']);

    $query = mysqli_query($con, "DELETE FROM rentals WHERE rental_id = '$rental_id'");

    // if update expenses is successful
    if ($query) {
        echo "<script>
          alert('Rental Deleted Successfully.');
          document.location = 'rentals.php';
      </script>";
    } else {
        echo "<script>
          alert('Delete Unsuccessful.');
          document.location = 'rentals.php';
      </script>";
    }
}
