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

$page_title = 'Customer Appointments';
include('includes/header.php');
?>

<div class="container">
    <!-- Calendar -->
    <div class="col-12 col-xl mb-4">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <h6 class="font-weight-semibold text-lg mb-0">Appointment Calendar</h6>
                <p class="text-sm">See appointments on a calendar</p>
            </div>
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- app table -->
        <div class="col-12 col-xl mb-4">
            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Customers' Appointments</h6>
                            <p class="text-sm">See information about all appointments</p>
                        </div>
                        <div class="ms-auto d-flex justify-content-end">
                            <!-- Filter by status -->
                            <form action="" method="GET">
                                <div class="d-flex align-items-center">
                                    <select name="status_filter" class="form-select form-select-sm me-2 mb-3 px-5">
                                        <option value="">All</option>
                                        <option value="0" <?= isset($_GET['status_filter']) && $_GET['status_filter'] === '0' ? 'selected' : '' ?>>Upcoming</option>
                                        <option value="1" <?= isset($_GET['status_filter']) && $_GET['status_filter'] === '1' ? 'selected' : '' ?>>Completed</option>
                                        <option value="2" <?= isset($_GET['status_filter']) && $_GET['status_filter'] === '2' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-dark">Filter</button>
                                </div>
                            </form>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center ms-2" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                <span class="btn-inner--icon"><i class="fas fa-calendar d-block me-2"></i></span>
                                <span class="btn-inner--text">Add appointment</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Appointment</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Purpose</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</span>
                                    </th>
                                    <th class="align-middle text-center">
                                        <span class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $no = 1;
                            $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '0';
                            $query = "SELECT * FROM appointments INNER JOIN customers ON appointments.cust_id = customers.cust_id";

                            if ($status_filter !== '') {
                                $query .= " WHERE status = '$status_filter'";
                            }

                            $query .= " ORDER BY date , time";

                            $query_run = mysqli_query($con, $query);

                            while ($data = mysqli_fetch_array($query_run)) :
                            ?>
                                <tr>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= $no++ ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <h6 class="text-sm text-dark font-weight-semibold mb-0"><?= $data['firstname'] ?> <?= $data['lastname'] ?></h6>
                                        <p class="text-sm text-secondary mb-0">+60<?= $data['phoneNo'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <h6 class="text-sm text-dark font-weight-semibold mb-0">
                                            <?php
                                            // Convert slot_date to d/m/y format
                                            $slot_date = DateTime::createFromFormat('Y-m-d', $data['date']);

                                            // Check if slot_date is valid
                                            if ($slot_date !== false) {
                                                $formatted_slot_date = $slot_date->format('d/m/Y');
                                                echo $formatted_slot_date;
                                            } else {
                                                echo "Invalid date";
                                            }
                                            ?>
                                        </h6>
                                        <p class="text-sm text-secondary  mb-0">
                                            <?php
                                            // Convert start_time and end_time to 12-hour format with AM/PM
                                            $time = DateTime::createFromFormat('H:i:s', $data['time']);

                                            // Format the time
                                            $format_time = $time->format('h:i A');

                                            echo $format_time;
                                            ?>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm text-dark font-weight-semibold mb-0"><?= $data['purpose'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm text-dark font-weight-semibold mb-0"><?= $data['location'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php
                                        $status = $data['status'];
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
                                        <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['app_id'] ?>"><i class="fas fa-edit text-sm opacity-5"></i></a>
                                        <span style="margin: 0 10px;"></span>
                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $data['app_id'] ?>"><i class="fas fa-trash text-sm opacity-5"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="modalEdit<?= $data['app_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditLabel<?= $data['app_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalEditLabel<?= $data['app_id'] ?>">Edit appointment</h1>
                                            </div>
                                            <form action="app_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="app_id" value="<?= $data['app_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Customer Name</label>
                                                        <input type="text" class="form-control form-control-lg" name="cust_name" value="<?= $data['firstname'] ?> <?= $data['lastname'] ?>" readonly>
                                                    </div>
                                                    <div class=" mb-3">
                                                        <label class="form-label">Contact No.</label>
                                                        <input type="text" class="form-control form-control-lg" name="phoneNo" value="+60<?= $data['phoneNo'] ?>" readonly>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Date</label>
                                                            <input type="date" class="form-control form-control-lg" name="date" value="<?= $data['date'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Time</label>
                                                            <input type="time" class="form-control form-control-lg" name="time" value="<?= $data['time'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Purpose</label>
                                                            <input type="text" class="form-control form-control-lg" name="purpose" value="<?= $data['purpose'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Location</label>
                                                            <input type="text" class="form-control form-control-lg" name="location" value="<?= $data['location'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class=" mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select class="form-select" name="status">
                                                            <option value="0" <?= ($data['status'] == 0) ? 'selected' : ''; ?>>Upcoming</option>
                                                            <option value="1" <?= ($data['status'] == 1) ? 'selected' : ''; ?>>Completed</option>
                                                            <option value="2" <?= ($data['status'] == 2) ? 'selected' : ''; ?>>Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="editapp">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit -->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="modalDelete<?= $data['app_id'] ?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel<?= $data['app_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title text-secondary text-sm font-weight-semibold fs-5" id="modalDeleteLabel<?= $data['app_id'] ?>">Delete Confirmation</h1>
                                            </div>
                                            <form action="app_crud.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="app_id" value="<?= $data['app_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger font-weight-bold text-center"><i class="fas fa-exclamation-circle text-9xl font-weight-bolder opacity-5"></i></span><br>
                                                        <span class="text-dark text-lg font-weight-bold text-center">Are you sure?</span><br>
                                                        <span class="text-secondary text-sm text-center">You won't be able to revert this data!</span><br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0" name="deleteapp">Confirm Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Delete -->
                            <?php endwhile; ?>
                        </table>

                        <!-- Modal Add -->
                        <div class="modal fade modal-lg" id="modalAdd" data-bs-backdrop="static" role="dialog" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="modalAddLabel">Add appointment</h1>
                                    </div>
                                    <form action="app_crud.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="customer" class="form-label">Customer</label>
                                                <select class="form-select" name="cust_id">
                                                    <!-- Populate with customer data -->
                                                    <?php
                                                    $customer_query = "SELECT cust_id, firstname, lastname FROM customers";
                                                    $customer_query_run = mysqli_query($con, $customer_query);
                                                    while ($customer = mysqli_fetch_assoc($customer_query_run)) {
                                                        echo "<option value='{$customer['cust_id']}'>{$customer['firstname']} {$customer['lastname']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <span class="form-label">Location</span>
                                                <input class="form-control" name="location" type="text" placeholder="Enter Location">
                                            </div>
                                            <div class="form-group">
                                                <span class="form-label">Purpose</span>
                                                <input class="form-control" name="purpose" type="text" placeholder="Enter purpose">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="form-label">Date</span>
                                                        <input class="form-control" name="date" type="date" id="dateInput" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '' ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6"> <!-- Use col-sm-6 to make them side by side on larger screens -->
                                                    <div class="form-group">
                                                        <span class="form-label">Time</span>
                                                        <input class="form-control time" name="time" type="time" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-white mb-0" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="addapp">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Add -->

                    </div>
                </div>
            </div>
        </div>
        <!-- end table appointment -->
    </div>
</div>




<?php include('includes/footer.php') ?>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                <?php
                $query = "SELECT * FROM appointments INNER JOIN customers ON appointments.cust_id = customers.cust_id WHERE status != 2  ORDER BY date, time";
                $query_run = mysqli_query($con, $query);
                while ($data = mysqli_fetch_array($query_run)) {
                    $start = $data['date'] . 'T' . $data['time'];
                    $end = date('Y-m-d\TH:i:s', strtotime($start . ' +1 hour')); // Assuming each appointment is 1 hour
                    echo "{";
                    echo "title: '" . $data['purpose'] . "',";
                    echo "start: '" . $start . "',";
                    echo "end: '" . $end . "',";
                    echo "description: 'Customer: " . $data['firstname'] . " " . $data['lastname'] . " \\n Location: " . $data['location'] . "',";
                    echo "},";
                }
                ?>
            ],
            eventClick: function(info) {
                alert('Purpose: ' + info.event.title + '\n' + 'Description: ' + info.event.extendedProps.description);
            }
        });

        calendar.render();
    });
</script>