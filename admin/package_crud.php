<?php
include('../dbcon.php');


if (isset($_POST['addCategory'])) {
    $ctg_name = $_POST['ctg_name'];

    $query = "INSERT INTO package_category (ctg_name) VALUES ('$ctg_name')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        echo "<script>
                alert('Category added successfully!');
                window.location.href = 'packages.php';
            </script>";
    } else {
        echo "<script>
                alert('Cannot add category!');
                window.location.href = 'packages.php';
            </script>";
    }
}


if (isset($_POST['addpkg'])) {
    $pkg_name = $_POST['pkg_name'];
    $pkg_desc = $_POST['pkg_desc'];
    $pkg_ctg_id = $_POST['pkg_ctg_id'];
    $pkg_price = $_POST['pkg_price'];
    $total_pax = $_POST['total_pax'];
    $duration = $_POST['duration'];
    $free_items = $_POST['free_items'];
    $eventspace_inc = isset($_POST['eventspace_inc']) ? 1 : 0;
    $makeup_inc = isset($_POST['makeup_inc']) ? 1 : 0;
    $attire_inc = isset($_POST['attire_inc']) ? 1 : 0;
    $dais_inc = isset($_POST['dais_inc']) ? 1 : 0;
    $photographer_inc = isset($_POST['photographer_inc']) ? 1 : 0;
    $eventhost_inc = isset($_POST['eventhost_inc']) ? 1 : 0;
    $menus = $_POST['menus'];

    // Handle image upload
    $img_file = $_FILES['image']['name'];
    $path = "uploads/";
    $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext; // Generate unique filename using timestamp

    // Move uploaded file to destination folder
    move_uploaded_file($_FILES['image']['tmp_name'], $path . $filename);

    // Use transaction to avoid partial inserts
    $con->begin_transaction();

    try {
        // Insert into packages table
        $stmt = $con->prepare("INSERT INTO packages (pkg_img, pkg_name, pkg_desc, pkg_ctg_id, pkg_price, total_pax, duration, free_items, eventspace_inc, eventhost_inc, attire_inc, dais_inc, makeup_inc, photographer_inc) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssidiisiiiiii", $filename, $pkg_name, $pkg_desc, $pkg_ctg_id, $pkg_price, $total_pax, $duration, $free_items, $eventspace_inc, $eventhost_inc, $attire_inc, $dais_inc, $makeup_inc, $photographer_inc);
        $stmt->execute();
        $pkg_id = $stmt->insert_id;
        $stmt->close();

        // Insert into package_menu table
        foreach ($menus as $menu) {
            $menu_id = $menu['menu_id'];
            $qty = $menu['qty'];
            $section = $menu['section'];

            // Get the menu price
            $result = $con->query("SELECT price_per_pax FROM menus WHERE menu_id = $menu_id");
            $row = $result->fetch_assoc();
            $price_per_pax = $row['price_per_pax'];
            $total_price = $price_per_pax * $qty;

            $stmt = $con->prepare("INSERT INTO package_menu (pkg_id, menu_id, qty, total_price, section) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiids", $pkg_id, $menu_id, $qty, $total_price, $section);
            $stmt->execute();
            $stmt->close();
        }

        $con->commit();
        echo "<script>
                alert('Package added successfully!');
                window.location.href = 'packages.php';
            </script>";
    } catch (Exception $e) {
        $con->rollback();
        echo "<script>
                alert('Failed to add package: " . $e->getMessage() . "');
                window.location.href = 'packages.php';
            </script>";
    }
}

$con->close();


//update package
session_start();
include('../dbcon.php');

if (isset($_POST['updatepkg'])) {
    $pkg_id = $_POST['pkg_id'];
    $pkg_name = $_POST['pkg_name'];
    $deposit = $_POST['deposit'];
    $pkg_desc = $_POST['pkg_desc'];
    $pkg_ctg_id = $_POST['pkg_ctg_id'];
    $pkg_price = $_POST['pkg_price'];
    $total_pax = $_POST['total_pax'];
    $duration = $_POST['duration'];
    $free_items = $_POST['free_items'];
    $eventspace_inc = isset($_POST['eventspace_inc']) ? 1 : 0;
    $makeup_inc = isset($_POST['makeup_inc']) ? 1 : 0;
    $attire_inc = isset($_POST['attire_inc']) ? 1 : 0;
    $dais_inc = isset($_POST['dais_inc']) ? 1 : 0;
    $photographer_inc = isset($_POST['photographer_inc']) ? 1 : 0;
    $eventhost_inc = isset($_POST['eventhost_inc']) ? 1 : 0;
    $menus = $_POST['menus'];
    $remove_menus = isset($_POST['remove_menus']) ? $_POST['remove_menus'] : [];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $img_file = $_FILES['image']['name'];
        $path = "uploads/";
        $image_ext = pathinfo($img_file, PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext; // Generate unique filename using timestamp

        // Move uploaded file to destination folder
        move_uploaded_file($_FILES['image']['tmp_name'], $path . $filename);

        // Delete old image file
        $result = $con->query("SELECT pkg_img FROM packages WHERE pkg_id = $pkg_id");
        $row = $result->fetch_assoc();
        if ($row && file_exists($path . $row['pkg_img'])) {
            unlink($path . $row['pkg_img']);
        }
    } else {
        // Keep the old image if no new image is uploaded
        $filename = $con->query("SELECT pkg_img FROM packages WHERE pkg_id = $pkg_id")->fetch_assoc()['pkg_img'];
    }

    // Use transaction to avoid partial updates
    $con->begin_transaction();

    try {
        // Update packages table
        $stmt = $con->prepare("UPDATE packages SET pkg_img = ?, pkg_name = ?, pkg_desc = ?, pkg_ctg_id = ?, pkg_price = ?, deposit = ?, total_pax = ?, duration = ?, free_items = ?, eventspace_inc = ?, eventhost_inc = ?, attire_inc = ?, dais_inc = ?, makeup_inc = ?, photographer_inc = ? WHERE pkg_id = ?");
        $stmt->bind_param("sssiddiisiiiiiii", $filename, $pkg_name, $pkg_desc, $pkg_ctg_id, $pkg_price, $deposit,  $total_pax, $duration, $free_items, $eventspace_inc, $eventhost_inc, $attire_inc, $dais_inc, $makeup_inc, $photographer_inc, $pkg_id);
        $stmt->execute();
        $stmt->close();

        // Remove existing menus if any
        if (!empty($remove_menus)) {
            foreach ($remove_menus as $menu_id) {
                $stmt = $con->prepare("DELETE FROM package_menu WHERE pkg_id = ? AND menu_id = ?");
                $stmt->bind_param("ii", $pkg_id, $menu_id);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Insert new or update existing menus in package_menu table
        foreach ($menus as $menu) {
            $menu_id = $menu['menu_id'];
            $qty = $menu['qty'];
            $section = $menu['section'];

            // Get the menu price
            $result = $con->query("SELECT price_per_pax FROM menus WHERE menu_id = $menu_id");
            $row = $result->fetch_assoc();
            $price_per_pax = $row['price_per_pax'];
            $total_price = $price_per_pax * $qty;

            // Check if the menu already exists in the package_menu table
            $result = $con->query("SELECT COUNT(*) as count FROM package_menu WHERE pkg_id = $pkg_id AND menu_id = $menu_id");
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                // Update existing menu
                $stmt = $con->prepare("UPDATE package_menu SET qty = ?, total_price = ?, section = ? WHERE pkg_id = ? AND menu_id = ?");
                $stmt->bind_param("idsii", $qty, $total_price, $section, $pkg_id, $menu_id);
            } else {
                // Insert new menu
                $stmt = $con->prepare("INSERT INTO package_menu (pkg_id, menu_id, qty, total_price, section) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iiids", $pkg_id, $menu_id, $qty, $total_price, $section);
            }

            $stmt->execute();
            $stmt->close();
        }

        $con->commit();
        echo "<script>
                alert('Package updated successfully!');
                window.location.href = 'packages.php';
            </script>";
    } catch (Exception $e) {
        $con->rollback();
        echo "<script>
                alert('Failed to update package: " . $e->getMessage() . "');
                window.location.href = 'packages.php';
            </script>";
    }
}

$con->close();


session_start();
include('../dbcon.php');

if (isset($_POST['deletepkg'])) {
    $pkg_id = $_POST['pkg_id'];

    // Use transaction to avoid partial deletes
    $con->begin_transaction();

    try {
        // Delete from package_menu table
        $stmt = $con->prepare("DELETE FROM package_menu WHERE pkg_id = ?");
        $stmt->bind_param("i", $pkg_id);
        $stmt->execute();
        $stmt->close();

        // Delete from packages table
        // First, retrieve the image file name to delete the file
        $result = $con->query("SELECT pkg_img FROM packages WHERE pkg_id = $pkg_id");
        $row = $result->fetch_assoc();
        $image_path = "uploads/" . $row['pkg_img'];

        // Delete the package record
        $stmt = $con->prepare("DELETE FROM packages WHERE pkg_id = ?");
        $stmt->bind_param("i", $pkg_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $con->commit();

        // Delete the image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        echo "<script>
                alert('Package deleted successfully!');
                window.location.href = 'packages.php';
            </script>";
    } catch (Exception $e) {
        $con->rollback();
        echo "<script>
                alert('Failed to delete package: " . $e->getMessage() . "');
                window.location.href = 'packages.php';
            </script>";
    }
}

$con->close();
