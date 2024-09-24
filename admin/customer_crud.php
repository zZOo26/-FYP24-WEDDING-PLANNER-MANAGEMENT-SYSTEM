<?php
include('../dbcon.php');
//button add category is clicked
if (isset($_POST['addcustomer'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phoneNo = $_POST['phoneNo'];
    $email = $_POST['email'];
    $query = mysqli_query($con, "INSERT INTO customers (firstname, lastname, phoneNo, email) VALUES ('$firstname','$lastname','$phoneNo','$email')");

    //if add category success
    if ($query) {
        echo "<script>
        alert ('New Customer Added Successfully.');
        document.location = 'customer.php';
        </script>";
    } else {
        echo "<script>
            alert('Add Promotion Category Unsuccessful.');
            document.location = 'customer.php';
            </script>";
    }
}


//button delete category is clicked
if (isset($_POST['deletecustomer'])) {
    $cust_id = mysqli_real_escape_string($con, $_POST['cust_id']);

    $query = mysqli_query($con, "DELETE FROM customers WHERE cust_id = '$cust_id'");

    // if update category is successful
    if ($query) {
        echo "<script>
            alert('Customer Deleted Successfully.');
            document.location = 'customer.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'customer.php';
        </script>";
    }
}