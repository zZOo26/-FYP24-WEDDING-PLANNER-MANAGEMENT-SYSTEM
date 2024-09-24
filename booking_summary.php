<?php
session_start();
include('dbcon.php'); // Assuming this file includes your bookingbase connection
include('functions.php'); // Assuming this file includes necessary functions

// Check if the user is logged in
if (!isset($_SESSION['cust_id'])) {
    // Redirect to login page if not logged in
    $redirect_url = $_SERVER['REQUEST_URI'];
    header("Location: login.php?redirect=" . urlencode($redirect_url));
    exit();
}

$user_data = check_login($con); // Function to fetch user booking from bookingbase

$page_title = "Booking Summary";
include('includes/header.php');

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    $query = "SELECT a.*, b.firstname, b.lastname, b.phoneNo, b.email, c.*, d.name as attire, e.name as dais, f.slot, f.start_time, g.menu_name, h.section, h.total_price as menu_price, 
                i.fullname as makeup_artist, i.phoneNo as mua_contact, j.fullname as eventhost, j.phoneNo as eventhost_contact, k.fullname as photographer, k.phoneNo as photo_contact
                FROM package_bookings a
                LEFT JOIN customers b ON a.cust_id = b.cust_id
                LEFT JOIN packages c ON a.pkg_id = c.pkg_id
                LEFT JOIN bridal_attire d ON a.attire_id = d.attire_id
                LEFT JOIN bridal_dais e ON a.dais_id = e.dais_id
                LEFT JOIN event_slot f ON a.slot_id = f.slot_id
                LEFT JOIN package_menu h ON c.pkg_id = h.pkg_id 
                LEFT JOIN menus g ON g.menu_id = h.menu_id
                LEFT JOIN ex_resources i ON a.makeup_artist = i.resource_id
                LEFT JOIN ex_resources j ON a.event_host = j.resource_id
                LEFT JOIN ex_resources k ON a.photographer = k.resource_id
                WHERE a.booking_id = ?";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $booking_id); // Bind the booking_id parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);

        // Display booking details
        // ... Your HTML and PHP code to display booking details goes here ...


?>


        <section class="my-5 py-6 px-5">
            <div class="card shadow-lg px-3">
                <div class="card-header bg-transparent ">
                    <div class="row ">
                        <div class="col-md-6 text-start">
                            <h4 class="text-secondary mb-4">Booking Summary</h4>
                        </div>
                        <div class="col-md-6 text-start">
                            <div class=" text-end">
                                <a data-mdb-ripple-init onclick="window.print()" class="btn btn-outline-secondary btn-sm text-capitalize mt-3" data-mdb-ripple-color="dark"><i class="fas fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-5 mt-5">
                            <div class="col-md-6">
                                <h1 class="text-gradient text-warning font-weight-bolder" style="font-family: 'Allura', sans-serif;">
                                    Waniey Bridal
                                </h1>
                                <h6 class="text-gradient text-warning font-weight-light" style="margin-top: -20px;">Wedding Planner</h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-secondary mb-1">Booking ID: <strong>#<?= htmlspecialchars($booking['booking_id']) ?></strong></p>
                                <p class="text-secondary mb-1">Booking Date: <strong><?= date('F j, Y', strtotime($booking['booking_date'])) ?></strong></p>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6 mb-3">
                                <table class="table table-responsive border-none">
                                    <tbody>
                                        <tr>
                                            <th class="text-secondary">Customer: </th>
                                            <td><?= $booking['firstname'] ?> <?= $booking['lastname'] ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Contact: </th>
                                            <td>+60<?= $booking['phoneNo'] ?> </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Email: </th>
                                            <td><?= $booking['email'] ?> </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Groom and Bride: </th>
                                            <td><?= $booking['groom_name'] ?> and <?= $booking['bride_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Event Location: </th>
                                            <td><?= $booking['event_loc'] ?> </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Event Date: </th>
                                            <td><?= date('F j, Y', strtotime($booking['event_date'])) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Event Slot: </th>
                                            <?php
                                            // Assuming $booking['start_time'] is in 'H:i:s' format and $booking['duration'] is in hours
                                            $start_time = DateTime::createFromFormat('H:i:s', $booking['start_time']);

                                            // Create a DateInterval object for the duration
                                            $duration = new DateInterval('PT' . $booking['duration'] . 'H');

                                            // Clone the start time to create the end time
                                            $end_time = clone $start_time;
                                            $end_time->add($duration);

                                            // Format the start time and end time
                                            $formatted_start_time = $start_time->format('h:i A');
                                            $formatted_end_time = $end_time->format('h:i A');

                                            // echo $formatted_start_time . ' - ' . $formatted_end_time;
                                            ?>

                                            <td><?php echo $formatted_start_time . ' - ' . $formatted_end_time; ?> ( <?= $booking['slot'] ?> )</td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Total Pax: </th>
                                            <td><?= $booking['pax'] ?> </td>
                                        </tr>
                                        <tr>
                                            <th class="text-secondary">Booking Status</th>
                                            <td class="align-middle">
                                                <?php if ($booking['booking_status'] == 0) { ?>
                                                    <p class="badge badge-xs border border-warning text-warning">Upcoming</p>
                                                <?php } elseif ($booking['booking_status'] == 1) { ?>
                                                    <p class="badge badge-xs border border-success text-success">Completed</p>
                                                <?php } else { ?>
                                                    <p class="badge badge-xs border border-danger text-danger">Canceled</p>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <h5 class="mt-5 mb-3">Package Details</h5>
                        <div class="table-responsive">
                            <table class="table border align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="align-middle ">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Package</span>
                                        </th>
                                        <th class="align-middle ">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Included:</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catering:</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Free Items:</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle">
                                            <h6 class="text font-weight-bold mb-0"><?= $booking['pkg_name'] ?> </h6>
                                            <p class="text font-weight-bold mb-0">RM <?= $booking['total_payment'] ?> </p>
                                        </td>
                                        <td class="align-middle" style="max-width: 200px; word-wrap: break-word; white-space: normal; overflow: hidden;">
                                            <ul>
                                                <?php if ($booking['eventspace_inc'] == 1) : ?>
                                                    <li>Eventspace</li>
                                                <?php endif; ?>
                                                <?php if ($booking['attire_inc'] == 1) : ?>
                                                    <li>Baju Pengantin (L/P)</li>
                                                <?php endif; ?>
                                                <?php if ($booking['dais_inc'] == 1) : ?>
                                                    <li>Pelamin</li>
                                                <?php endif; ?>
                                                <?php if ($booking['eventhost_inc'] == 1) : ?>
                                                    <li>Event Host</li>
                                                <?php endif; ?>
                                                <?php if ($booking['makeup_inc'] == 1) : ?>
                                                    <li>Makeup</li>
                                                <?php endif; ?>
                                                <?php if ($booking['photographer_inc'] == 1) : ?>
                                                    <li>Photographer</li>
                                                    <ul>
                                                        <li>Shoot, Edit, Copy</li>
                                                        <li>Photo Album</li>
                                                        <li>Free Outdoor (T&C Applied)</li>
                                                    </ul>
                                                <?php endif; ?>
                                            </ul>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                            if ($booking) {
                                                $menu_by_sections = array();

                                                // Fetch all menu items with their sections from the database
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $menu_name = $row['menu_name'];
                                                    $menu_section = $row['section'];

                                                    // Add menu item to the respective section in the array
                                                    if (!isset($menu_by_sections[$menu_section])) {
                                                        $menu_by_sections[$menu_section] = array();
                                                    }
                                                    $menu_by_sections[$menu_section][] = $menu_name;
                                                }

                                                // Display the package details and the grouped catering menu
                                                if (!empty($menu_by_sections)) {

                                            ?>
                                                    <ul>
                                                <?php
                                                    // Iterate over the grouped sections and display each section with its menu items
                                                    foreach ($menu_by_sections as $section => $items) {
                                                        echo "<li><strong>$section</strong><ul>";
                                                        foreach ($items as $item) {
                                                            echo "<li>$item</li>";
                                                        }
                                                        echo "</ul></li>";
                                                    }
                                                }
                                            }
                                                ?>
                                                    </ul>

                                        </td>
                                        <td>
                                            <ul>
                                                <?php
                                                $items = explode(', ', $booking['free_items']);
                                                echo "<li>" . implode('</li><li>', $items) . "</li>";
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>


                            </table>
                        </div>

                        <h5 class="mt-5">Payment Details</h5>
                        <div class="table-responsive mb-5">
                            <table class="table border align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="align-middle ">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total:</span>
                                        </th>
                                        <th class="align-middle ">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deposit:</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Balance:</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Paid:</span>
                                        </th>
                                        <th class="align-middle ">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Promotional Code:</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remark</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full Payment Status</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle">
                                            <p class="text-lg font-weight-bold mb-0">RM<?= $booking['total_payment'] ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <p>RM<?= number_format($booking['deposit'], 2) ?></p>
                                            <?php
                                            if ($booking['deposit_status'] == 0) {
                                            ?>
                                                <p class="badge badge-xs border border-warning text-warning">Pending</p>

                                            <?php
                                            } elseif ($booking['deposit_status'] == 1) {

                                            ?>
                                                <p class="badge badge-xs border border-success text-success">Paid</p>
                                            <?php
                                            } else {
                                            ?>
                                                <p class="badge badge-xs border border-danger text-danger">Canceled</p>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="align-middle">

                                            <?php
                                            if ($booking['full_payment_status'] == 0) {
                                                if ($booking['deposit_status'] == 0) {
                                            ?>
                                                    <p><strong>RM<?= number_format($booking['total_payment'], 2) ?></strong></p>
                                                <?php
                                                } else {
                                                ?>
                                                    <p><strong>RM<?= number_format($booking['payment_bal'], 2) ?></strong></p>
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
                                        <td>
                                            <?php
                                            if ($booking['full_payment_status'] == 0) {
                                                if ($booking['deposit_status'] == 1) {
                                            ?>
                                                    <p><strong>RM<?= number_format($booking['deposit'], 2) ?></strong></p>
                                                <?php
                                                } else {
                                                ?>
                                                    <p><strong>RM<?= number_format(0, 2) ?></strong></p>
                                                <?php
                                                }
                                                ?>
                                            <?php
                                            } else {
                                            ?>
                                                <p><strong>RM<?= number_format($booking['total_payment'], 2) ?></strong></p>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-xs font-weight-bold mb-0"><?= $booking['promo_code'] ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-xs font-weight-bold mb-0" style="max-width: 200px; word-wrap: break-word; white-space: normal; overflow: hidden;"><?= $booking['remarks'] ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                            if ($booking['full_payment_status'] == 0) {
                                            ?>
                                                <p class="badge badge-xs border border-warning text-warning">Pending</p>

                                            <?php
                                            } elseif ($booking['full_payment_status'] == 1) {

                                            ?>
                                                <p class="badge badge-xs border border-success text-success">Paid</p>
                                            <?php
                                            } else {
                                            ?>
                                                <p class="badge badge-xs border border-danger text-danger">Canceled</p>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>


                            </table>
                        </div>

                        <h5 class="mt-5">Others</h5>
                        <div class="table-responsive mb-5">
                            <table class="table border align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center ">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">Attire selected:</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dais selected:</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event Host:</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Photographer:</span>
                                        </th>
                                        <th class="align-middle text-center">
                                            <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Makeup Artist</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <?php if ($booking['attire_id'] !== NULL) { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($booking['attire']) ?> </p>
                                            </td>
                                        <?php  } else { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">N/A</p>
                                            </td>
                                        <?php  } ?>

                                        <?php if ($booking['dais_id'] !== NULL) { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($booking['dais']) ?> </p>
                                            </td>
                                        <?php  } else { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">N/A</p>
                                            </td>
                                        <?php  } ?>

                                        <?php if ($booking['makeup_artist'] !== NULL) { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($booking['makeup_artist']) ?></p>
                                                <span class="text-xs mb-0">+60<?= htmlspecialchars($booking['mua_contact']) ?></span>
                                            </td>
                                        <?php  } else { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">N/A</p>
                                            </td>
                                        <?php  } ?>

                                        <?php if ($booking['eventhost'] !== NULL) { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($booking['eventhost']) ?></p>
                                                <span class="text-xs mb-0">+60<?= htmlspecialchars($booking['eventhost_contact']) ?></span>
                                            </td>
                                        <?php  } else { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">N/A</p>
                                            </td>
                                        <?php  } ?>

                                        <?php if ($booking['photographer'] !== NULL) { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($booking['photographer']) ?></p>
                                                <span class="text-xs mb-0">+60<?= htmlspecialchars($booking['photo_contact']) ?></span>
                                            </td>
                                        <?php  } else { ?>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">N/A</p>
                                            </td>
                                        <?php  } ?>

                                    </tr>

                                </tbody>



                            </table>
                        </div>

                    </div>
                    <div class="card-footer">
                        <p class="text-center mb-0 mt-0"><a href="mybooking.php">Go to My Bookings</a></p>
                    </div>
                </div>
        </section>


<?php
    } else {
        echo "Error: Booking record not found.";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);
} else {
    echo "Error: Booking ID not provided.";
}
?>