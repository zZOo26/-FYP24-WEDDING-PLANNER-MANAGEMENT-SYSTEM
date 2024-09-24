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

$page_title = 'Customer Appointments';
include('includes/header.php');
?>
<div class="mt-4 row">
    <!-- Calendar -->
    <div class="col-12 col-xl mb-4">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <h6 class="font-weight-semibold text-lg mb-0">Event Calendar</h6>
                <p class="text-sm">See events on a calendar</p>
            </div>
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                // Package Bookings events
                <?php
                $query = "SELECT a.*, b.firstname, b.lastname, b.phoneNo, c.*, d.*
                        FROM package_bookings a
                        INNER JOIN customers b ON a.cust_id = b.cust_id
                        INNER JOIN packages c ON a.pkg_id = c.pkg_id
                        INNER JOIN event_slot d ON a.slot_id = d.slot_id"; // Fixed typo here
                $query_run = mysqli_query($con, $query);
                while ($data = mysqli_fetch_array($query_run)) {
                    $start = $data['date'] . 'T' . $data['time'];
                    $end = date('Y-m-d\TH:i:s', strtotime($start . ' +1 hour')); // Assuming each appointment is 1 hour
                    echo "{";
                    echo "title: '" . $data['pkg_name'] . "',";
                    echo "start: '" . $start . "',";
                    echo "end: '" . $end . "',";
                    echo "description: 'Customer: " . $data['firstname'] . " " . $data['lastname'] . " \\n Location: " . $data['location'] . "',";
                    echo "color: 'blue',"; // Set color for package bookings
                    echo "extendedProps: {";
                    echo "eventDate: '" . $data['date'] . "',";
                    echo "pkgName: '" . $data['pkg_name'] . "',";
                    echo "pax: '" . $data['pax'] . "',";
                    echo "slot: '" . $data['slot'] . "',";
                    echo "startTime: '" . $data['time'] . "'";
                    echo "}";
                    echo "},";
                }
                ?>
            ],
            eventClick: function(info) {
                alert(
                    'Event Date: ' + info.event.extendedProps.eventDate + '\n' +
                    'Package Name: ' + info.event.extendedProps.pkgName + '\n' +
                    'Pax: ' + info.event.extendedProps.pax + '\n' +
                    'Slot: ' + info.event.extendedProps.slot + '\n' +
                    'Start Time: ' + info.event.extendedProps.startTime
                );
            }
        });

        calendar.render();
    });
</script>
