<?php
include('../dbcon.php');

if (isset($_POST['addimg'])) {
    $description = $_POST['description'];

    $img_file = $_FILES['img_file']['name'];

    $path = "uploads";

    $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    $query = "INSERT INTO gallery (img_file, description) VALUES ('$filename','$description')";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        move_uploaded_file($_FILES['img_file']['tmp_name'], $path . '/' . $filename);
        echo "<script>
        alert ('Image Added Successfully.');
        document.location = 'gallery.php';
        </script>";
    } else {
        echo "<script>
            alert('Add Image Unsuccessful.');
            document.location = 'gallery.php';
            </script>";
    }
}


//button delete image is clicked
if (isset($_POST['deleteimg'])) {
    $img_id = mysqli_real_escape_string($con, $_POST['img_id']);
    

    $img_query = "SELECT * FROM gallery WHERE img_id = '$img_id'";
    $img_query_run = mysqli_query($con, $img_query);
    $img_data = mysqli_fetch_array($img_query_run);
    $image = $img_data['img_file'];


    $delete_query = "DELETE FROM gallery WHERE img_id = '$img_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    // if update img is successful
    if ($delete_query_run) {

        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        echo "<script>
            alert('Image Deleted Successfully.');
            document.location = 'gallery.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'gallery.php';
        </script>";
    }
}
