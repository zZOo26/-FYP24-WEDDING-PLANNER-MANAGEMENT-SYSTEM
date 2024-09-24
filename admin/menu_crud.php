<?php
include('../dbcon.php');


// button addctg is clicked
if (isset($_POST['addctg'])) {
    $ctg_name = $_POST['ctg_name'];

    $query = "INSERT INTO menu_category (ctg_name) VALUES ('$ctg_name')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        echo "<script>
        alert ('Category Added Successfully.');
        document.location = 'menu.php';
        </script>";
    } else {
        echo "<script>
            alert('Add Category Unsuccessful.');
            document.location = 'menu.php';
            </script>";
    }
}

//button addmenu is clicked
else if (isset($_POST['addmenu'])) {
    $menu_name = $_POST['menu_name'];
    $price_per_pax = $_POST['price_per_pax'];
    $menu_ctg_id = $_POST['menu_ctg_id'];
    $description = $_POST['description'];

    $img_file = $_FILES['menu_img']['name'];
    $path = "uploads";

    $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    $check_category_query = "SELECT * FROM menu_category WHERE menu_ctg_id = '$menu_ctg_id'";
    $check_category_query_run = mysqli_query($con, $check_category_query);

    if (mysqli_num_rows($check_category_query_run) > 0) {

        $query = "INSERT INTO menus (menu_img, menu_name, price_per_pax, menu_ctg_id, description) 
                VALUES ('$filename','$menu_name','$price_per_pax','$menu_ctg_id','$description')";

        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            move_uploaded_file($_FILES['menu_img']['tmp_name'], $path . '/' . $filename);
            echo "<script>
            alert ('Menu Added Successfully.');
            document.location = 'menu.php';
            </script>";
        } else {
            echo "<script>
                alert('Add Menu Unsuccessful.');
                document.location = 'menu.php';
                </script>";
        }
    } else {
        echo "<script>
        alert('Selected resource category does not exist.');
        document.location = 'external_resource.php';
        </script>";
    }
}


// button edit menu is clicked
else if (isset($_POST['editmenu'])) {
    $menu_name = $_POST['menu_name'];
    $price_per_pax = $_POST['price_per_pax'];
    $description = $_POST['description'];
    $menu_id = $_POST['menu_id'];
    $menu_ctg_id = $_POST['menu_ctg_id'];

    $new_img = $_FILES['menu_img']['name'];
    $old_img = $_POST['old_img'];

    $path = "uploads";

    if ($new_img != "") {
        // $update_filename = $new_img;
        $image_ext = pathinfo($new_img, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_ext;
    } else {
        $update_filename = $old_img;
    }

    $update_query = "UPDATE menus SET menu_img='$update_filename',menu_name='$menu_name',
    price_per_pax='$price_per_pax',description='$description', menu_ctg_id = '$menu_ctg_id' WHERE menu_id = '$menu_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        if ($_FILES['menu_img']['name'] != "") {
            move_uploaded_file($_FILES['menu_img']['tmp_name'], $path . '/' . $update_filename);

            if (file_exists("uploads/" . $old_img)) {
                unlink("uploads/" . $old_img);
            }
        } else {
            $update_filename = $old_img;
        }
        echo "<script>
                alert ('menu Updated Successfully.');
                document.location = 'menu.php';
                </script>";
    } else {
        echo "<script>
                alert('Update menu Unsuccessful.');
                document.location = 'menu.php';
                </script>";
    }
}


//button delete menu is clicked
else if (isset($_POST['deletemenu'])) {
    $menu_id = mysqli_real_escape_string($con, $_POST['menu_id']);
    
    $menu_query = "SELECT * FROM menus WHERE menu_id = '$menu_id'";
    $menu_query_run = mysqli_query($con, $menu_query);
    $menu_data = mysqli_fetch_array($menu_query_run);
    $image = $menu_data['menu_img'];


    $delete_query = "DELETE FROM menus WHERE menu_id = '$menu_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    // if update menu is successful
    if ($delete_query_run) {

        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }

        echo "<script>
            alert('Menu Deleted Successfully.');
            document.location = 'menu.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'menu.php';
        </script>";
    }
}