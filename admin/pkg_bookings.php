<?php
session_start();
include('../dbcon.php');
include('./functions.php');

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    die;
}

$user_data = check_login($con);

$_SESSION;

$page_title = 'Customer Package Bookings';
include('includes/header.php');

// Filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '0';
$month_filter = isset($_GET['month']) ? $_GET['month'] : '';
$pkg_ctg_id_filter = isset($_GET['pkg_ctg_id']) ? $_GET['pkg_ctg_id'] : '';
$slot_id_filter = isset($_GET['slot_id']) ? $_GET['slot_id'] : '';

$current_year = date("Y"); // Assuming you want to filter by the current year

$query = "SELECT a.*, b.firstname, b.lastname, b.phoneNo, c.*,c.deposit as pkg_deposit, d.attire_id, d.name as attire_name, e.dais_id, e.name as dais_name,
                        f.fullname as makeup_artist_name, f.phoneNo as mua_contact, g.fullname as photographer_name, 
                        f.phoneNo as photo_contact, h.fullname as event_host_name, f.phoneNo as host_contact, j.*, k.*
                        FROM package_bookings a
                        LEFT JOIN customers b ON a.cust_id = b.cust_id
                        LEFT JOIN packages c ON a.pkg_id = c.pkg_id
                        LEFT JOIN package_category j ON c.pkg_ctg_id = j.pkg_ctg_id
                        LEFT JOIN bridal_attire d ON a.attire_id = d.attire_id
                        LEFT JOIN bridal_dais e ON a.dais_id = e.dais_id
                        LEFT JOIN ex_resources f ON a.makeup_artist = f.resource_id
                        LEFT JOIN ex_resources g ON a.photographer = g.resource_id
                        LEFT JOIN ex_resources h ON a.event_host = h.resource_id
                        LEFT JOIN event_slot K ON a.slot_id = k.slot_id";

$conditions = [];
if ($status_filter !== '') {
    $conditions[] = "a.booking_status = '$status_filter'";
}
if ($month_filter !== '') {
    $conditions[] = "DATE_FORMAT(a.event_date, '%Y-%m') = '$current_year-$month_filter'";
}
if ($pkg_ctg_id_filter !== '') {
    $conditions[] = "c.pkg_ctg_id = '$pkg_ctg_id_filter'";
}
if ($slot_id_filter !== '') {
    $conditions[] = "a.slot_id = '$slot_id_filter'";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY   a.booking_date DESC ";
$query_run = mysqli_query($con, $query);
$no = 1;

?>

<div class="mt-4 row">
    <div class="col-12">
        <div class="card">
            <img src="assets/img/header-blue-purple.jpg" alt="pattern-lines" class="top-0 rounded-2 position-absolute start-0 w-100 h-100">
            <div class="px-4 bg-cover card-body z-index-1">
                <div class="row">
                    <div class="col-lg-8 col-12">
                        <h3 class="text-white">Customer Package Booking List 🔥</h3>
                        <p class="mb-4 text-white">See all details about your customer package bookings.</p>
                        <a href="add-booking.php" class="mb-0 btn btn-dark">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>
                            New Booking
                        </a>
                    </div>
                    <div class="text-end col-lg-4 col-12">
                        <img class="w-35 ms-auto me-5 d-none d-md-block" src="assets/img/3d-cube.png" alt="cube image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-4 row">
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <h5>Filter By:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class=" mb-2">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Booking Status</label>
                                    <select class="form-select" name="status">
                                        <option value="">View All</option>
                                        <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Upcoming</option>
                                        <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Completed</option>
                                        <option value="2" <?= $status_filter === '2' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Month</label>
                                    <select class="form-select" name="month">
                                        <option value="">All Months</option>
                                        <?php
                                        $months = [
                                            "01" => "January",
                                            "02" => "February",
                                            "03" => "March",
                                            "04" => "April",
                                            "05" => "May",
                                            "06" => "June",
                                            "07" => "July",
                                            "08" => "August",
                                            "09" => "September",
                                            "10" => "October",
                                            "11" => "November",
                                            "12" => "December"
                                        ];
                                        foreach ($months as $num => $name) {
                                            $selected = ($month_filter === $num) ? 'selected' : '';
                                            echo "<option value=\"$num\" $selected>$name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Package Category</label>
                                    <select class="form-select" name="pkg_ctg_id">
                                        <option value="">All Package Categories</option>
                                        <?php
                                        $pkg_ctg_query = "SELECT * FROM package_category";
                                        $pkg_ctg_query_run = mysqli_query($con, $pkg_ctg_query);
                                        while ($pkg_ctg = mysqli_fetch_assoc($pkg_ctg_query_run)) {
                                            $selected = ($pkg_ctg_id_filter === $pkg_ctg['pkg_ctg_id']) ? 'selected' : '';
                                            echo "<option value='{$pkg_ctg['pkg_ctg_id']}' $selected>{$pkg_ctg['ctg_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Slot</label>
                                    <select class="form-select" name="slot_id">
                                        <option value="">All Slots</option>
                                        <?php
                                        $slot_query = "SELECT * FROM event_slot";
                                        $slot_query_run = mysqli_query($con, $slot_query);
                                        while ($slot = mysqli_fetch_assoc($slot_query_run)) {
                                            $start_time = DateTime::createFromFormat('H:i:s', $slot['start_time']);
                                            $formatted_start_time = $start_time->format('h:i A');
                                            $selected = ($slot_id_filter === $slot['slot_id']) ? 'selected' : '';
                                            echo "<option value='{$slot['slot_id']}' $selected>{$slot['slot']} : {$formatted_start_time}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-dark">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Add Slot</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="booking_crud.php" method="post">
                    <div class="row">
                        <div class="col-md-6 ">
                            <label for="slot" class="form-label">Slot</label>
                            <input type="text" class="form-control" name="slot" required>
                        </div>
                        <div class="col-md-6 ">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="start_time" required>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-sm btn-dark mb-0" name="addslot">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="mt-4 row">
    <div class="col-12">
        <div class="card">
            <div class="card-body px-02 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                </th>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Booking ID</span>
                                </th>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Booking Date</span>
                                </th>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</span>
                                </th>
                                <th class="align-middle ">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Package</span>
                                </th>
                                <th class="align-middle ">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event Date</span>
                                </th>
                                <th class="align-middle ">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</span>
                                </th>
                                <th class="align-middle ">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <?php while ($data = mysqli_fetch_assoc($query_run)) : ?>
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">#<?= $data['booking_id'] ?></span>
                                </td>
                                <td class="align-middle">
                                    <p class="text-xs font-weight-bold mb-0">
                                        <?= date('d/m/y H:i:s', strtotime($data['booking_date'])) ?>
                                    </p>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column justify-content-start ms-1">
                                        <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['firstname'] ?> <?= $data['lastname'] ?></h6>
                                        <p class="text-sm text-secondary mb-0">+60<?= $data['phoneNo'] ?></p>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column justify-content-start ms-1">
                                        <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['pkg_name'] ?></h6>
                                        <p class="text-sm text-secondary mb-0"><?= $data['ctg_name'] ?></p>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        <?php
                                        // Convert event_date to d/m/y format
                                        $event_date = DateTime::createFromFormat('Y-m-d', $data['event_date']);

                                        // Check if event_date is valid
                                        if ($event_date !== false) {
                                            $formatted_event_date = $event_date->format('d/m/Y');
                                            echo $formatted_event_date;
                                        } else {
                                            echo "Invalid date";
                                        }
                                        ?>
                                    </span>
                                    <p class="text-sm text-secondary mb-0"><?= $data['slot'] ?></p>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $status = $data['booking_status'];
                                    switch ($status) {
                                        case '0':
                                            echo "<span class='badge badge-sm border border-warning text-warning'>Upcoming</span>";
                                            break;
                                        case '1':
                                            echo "<span class='badge badge-sm border border-success text-success'>Completed</span>";
                                            break;
                                        case '2':
                                            echo "<span class='badge badge-sm border border-danger text-danger'>Cancelled</span>";
                                            break;
                                        default:
                                            echo "<span class='badge badge-sm border border-danger text-secondary'>Unknown</span>";
                                            break;
                                    }
                                    ?>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['booking_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                    <span style="margin: 0 10px;"></span>
                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['booking_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                </td>
                            </tr>


                            <!-- Modal Edit -->
                            <div class="modal fade" data-bs-backdrop="static" id="modalEdit<?= $data['booking_id'] ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg  modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditLabel">Edit Booking</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="booking_crud.php" method="POST">
                                                <input type="hidden" name="booking_id" value="<?= $data['booking_id'] ?>">
                                                <div class="mb-3 mt-3 border-top border-bottom px-2 py-2">
                                                    <h4>Booking Details:</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="customer" class="form-label">Customer</label>
                                                        <input type="text" class="form-control" value="<?= $data['firstname'] ?> <?= $data['lastname'] ?>" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="contact" class="form-label">Contact</label>
                                                        <input type="text" class="form-control" value="+60<?= $data['phoneNo'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- Other form fields -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="item" class="form-label">Package</label>
                                                        <select class="form-select" name="pkg_id">
                                                            <option value="" selected disabled>Select Package</option>
                                                            <?php
                                                            // Update the query to retrieve pkg_name and ctg_name from the packages table
                                                            $packages_query = "SELECT a.pkg_id , a.pkg_name, ctg_name FROM packages a INNER JOIN package_category b ON a.pkg_ctg_id=b.pkg_ctg_id";
                                                            $packages_query_run = mysqli_query($con, $packages_query);
                                                            while ($package = mysqli_fetch_assoc($packages_query_run)) {
                                                                $selected = ($package['pkg_id'] == $data['pkg_id']) ? 'selected' : '';
                                                                echo "<option value='{$package['pkg_id']}' data-type='package' {$selected}>{$package['pkg_name']} ({$package['ctg_name']})</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="total_payment" class="form-label">Total Payment (RM)</label>
                                                        <input type="number" class="form-control " name="total_payment" value="<?= $data['total_payment'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="event_date" class="form-label">Event Date</label>
                                                        <input type="date" class="form-control" name="event_date" value="<?= $data['event_date'] ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="event_slot" class="form-label">Event Slot</label>
                                                        <select class="form-select" name="event_slot">
                                                            <option value="" selected disabled>Select One</option>
                                                            <?php
                                                            $slot_query = "SELECT * FROM event_slot";
                                                            $slot_query_run = mysqli_query($con, $slot_query);
                                                            while ($slot = mysqli_fetch_assoc($slot_query_run)) {
                                                                $selected = ($slot['slot_id'] == $data['slot_id']) ? 'selected' : '';
                                                                echo "<option value='{$slot['slot_id']}' {$selected}  >{$slot['slot']} - {$slot['start_time']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class=" mb-3">
                                                    <label for="event_loc" class="form-label">Event Location</label>
                                                    <input type="text" class="form-control" name="event_loc" value="<?= $data['event_loc'] ?>" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="bride_name" class="form-label">Bride Name</label>
                                                        <input type="text" class="form-control" name="bride_name" value="<?= $data['bride_name'] ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="groom_name" class="form-label">Groom Name</label>
                                                        <input type="text" class="form-control" name="groom_name" value="<?= $data['groom_name'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-5 border-top border-bottom px-2 py-2">
                                                    <h4>Payment Details:</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="deposit" class="form-label">Deposit (RM)</label>
                                                        <input type="number" class="form-control" name="deposit" value="<?= $data['pkg_deposit'] ?>" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="deposit_status" class="form-label">Deposit Status</label>
                                                        <select class="form-select" name="deposit_status">
                                                            <option value="0" <?= ($data['deposit_status'] == 0) ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="1" <?= ($data['deposit_status'] == 1) ? 'selected' : ''; ?>>Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="payment_bal" class="form-label">Payment Balance (RM)</label>
                                                        <input type="number" class="form-control" name="payment_bal" value="<?= $data['payment_bal'] ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="full_payment_status" class="form-label">Full Payment Status</label>
                                                        <select class="form-select" name="full_payment_status">
                                                            <option value="0" <?= ($data['full_payment_status'] == 0) ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="1" <?= ($data['full_payment_status'] == 1) ? 'selected' : ''; ?>>Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="full_payment_date" class="form-label">Full Payment Date</label>
                                                        <input type="date" class="form-control" name="full_payment_date" value="<?= $data['full_payment_date'] ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="promo_code" class="form-label">Promotion Code</label>
                                                        <input type="text" class="form-control" name="promo_code" value="<?= $data['promo_code'] ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="remarks" class="form-label">Remark</label>
                                                    <textarea class="form-control form-control-lg" name="remarks" required><?= $data['remarks'] ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" name="booking_status">
                                                        <option value="0" <?= ($data['booking_status'] == 0) ? 'selected' : ''; ?>>Upcoming</option>
                                                        <option value="1" <?= ($data['booking_status'] == 1) ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="2" <?= ($data['booking_status'] == 2) ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 mt-5 border-top border-bottom px-2 py-2">
                                                    <h4>Assign External Resources (if included):</h4>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Makeup Artist</label>
                                                        <select class="form-select" name="makeup_artist">
                                                            <option value="" selected disabled>Select One</option>
                                                            <?php
                                                            $mua_query = "SELECT a.*, a.resource_id AS makeup_artist, b.* FROM ex_resources a INNER JOIN resource_category b ON a.resource_ctg_id=b.resource_ctg_id WHERE b.ctg_name ='Makeup Artist'";
                                                            $mua_query_run = mysqli_query($con, $mua_query);
                                                            while ($mua = mysqli_fetch_assoc($mua_query_run)) {
                                                                $selected = ($mua['makeup_artist'] == $data['makeup_artist']) ? 'selected' : '';
                                                                echo "<option value='{$mua['makeup_artist']}' {$selected} >{$mua['fullname']} </option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Event Host</label>
                                                        <select class="form-select" name="event_host">
                                                            <option value="" selected disabled>Select One</option>
                                                            <?php
                                                            $host_query = "SELECT a.*, b.* FROM ex_resources a INNER JOIN resource_category b ON a.resource_ctg_id=b.resource_ctg_id WHERE b.ctg_name ='Event Host'";
                                                            $host_query_run = mysqli_query($con, $host_query);
                                                            while ($host = mysqli_fetch_assoc($host_query_run)) {
                                                                $selected = ($host['resource_id'] == $data['event_host']) ? 'selected' : '';
                                                                echo "<option value='{$host['resource_id']}' {$selected}>{$host['fullname']} </option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Photographer</label>
                                                        <select class="form-select" name="photographer">
                                                            <option value="" selected disabled>Select One</option>
                                                            <?php
                                                            $photographer_query = "SELECT a.*, b.* FROM ex_resources a INNER JOIN resource_category b ON a.resource_ctg_id=b.resource_ctg_id WHERE b.ctg_name ='Photographer'";
                                                            $photographer_query_run = mysqli_query($con, $photographer_query);
                                                            while ($photographer = mysqli_fetch_assoc($photographer_query_run)) {
                                                                $selected = ($photographer['resource_id'] == $data['photographer']) ? 'selected' : '';
                                                                echo "<option value='{$photographer['resource_id']}'{$selected}>{$photographer['fullname']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-5 border-top border-bottom px-2 py-2">
                                                    <h4>Bridal Dais and Attire (If included):</h4>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Bridal Dais</label>
                                                        <select class="form-select" name="dais_id">
                                                            <option value="" selected disabled>Select One</option>
                                                            <?php
                                                            $dais_query = "SELECT * FROM bridal_dais";
                                                            $dais_query_run = mysqli_query($con, $dais_query);
                                                            while ($dais = mysqli_fetch_assoc($dais_query_run)) {
                                                                $selected = ($dais['dais_id'] == $data['dais_id']) ? 'selected' : '';
                                                                echo "<option value='{$dais['dais_id']}'{$selected}>{$dais['name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Bridal Attire</label>
                                                        <select class="form-select" name="attire_id">
                                                            <option value="" selected disabled>Select One</option>
                                                            <?php
                                                            $attire_query = "SELECT * FROM bridal_attire";
                                                            $attire_query_run = mysqli_query($con, $attire_query);
                                                            while ($attire = mysqli_fetch_assoc($attire_query_run)) {
                                                                $selected = ($attire['attire_id'] == $data['attire_id']) ? 'selected' : '';
                                                                echo "<option value='{$attire['attire_id']}'{$selected}>{$attire['name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-0" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="updatebooking">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal Edit -->

                            <!-- Modal Delete -->
                            <div class="modal fade" id="modalDelete<?= $data['booking_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['booking_id'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['booking_id'] ?>">Delete Confirmation</h1>
                                        </div>
                                        <form action="booking_crud.php" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="booking_id" value="<?= $data['booking_id'] ?>">
                                            <div class="modal-body">
                                                <div class="d-flex flex-column">
                                                    <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                    <span class="text-dark text-lg font-weight-bold text-center">Delete this data?</span><br>
                                                    <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-dark mb-0" name="deletebooking">Confirm Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal Delete -->
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>