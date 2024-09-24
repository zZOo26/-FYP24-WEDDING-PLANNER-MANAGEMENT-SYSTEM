<?php
session_start();
include('dbcon.php'); // Assuming this file includes your rentalbase connection
include('functions.php'); // Assuming this file includes necessary functions

// Check if the user is logged in
if (!isset($_SESSION['cust_id'])) {
    // Redirect to login page if not logged in
    $redirect_url = $_SERVER['REQUEST_URI'];
    header("Location: login.php?redirect=" . urlencode($redirect_url));
    exit();
}

$user_rental = check_login($con); // Function to fetch user rental from rentalbase

$_SESSION;
$page_title = "Rental Summary";
include('includes/header.php');

// Assume rental insertion logic is already handled and successful, and rental_id is obtained
if (isset($_GET['rental_id'])) {
    $rental_id = $_GET['rental_id'];

    // Query to fetch rental details based on rental_id
    $fetch_rental_query = "SELECT b.*, c.*, 
                                                CASE 
                                                    WHEN b.rental_type = 'dais' THEN d.name
                                                    WHEN b.rental_type = 'attire' THEN a.name
                                                END AS item_name,
                                                CASE 
                                                    WHEN b.rental_type = 'dais' THEN d.image
                                                    WHEN b.rental_type = 'attire' THEN a.image
                                                END AS item_img,
                                                CASE 
                                                    WHEN b.rental_type = 'dais' THEN d.category
                                                    WHEN b.rental_type = 'attire' THEN a.category
                                                END AS item_type
                                            FROM rentals b
                                            JOIN customers c ON b.cust_id = c.cust_id
                                            LEFT JOIN bridal_dais d ON b.rental_type = 'dais' AND b.item_id = d.dais_id
                                            LEFT JOIN bridal_attire a ON b.rental_type = 'attire' AND b.item_id = a.attire_id
                                            WHERE b.rental_id = ?";

    $stmt = mysqli_prepare($con, $fetch_rental_query);
    mysqli_stmt_bind_param($stmt, "i", $rental_id); // Bind the rental_id parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $rental = mysqli_fetch_assoc($result);

        // Retrieve other necessary rental from rentalbase or variables
        $item_id = $rental['item_id']; // Example, you may need to fetch item details
        $event_date = date('F j, Y', strtotime($rental['event_date']));
        $return_date = date('F j, Y', strtotime($rental['return_date'])); // Format event date nicely
        $event_location = $rental['event_loc']; // Location details

        // Close statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($con);
?>

        <section class="my-8 py-8 px-5">
            <div class="container">
                <div class="row mt-n8 pb-4 p-3 mx-sm-0 mx-1 ">
                    <div class="card shadow-lg blur px-3">
                        <div class="card-header bg-transparent ">
                            <div class="row ">
                                <div class="col-md-6 text-start">
                                    <h4 class="text-secondary mb-4">Rental Summary</h4>
                                </div>
                                <div class="col-md-6 text-start">
                                    <div class=" text-end">
                                        <a data-mdb-ripple-init onclick="window.print()" class="btn btn-outline-secondary btn-sm text-capitalize mt-3" data-mdb-ripple-color="dark"><i class="fas fa-print"></i> Print</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-5">
                                <div class="row mb-5 mt-5">
                                    <div class="col-md-6">
                                        <h1 class="text-gradient text-warning font-weight-bolder" style="font-family: 'Allura', sans-serif;">
                                            Waniey Bridal
                                        </h1>
                                        <h6 class="text-gradient text-warning font-weight-light" style="margin-top: -20px;">Wedding Planner</h6>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="text-secondary mb-1">Rental ID: <strong>#<?= htmlspecialchars($rental['rental_id']) ?></strong></p>
                                        <p class="text-secondary mb-1">Rental Date: <strong><?= date('F j, Y', strtotime($rental['created_at'])) ?></strong></p>
                                    </div>
                                </div>
                                <table class="table table-responsive">
                                    <tbody>
                                        <tr>
                                            <th class="text-secondary">Customer: </th>
                                            <td>
                                                <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($rental['firstname']) ?> <?= htmlspecialchars($rental['lastname']) ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Contact: </th>
                                            <td>
                                                <p class="mb-0 text-sm font-weight-semibold">+60<?= htmlspecialchars($rental['phoneNo']) ?> </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Email: </th>
                                            <td>
                                                <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($rental['email']) ?> </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Event Location: </th>
                                            <td>
                                                <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($rental['event_loc']) ?> </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Event Date: </th>
                                            <td>
                                                <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($event_date) ?> </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Return Date</th>
                                            <td>
                                                <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($return_date) ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Rental Status</th>
                                            <td class="align-middle">
                                                <?php if ($rental['rental_status'] == 0) { ?>
                                                    <p class="badge badge-xs border border-warning text-warning">Upcoming</p>
                                                <?php } elseif ($rental['rental_status'] == 1) { ?>
                                                    <p class="badge badge-xs border border-success text-success">Completed</p>
                                                <?php } else { ?>
                                                    <p class="badge badge-xs border border-danger text-danger">Canceled</p>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-5">
                                    <h5 class="mt-5 mb-3">Rental Details</h5>
                                    <div class="table-responsive">
                                        <table class="table border align-items-center mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="text-secondary">Rental Type</th>
                                                    <td class="text-uppercase">
                                                        <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($rental['rental_type']) ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Item ID</th>
                                                    <td>
                                                        <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($item_id) ?> </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Item Name</th>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex align-items-center">
                                                                <img class="avatar avatar-lg me-2" src="admin/uploads/<?= htmlspecialchars($rental['item_img']) ?>" />
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-start ms-1">
                                                                <p class="mb-0 text-sm font-weight-semibold"><?= htmlspecialchars($rental['item_name']) ?></p>
                                                                <p class="text-sm text-secondary mb-0"><?= htmlspecialchars($rental['item_type']) ?></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Full Payment</th>
                                                    <td>
                                                        <p class="mb-0 text-sm font-weight-semibold"><?= date('F j, Y', strtotime($rental['full_payment_date'])) ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Remark</th>
                                                    <td><?= htmlspecialchars($rental['remark']) ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Total</th>
                                                    <td>
                                                        <span><strong>RM<?= number_format($rental['total_payment'], 2) ?></strong></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Deposit Amount</th>
                                                    <td>
                                                        <span>RM<?= number_format($rental['deposit'], 2) ?></span><br>
                                                        <span>
                                                            <?php if ($rental['deposit_status'] == 0) { ?>
                                                                <p class="badge badge-xs border border-warning text-warning">Pending</p>
                                                            <?php } elseif ($rental['deposit_status'] == 1) { ?>
                                                                <p class="badge badge-xs border border-success text-success">Paid</p>
                                                            <?php } else { ?>
                                                                <p class="badge badge-xs border border-danger text-danger">Canceled</p>
                                                            <?php } ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Payment Balance</th>
                                                    <td>
                                                        <?php
                                                        if ($rental['full_payment_status'] == 0) {
                                                            if ($rental['deposit_status'] == 0) {
                                                        ?>
                                                                <span>RM<?= number_format($rental['total_payment'], 2) ?></span>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <span>RM<?= number_format($rental['payment_bal'], 2) ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span>RM<?= number_format(0, 2) ?></span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-secondary">Total Paid</th>
                                                    <td>
                                                        <?php
                                                        if ($rental['full_payment_status'] == 0) {
                                                            if ($rental['deposit_status'] == 1) {
                                                        ?>
                                                                <span>RM<?= number_format($rental['deposit'], 2) ?></span>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <span>RM<?= number_format(0, 2) ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span>RM<?= number_format($rental['total_payment'], 2) ?></span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p class="text-center mb-0 mt-0"><a href="myrental.php">Go to My Rentals</a></p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
<?php
    } else {
        echo "Error: Rental record not found.";
    }
} else {
    echo "Error: Rental ID not provided.";
}
?>