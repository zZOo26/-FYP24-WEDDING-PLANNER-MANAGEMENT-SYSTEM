<?php

include('../dbcon.php');



// Function to update promotion statuses
function update_promo_statuses($con)
{
    $current_date = date('Y-m-d H:i:s');

    // Update status to 'ongoing' where start_date has passed and end_date has not passed yet
    $sql_ongoing = "UPDATE promotions SET promo_status='Ongoing' WHERE start_date <= '$current_date' AND end_date > '$current_date' AND promo_status != 'Ongoing'";
    mysqli_query($con, $sql_ongoing);

    // Update status to 'ended' where end_date has passed
    $sql_ended = "UPDATE promotions SET promo_status='Ended' WHERE end_date <= '$current_date' AND promo_status != 'Ended'";
    mysqli_query($con, $sql_ended);

    // Update status to 'upcoming' where start_date is in the future
    $sql_upcoming = "UPDATE promotions SET promo_status='Upcoming' WHERE start_date > '$current_date' AND promo_status != 'Upcoming'";
    mysqli_query($con, $sql_upcoming);
}


// Ensure the script only updates statuses when the appropriate GET request is received
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['update_status'])) {
    // Call the function to update statuses
    update_promo_statuses($con);
    echo "<script>
    alert ('Status Updated.');
    document.location = 'promotion.php';
    </script>";
    exit;
}


// button addctg is clicked
if (isset($_POST['addctg'])) {
    $ctg_name = $_POST['ctg_name'];

    $query = "INSERT INTO promotion_category (ctg_name) VALUES ('$ctg_name')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        echo "<script>
        alert ('Category Added Successfully.');
        document.location = 'promotion.php';
        </script>";
    } else {
        echo "<script>
            alert('Add Category Unsuccessful.');
            document.location = 'promotion.php';
            </script>";
    }
}

//button addpromo is clicked
else if (isset($_POST['addpromo'])) {
    $promo_code = $_POST['promo_code'];
    $amount_off = $_POST['amount_off'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $promo_ctg_id = $_POST['promo_ctg_id'];
    $promo_desc = $_POST['promo_desc'];

    // Convert start and end dates to timestamps
    // $start_timestamp = strtotime($start_date);
    // $end_timestamp = strtotime($end_date);
    // $current_timestamp = time();

    // // Define the logic for each status
    // if ($current_timestamp < $start_timestamp) {
    //     $status = "Upcoming";
    // } elseif ($current_timestamp >= $start_timestamp && $current_timestamp <= $end_timestamp) {
    //     $status = "Ongoing";
    // } else {
    //     $status = "Ended";
    // }


    $img_file = $_FILES['poster']['name'];
    $path = "uploads";

    $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    $check_category_query = "SELECT * FROM promotion_category WHERE promo_ctg_id = '$promo_ctg_id'";
    $check_category_query_run = mysqli_query($con, $check_category_query);

    if (mysqli_num_rows($check_category_query_run) > 0) {

        $query = "INSERT INTO promotions (poster, promo_code,amount_off, start_date, end_date, promo_desc, promo_ctg_id) 
                VALUES ('$filename','$promo_code', '$amount_off','$start_date','$end_date','$promo_desc','$promo_ctg_id')";

        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            move_uploaded_file($_FILES['poster']['tmp_name'], $path . '/' . $filename);
            echo "<script>
            alert ('promo Added Successfully.');
            document.location = 'promotion.php';
            </script>";
        } else {
            echo "<script>
                alert('Add promo Unsuccessful.');
                document.location = 'promotion.php';
                </script>";
        }
    } else {
        echo "<script>
        alert('Selected resource category does not exist.');
        document.location = 'promotion.php';
        </script>";
    }
}


// button edit promo is clicked
else if (isset($_POST['editpromo'])) {
    $promo_code = $_POST['promo_code'];
    $amount_off = $_POST['amount_off'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $promo_ctg_id = $_POST['promo_ctg_id'];
    $promo_desc = $_POST['promo_desc'];
    $promo_id = $_POST['promo_id'];

    $new_img = $_FILES['poster']['name'];
    $old_img = $_POST['old_img'];

    $path = "uploads";

    if ($new_img != "") {
        // $update_filename = $new_img;
        $image_ext = pathinfo($new_img, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_ext;
    } else {
        $update_filename = $old_img;
    }

    $update_query = "UPDATE promotions 
    SET poster='$update_filename',
    promo_code='$promo_code', 
    amount_off='$amount_off',
    start_date='$start_date', 
    end_date='$end_date', 
    promo_desc='$promo_desc', 
    promo_ctg_id = '$promo_ctg_id' 
    WHERE promo_id = '$promo_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        if ($_FILES['poster']['name'] != "") {
            move_uploaded_file($_FILES['poster']['tmp_name'], $path . '/' . $update_filename);

            if (file_exists("uploads/" . $old_img)) {
                unlink("uploads/" . $old_img);
            }
        } else {
            $update_filename = $old_img;
        }

        update_promo_statuses($con);

        echo "<script>
                alert ('Promotion Updated Successfully.');
                document.location = 'promotion.php';
                </script>";
    } else {
        echo "<script>
                alert('Update Promotion Unsuccessful.');
                document.location = 'promotion.php';
                </script>";
    }
}

//button delete promo is clicked
else if (isset($_POST['deletepromo'])) {
    $promo_id = mysqli_real_escape_string($con, $_POST['promo_id']);

    $promo_query = "SELECT * FROM promotions WHERE promo_id = '$promo_id'";
    $promo_query_run = mysqli_query($con, $promo_query);
    $promo_data = mysqli_fetch_array($promo_query_run);
    $image = $promo_data['poster'];


    $delete_query = "DELETE FROM promotions WHERE promo_id = '$promo_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    // if update promo is successful
    if ($delete_query_run) {

        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        echo "<script>
            alert('Promotion Deleted Successfully.');
            document.location = 'promotion.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'promotion.php';
        </script>";
    }
}
