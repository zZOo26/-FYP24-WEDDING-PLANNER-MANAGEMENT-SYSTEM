<?php
include('../dbcon.php');

//button add dais is clicked
if (isset($_POST['adddais'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $dais_size = $_POST['dais_size'];
    $np = $_POST['normal_price'];
    $dp = $_POST['deposit'];
    $items = $_POST['items'];
    $dais_desc = $_POST['dais_desc'];
    

    $img_file = $_FILES['image']['name'];

    $path = "uploads";

    $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;



        $query = "INSERT INTO bridal_dais (image, name, category, dais_size, normal_price, deposit, items, dais_desc) 
                VALUES ('$filename','$name','$category','$dais_size','$np', '$dp','$items', '$dais_desc')";

        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);
            echo "<script>
        alert ('Dais Added Successfully.');
        document.location = 'dais.php';
        </script>";
        } else {
            echo "<script>
            alert('Add Dais Unsuccessful.');
            document.location = 'dais.php';
            </script>";
        }
    
}

// button edit dais is clicked
if (isset($_POST['editdais'])) {
    $dais_id = $_POST['dais_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $dais_size = $_POST['dais_size'];
    $np = $_POST['normal_price'];
    $dp = $_POST['deposit'];
    $items = $_POST['items'];
    $dais_desc = $_POST['dais_desc'];
    

    $new_img = $_FILES['image']['name'];
    $old_img = $_POST['old_img'];

    $path = "uploads";

    if ($new_img != "") {
        // $update_filename = $new_img;
        $image_ext = pathinfo($new_img, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_ext;
    } else {
        $update_filename = $old_img;
    }

    $update_query = "UPDATE bridal_dais SET image='$update_filename',name='$name',
    category='$category',dais_size='$dais_size', normal_price='$np', 
    deposit='$dp', items='$items', dais_desc='$dais_desc' WHERE dais_id='$dais_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        if ($_FILES['image']['name'] != "") {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $update_filename);

            if (file_exists("uploads/" . $old_img)) {
                unlink("uploads/" . $old_img);
            }
        } else {
            $update_filename = $old_img;
        }
        echo "<script>
                alert ('Dais Updated Successfully.');
                document.location = 'dais.php';
                </script>";
    } else {
        echo "<script>
                alert('Update Dais Unsuccessful.');
                document.location = 'dais.php';
                </script>";
    }
}


//button delete dais is clicked
if (isset($_POST['deletedais'])) {
    $dais_id = mysqli_real_escape_string($con, $_POST['dais_id']);

    $dais_query = "SELECT * FROM bridal_dais WHERE dais_id = '$dais_id'";
    $dais_query_run = mysqli_query($con, $dais_query);
    $dais_data = mysqli_fetch_array($dais_query_run);
    $image = $dais_data['image'];


    $delete_query = "DELETE FROM bridal_dais WHERE dais_id = '$dais_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    // if update dais is successful
    if ($delete_query_run) {

        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        echo "<script>
            alert('Dais Deleted Successfully.');
            document.location = 'dais.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'dais.php';
        </script>";
    }
}
