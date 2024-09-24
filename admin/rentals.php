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

$page_title = 'Customer Rentals';
include('includes/header.php');

// Filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '0';
$month_filter = isset($_GET['month']) ? $_GET['month'] : '';
$rental_type_filter = isset($_GET['rental_type']) ? $_GET['rental_type'] : '';

$current_year = date("Y"); // Assuming you want to filter by the current year

$query = "SELECT b.*, c.firstname, c.lastname, c.phoneNo,
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
LEFT JOIN bridal_attire a ON b.rental_type = 'attire' AND b.item_id = a.attire_id";

$conditions = [];
if ($status_filter !== '') {
    $conditions[] = "b.rental_status = '$status_filter'";
}
if ($month_filter !== '') {
    $conditions[] = "DATE_FORMAT(b.event_date, '%Y-%m') = '$current_year-$month_filter'";
}
if ($rental_type_filter !== '') {
    $conditions[] = "b.rental_type = '$rental_type_filter'";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY b.created_at DESC";
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
                        <h3 class="text-white">Customer Rental List 🔥</h3>
                        <p class="mb-4 text-white">See all details about your customer rentals.</p>
                        <button class="mb-0 btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalAdd">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>
                            New Rental
                        </button>
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
    <div class="col-12">
        <div class="card">
            <div class="pb-0 card-header">
                <div class="row">
                    <div class="col-6 mb-2">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-select" name="status">
                                        <option value="">View All</option>
                                        <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Upcoming</option>
                                        <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Completed</option>
                                        <option value="2" <?= $status_filter === '2' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
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
                                    <select class="form-select" name="rental_type">
                                        <option value="">All Types</option>
                                        <option value="dais" <?= $rental_type_filter === 'dais' ? 'selected' : '' ?>>Dais</option>
                                        <option value="attire" <?= $rental_type_filter === 'attire' ? 'selected' : '' ?>>Attire</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-dark">Filter</button>
                                </div>
                            </div>
                        </form>



                    </div>
                    <!-- <div class="col-6 text-end mb-2">
                        <button class="mt-1 mb-0 btn btn-white export mt-sm-0" data-type="csv" type="button" name="button">
                            <svg class="me-2" width="14" height="15" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17.5C3 16.9477 3.44772 16.5 4 16.5H16C16.5523 16.5 17 16.9477 17 17.5C17 18.0523 16.5523 18.5 16 18.5H4C3.44772 18.5 3 18.0523 3 17.5ZM6.29289 7.20711C5.90237 6.81658 5.90237 6.18342 6.29289 5.79289L9.29289 2.79289C9.48043 2.60536 9.73478 2.5 10 2.5C10.2652 2.5 10.5196 2.60536 10.7071 2.79289L13.7071 5.79289C14.0976 6.18342 14.0976 6.81658 13.7071 7.20711C13.3166 7.59763 12.6834 7.59763 12.2929 7.20711L11 5.91421V13.5C11 14.0523 10.5523 14.5 10 14.5C9.44771 14.5 9 14.0523 9 13.5V5.91421L7.70711 7.20711C7.31658 7.59763 6.68342 7.59763 6.29289 7.20711Z" fill="#111827"></path>
                            </svg>Export
                        </button>
                    </div> -->
                </div>
            </div>
            <div class="card-body px-02 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                </th>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rental ID</span>
                                </th>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rental Date</span>
                                </th>
                                <th class="align-middle">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</span>
                                </th>
                                <th class="align-middle ">
                                    <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item</span>
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
                        <?php while ($data = mysqli_fetch_array($query_run)) : ?>
                            <tr>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">#<?= $data['rental_id'] ?></span>
                                </td>
                                <td class="align-middle">
                                    <p class="text-xs font-weight-bold mb-0">
                                        <?= date('d/m/y H:i:s', strtotime($data['created_at'])) ?>
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
                                        <h6 class="mb-0 text-sm font-weight-semibold"><?= $data['item_name'] ?></h6>
                                        <p class="text-sm text-secondary mb-0"><?= $data['item_type'] ?></p>
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
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $status = $data['rental_status'];
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
                                    <!-- <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalView<?= $data['rental_id'] ?>"><i class="fas fa-eye text-sm opacity-5"></i></a>
                                    <span style="margin: 0 10px;"></span> -->
                                    <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['rental_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                    <span style="margin: 0 10px;"></span>
                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['rental_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                </td>
                            </tr>


                            <!-- Modal Edit -->
                            <div class="modal fade" data-bs-backdrop="static" id="modalEdit<?= $data['rental_id'] ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg  modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditLabel">Edit Rental</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="rental_crud.php" method="POST">
                                                <input type="hidden" name="rental_id" value="<?= $data['rental_id'] ?>">
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
                                                        <label for="item" class="form-label">Item</label>
                                                        <select class="form-select" name="item_id" id="itemSelect" onchange="setRentalType()">
                                                            <option value="" selected disabled>Select Item</option>
                                                            <?php
                                                            if ($data['rental_type'] == 'dais') {
                                                                $dais_query = "SELECT dais_id AS item_id, name FROM bridal_dais";
                                                                $dais_query_run = mysqli_query($con, $dais_query);
                                                                while ($dais = mysqli_fetch_assoc($dais_query_run)) {
                                                                    $selected = ($dais['item_id'] == $data['item_id'] && $data['rental_type'] == 'dais') ? 'selected' : '';
                                                                    echo "<option value='{$dais['item_id']}' data-type='dais' {$selected}>{$dais['name']} (Dais)</option>";
                                                                }
                                                            } else {
                                                                $attire_query = "SELECT attire_id AS item_id, name FROM bridal_attire";
                                                                $attire_query_run = mysqli_query($con, $attire_query);
                                                                while ($attire = mysqli_fetch_assoc($attire_query_run)) {
                                                                    $selected = ($attire['item_id'] == $data['item_id'] && $data['rental_type'] == 'attire') ? 'selected' : '';
                                                                    echo "<option value='{$attire['item_id']}' data-type='attire' {$selected}>{$attire['name']} (Attire)</option>";
                                                                }
                                                            }


                                                            ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="rental_type" id="rentalType" value="<?php echo $data['rental_type']; ?>">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="total_payment" class="form-label">Total Payment (RM)</label>
                                                        <input type="number" class="form-control" name="total_payment" value="<?= $data['total_payment'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="event_date" class="form-label">Event Date</label>
                                                        <input type="date" class="form-control" name="event_date" value="<?= $data['event_date'] ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="return_date" class="form-label">Return Date</label>
                                                        <input type="date" class="form-control" name="return_date" value="<?= $data['return_date'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="event_loc" class="form-label">Event Location</label>
                                                    <input type="text" class="form-control" name="event_loc" value="<?= $data['event_loc'] ?>" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="deposit" class="form-label">Deposit (RM)</label>
                                                        <input type="number" class="form-control" name="deposit" value="<?= $data['deposit'] ?>" required>
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
                                                    <label for="remark" class="form-label">Remark</label>
                                                    <textarea class="form-control form-control-lg" name="remark" required><?= $data['remark'] ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" name="rental_status">
                                                        <option value="0" <?= ($data['rental_status'] == 0) ? 'selected' : ''; ?>>Upcoming</option>
                                                        <option value="1" <?= ($data['rental_status'] == 1) ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="2" <?= ($data['rental_status'] == 2) ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-0" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="updaterental">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal Edit -->

                            <!-- Modal Delete -->
                            <div class="modal fade" id="modalDelete<?= $data['rental_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['rental_id'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['rental_id'] ?>">Delete Confirmation</h1>
                                        </div>
                                        <form action="rental_crud.php" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="rental_id" value="<?= $data['rental_id'] ?>">
                                            <div class="modal-body">
                                                <div class="d-flex flex-column">
                                                    <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                    <span class="text-dark text-lg font-weight-bold text-center">Delete this data?</span><br>
                                                    <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-dark mb-0" name="deleterental">Confirm Delete</button>
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

<!-- Modal Add -->
<div class="modal fade" data-bs-backdrop="static" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddLabel">Add New Rental</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Rental Type Form -->
                <form action="rental_form.php" method="POST">
                    <div class="mb-3">
                        <label for="rental_type" class="form-label">Rental Type</label>
                        <select class="form-select" name="rental_type" id="rentalTypeSelect" required>
                            <option value="" selected disabled>Select Rental Type</option>
                            <option value="dais">Dais</option>
                            <option value="attire">Attire</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-dark mb-0">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Add -->

<?php include('includes/footer.php'); ?>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var itemSelect = document.getElementById('itemSelect');
        itemSelect.addEventListener('change', setRentalType);
    });

    function setRentalType() {
        var itemSelect = document.getElementById('itemSelect');
        var rentalType = itemSelect.options[itemSelect.selectedIndex].getAttribute('data-type');
        document.getElementById('rentalType').value = rentalType;

        // Debugging output
        console.log("Rental Type: " + rentalType);
        // alert("Rental Type Set: " + rentalType);
    }
</script>