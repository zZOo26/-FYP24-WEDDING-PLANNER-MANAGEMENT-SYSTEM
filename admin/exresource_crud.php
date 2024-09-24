<?php
include('../dbcon.php');


// button addctg is clicked
if (isset($_POST['addctg'])) {
    $ctg_name = $_POST['ctg_name'];

    $query = "INSERT INTO resource_category (ctg_name) VALUES ('$ctg_name')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        echo "<script>
        alert ('Category Added Successfully.');
        document.location = 'external_resource.php';
        </script>";
    } else {
        echo "<script>
            alert('Add Category Unsuccessful.');
            document.location = 'external_resource.php';
            </script>";
    }

}

//button add resource is clicked
if (isset($_POST['addresource'])) {
    $fullname = $_POST['fullname'];
    $phoneNo = $_POST['phoneNo'];
    $email = $_POST['email'];
    $bank_name = $_POST['bank_name'];
    $acc_no = $_POST['acc_no'];
    $remark = $_POST['remark'];
    $resource_ctg_id = $_POST['resource_ctg_id'];

    $check_category_query = mysqli_query($con, "SELECT * FROM resource_category WHERE resource_ctg_id = '$resource_ctg_id'");

    if (mysqli_num_rows($check_category_query) > 0) {
        // Insert data into ex_resources table
        $query = "INSERT INTO ex_resources (fullname, phoneNo, email, bank_name, acc_no, remark, resource_ctg_id) 
                    VALUES ('$fullname','$phoneNo','$email','$bank_name','$acc_no','$remark','$resource_ctg_id')";

        $query_run = mysqli_query($con, $query);

        // Check if the query was successful
        if ($query_run) {
            echo "<script>
            alert ('Data Added Successfully.');
            document.location = 'external_resource.php';
            </script>";
        } else {
            echo "<script>
                alert('Add Data Unsuccessful.');
                document.location = 'external_resource.php';
                </script>";
        }
    } else {
        echo "<script>
            alert('Selected resource category does not exist.');
            </script>";
    }
}

// button edit resource is clicked
if (isset($_POST['editresource'])) {
    $fullname = $_POST['fullname'];
    $phoneNo = $_POST['phoneNo'];
    $email = $_POST['email'];
    $bank_name = $_POST['bank_name'];
    $acc_no = $_POST['acc_no'];
    $remark = $_POST['remark'];
    $resource_id = $_POST['resource_id'];
    $resource_ctg_id = $_POST['resource_ctg_id'];

    $query = mysqli_query($con, "UPDATE ex_resources SET fullname = '$fullname', phoneNo = '$phoneNo', email = '$email', 
    bank_name = '$bank_name', acc_no = '$acc_no', remark = '$remark', resource_ctg_id = '$resource_ctg_id'  WHERE resource_id = '$resource_id'");

    // if update resource is successful
    if ($query) {
        echo "<script>
            alert('Data Updated Successfully.');
            document.location = 'external_resource.php';
        </script>";
    } else {
        echo "<script>
            alert('Update Data Unsuccessful.');
            document.location = 'external_resource.php';
        </script>";
    }
}


//button delete resource is clicked
if (isset($_POST['deleteresource'])) {
    $resource_id = mysqli_real_escape_string($con, $_POST['resource_id']);

    $query = mysqli_query($con, "DELETE FROM ex_resources WHERE resource_id = '$resource_id'");

    // if update resource is successful
    if ($query) {
        echo "<script>
            alert('Data Deleted Successfully.');
            document.location = 'external_resource.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'external_resource.php';
        </script>";
    }
}


?>