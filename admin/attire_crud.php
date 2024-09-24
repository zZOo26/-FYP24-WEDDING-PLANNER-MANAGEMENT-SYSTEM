<?php
include('../dbcon.php');

//button add attire is clicked
if (isset($_POST['addattire'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $min_size = $_POST['min_size'];
    $max_size = $_POST['max_size'];
    $np = $_POST['normal_price'];
    $dp = $_POST['deposit'];
    $accessories = $_POST['accessories'];
    $attire_desc = $_POST['attire_desc'];
    

    $img_file = $_FILES['image']['name'];

    $path = "uploads";

    $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    $query = "INSERT INTO bridal_attire (image, name, category, color, min_size, max_size, normal_price, deposit, accessories, attire_desc) 
                VALUES ('$filename','$name','$category','$color', '$min_size','$max_size','$np', '$dp','$accessories', '$attire_desc')";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);
        echo "<script>
            alert ('New Attire Added Successfully.');
            document.location = 'attire.php';
            </script>";
    } else {
        echo "<script>
            alert('Add Attire Unsuccessful.');
            document.location = 'attire.php';
            </script>";
    }
}

// button edit attire is clicked
if (isset($_POST['editattire'])) {
    $attire_id = $_POST['attire_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $min_size = $_POST['min_size'];
    $max_size = $_POST['max_size'];
    $np = $_POST['normal_price'];
    $dp = $_POST['deposit'];
    $accessories = $_POST['accessories'];
    $attire_desc = $_POST['attire_desc'];
    


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

    $update_query = "UPDATE bridal_attire SET image='$update_filename',name='$name',
    category='$category',min_size='$min_size',max_size='$max_size', normal_price='$np', 
    deposit='$dp', accessories='$accessories', attire_desc='$attire_desc' WHERE attire_id='$attire_id'";

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
                alert ('Attire Updated Successfully.');
                document.location = 'attire.php';
                </script>";
    } else {
        echo "<script>
                alert('Update Attire Unsuccessful.');
                document.location = 'attire.php';
                </script>";
    }
}


//button delete attire is clicked
if (isset($_POST['deleteattire'])) {
    $attire_id = mysqli_real_escape_string($con, $_POST['attire_id']);

    $attire_query = "SELECT * FROM bridal_attire WHERE attire_id = '$attire_id'";
    $attire_query_run = mysqli_query($con, $attire_query);
    $attire_data = mysqli_fetch_array($attire_query_run);
    $image = $attire_data['image'];


    $delete_query = "DELETE FROM bridal_attire WHERE attire_id = '$attire_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    // if update attire is successful
    if ($delete_query_run) {

        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        echo "<script>
            alert('attire Deleted Successfully.');
            document.location = 'attire.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'attire.php';
        </script>";
    }
}
