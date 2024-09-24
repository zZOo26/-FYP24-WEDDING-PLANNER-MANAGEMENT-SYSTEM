<?php
function check_login($con)
{

    if (isset($_SESSION['admin_id'])) {
        $id = $_SESSION['admin_id'];
        $query = "SELECT * FROM admins WHERE admin_id = $id LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    } else {
        //redirect to login page
        header("Location: login.php");
        die;
    }
}

//get monthly sale
function getMonthlySales($year, $month = null)
{
    global $con;

    $query = "
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            SUM(total_payment) AS total_sales
        FROM (
            SELECT created_at, total_payment FROM rentals
            UNION ALL
            SELECT booking_date AS created_at, total_payment FROM package_bookings
        ) AS combined_sales
        WHERE 
            YEAR(created_at) = '$year'
    ";

    if ($month) {
        $query .= " AND MONTH(created_at) = '$month'";
    }

    $query .= "
        GROUP BY 
            DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY 
            DATE_FORMAT(created_at, '%Y-%m')
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $sales_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sales_data[] = $row;
    }

    return $sales_data;
}


function getTotalCustomers()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_customers FROM customers");
    $data = mysqli_fetch_assoc($result);
    return $data['total_customers'];
}

function getTotalPackages()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_packages FROM packages");
    $data = mysqli_fetch_assoc($result);
    return $data['total_packages'];
}

function getTotalBridalAttires()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_bridal_attires FROM bridal_attire");
    $data = mysqli_fetch_assoc($result);
    return $data['total_bridal_attires'];
}

function getTotalBridalDais()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_bridal_dais FROM bridal_dais");
    $data = mysqli_fetch_assoc($result);
    return $data['total_bridal_dais'];
}

function getTotalDaisRental()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_dais_rental FROM rentals WHERE rental_type = 'dais'");
    $data = mysqli_fetch_assoc($result);
    return $data['total_dais_rental'];
}

function getTotalAttireRental()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_attire_rental FROM rentals WHERE rental_type = 'attire'");
    $data = mysqli_fetch_assoc($result);
    return $data['total_attire_rental'];
}

function getTotalPackageBooking()
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total_package_booking FROM package_bookings WHERE booking_status != 3");
    $data = mysqli_fetch_assoc($result);
    return $data['total_package_booking'];
}

function getTotalExpenses()
{
    global $con;
    $result = mysqli_query($con, "SELECT SUM(amount) AS total_expenses FROM expenses");
    $data = mysqli_fetch_assoc($result);
    return $data['total_expenses'];
}

function getMonthlyExpenses($year)
{
    global $con;

    // Run the SQL query
    $sql = "SELECT DATE_FORMAT(date_created, '%Y-%m') AS month, SUM(amount) AS monthly_expense 
            FROM expenses 
            WHERE YEAR(date_created) = '$year' 
            GROUP BY month 
            ORDER BY month";
    $result = mysqli_query($con, $sql);

    // Check if the query was successful
    if (!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $monthly_expenses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $monthly_expenses[] = $row;
    }

    // Debugging: print the result array
    // echo '<pre>';
    // print_r($monthly_expenses);
    // echo '</pre>';

    return $monthly_expenses;
}
