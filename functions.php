<?php
function check_login($con)
{

    if (isset($_SESSION['cust_id'])) {
        $id = $_SESSION['cust_id'];
        $query = "SELECT * FROM customers WHERE cust_id = $id LIMIT 1";
        $query_run = mysqli_query($con, $query);

        if ($query_run && mysqli_num_rows($query_run) > 0) {
            $user_data = mysqli_fetch_assoc($query_run);
            return $user_data;
        }
    } else {
        //redirect to login page
        header("Location: login.php");
        die;
    }

    return false;
}


function getAllActive($table)
{
    global $con;
    $query = "SELECT * FROM $table ORDER BY created_at DESC";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getLatest($table)
{
    global $con;
    $query = "SELECT * FROM $table ORDER BY created_at DESC LIMIT 6";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getLatestPackage()
{
    global $con;
    $query = "SELECT a.*, b.ctg_name FROM packages a INNER JOIN package_category b ON a.pkg_ctg_id = b.pkg_ctg_id ORDER BY created_at DESC LIMIT 6";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getCategory($table)
{
    global $con;
    $query = "SELECT DISTINCT category FROM $table";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getIDActive($table, $column, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE $column='$id' ORDER BY created_at DESC";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getPackage($id)
{
    global $con;
    $query = "SELECT a.*, b.*, d.menu_name, c.section
              FROM packages a 
              INNER JOIN package_category b ON a.pkg_ctg_id = b.pkg_ctg_id 
              INNER JOIN package_menu c ON a.pkg_id = c.pkg_id 
              INNER JOIN menus d ON c.menu_id = d.menu_id
              WHERE a.pkg_id = '$id'";

    $query_run = mysqli_query($con, $query);
    return $query_run;
}


function getActivePromotion()
{
    global $con;
    $query = "SELECT p.*, c.ctg_name
                FROM promotions p
                JOIN promotion_category c ON p.promo_ctg_id = c.promo_ctg_id
                WHERE p.promo_status IN ('ongoing', 'upcoming')"; // Fetch up to 6 promotions for the carousel
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getNumberofData($table, $column)
{
    global $con;
    $query = "SELECT COUNT($column) AS NumberOfProducts FROM $table";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function getFeedback()
{
    global $con;
    $query = "SELECT a.* , b.firstname, b.lastname FROM feedback a INNER JOIN customers b ON a.cust_id=b.cust_id WHERE view = 1 ORDER BY a.created_at DESC";
    $query_run = mysqli_query($con, $query);
    return $query_run;
}

function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location: .$url');
    exit(0);
}
