<?php 
include('dbcon.php');


if (isset($_POST['editprofile'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phoneNo = $_POST['phoneNo'];
    $email = $_POST['email'];
    $bank_name = $_POST['bank_name'];
    $acc_no = $_POST['acc_no'];
    $cust_id = $_POST['cust_id'];

    $new_img = $_FILES['profile_img']['name'];
    $old_img = $_POST['old_img'];

    $path = "uploads";

    if ($new_img != "") {
        // $update_filename = $new_img;
        $image_ext = pathinfo($new_img, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_ext;
    } else {
        $update_filename = $old_img;
    }

    $update_query = "UPDATE customers SET profile_img='$update_filename',firstname='$firstname', lastname='$lastname',
        phoneNo='$phoneNo', email='$email', bank_name='$bank_name', acc_no = '$acc_no' WHERE cust_id = '$cust_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        if ($_FILES['profile_img']['name'] != "") {
            move_uploaded_file($_FILES['profile_img']['tmp_name'], $path . '/' . $update_filename);

            if (file_exists("uploads/" . $old_img)) {
                unlink("uploads/" . $old_img);
            }
        } else {
            $update_filename = $old_img;
        }
        echo "<script>
                    alert ('Profile Updated Successfully.');
                    document.location = 'myprofile.php';
                    </script>";
    } else {
        echo "<script>
                    alert('Update Profile Unsuccessful.');
                    document.location = 'myprofile.php';
                    </script>";
    }
}



// delete profile
if (isset($_POST['deleteacc'])) {
    $cust_id = mysqli_real_escape_string($con, $_POST['cust_id']);
    
    $cust_query = "SELECT * FROM customers WHERE cust_id = '$cust_id'";
    $cust_query_run = mysqli_query($con, $cust_query);
    $cust_data = mysqli_fetch_array($cust_query_run);
    $image = $cust_data['profile_img'];


    $delete_query = "DELETE FROM customers WHERE cust_id = '$cust_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    // if update cust is successful
    if ($delete_query_run) {

        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        // Destroy session to log the user out
        session_start();
        session_unset();
        session_destroy();

        echo "<script>
            alert('cust Deleted Successfully.');
            document.location = 'login.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'myprofile.php';
        </script>";
    }

}


?>


