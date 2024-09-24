<?php
session_start();
include('dbcon.php');
include('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['cust_id'])) {
    // Capture the current URL and redirect to login with it as a query parameter
    $redirect_url = $_SERVER['REQUEST_URI'];
    header("Location: login.php?redirect=" . urlencode($redirect_url));
    die;
}

$user_data = check_login($con);

$page_title = "Book Appointment";
include('includes/header.php');

// Fetch all available appointment slots
$slots = array();
$query = "SELECT slot_id, slot_date, start_time, end_time, purpose FROM app_slot WHERE status = 0 AND slot_date > CURDATE() ORDER BY slot_date, start_time";
$query_run = mysqli_query($con, $query);
if ($query_run) {
    while ($data = mysqli_fetch_array($query_run)) {
        $slots[$data['purpose']][] = $data;
    }
}
?>

<header>
    <div class="page-header min-vh-85">
        <div>
            <img class="position-absolute fixed-top ms-auto w-50 h-100 z-index-0 d-none d-sm-none d-md-block border-radius-section border-top-end-radius-0 border-top-start-radius-0 border-bottom-end-radius-0" src="assets/img/IMG_1011.jpg" alt="image">
        </div>
        <div class="container my-3 py-3">
            <div class="row">
                <div class="col-lg-7 d-flex justify-content-center flex-column">
                    <div class="card d-flex blur justify-content-center p-4 shadow-lg my-sm-0 my-sm-6 mt-8 mb-5">
                        <div class="text-center">
                            <h3 class="text-warning">Book Your Appointment Today!</h3>
                            <p class="mb-0">
                                We are excited to help you with your wedding planning.
                                To book your appointment, please fill out our appointment form or contact us directly.
                            </p>
                        </div>
                        <form method="post" action="add-app.php">
                            <div class="card-body pb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Appointment Purpose</label>
                                        <div class="input-group mb-4">
                                            <select class="form-control" name="purpose" id="purpose">
                                                <option value="">Select Purpose</option>
                                                <?php
                                                foreach ($slots as $purpose => $purpose_slots) {
                                                    echo "<option value='$purpose'>$purpose</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ps-md-2">
                                        <label>Available Appointment Slots</label>
                                        <div class="input-group mb-4">
                                            <select class="form-control" name="slot" id="slot" required>
                                                <option value="">Select a slot</option>
                                            </select>
                                            <!-- Add a hidden input field for slot_id -->
                                            <input type="hidden" name="slot_id" id="slot_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn bg-warning mt-3 mb-0 text-white" name="bookapp">Book Appointment</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<?php include('includes/footer.php') ?>
<script>
    $(document).ready(function() {
        $('#purpose').change(function() {
            var purpose = $(this).val();
            var slots = <?php echo json_encode($slots); ?>;
            var purpose_slots = slots[purpose];
            $('#slot').empty().append('<option value="">Select a slot</option>');
            if (purpose_slots) {
                purpose_slots.forEach(function(slot) {
                    $('#slot').append('<option value="' + slot.slot_id + '">' + formatDate(slot.slot_date) + ' : ' + formatAMPM(slot.start_time) + ' - ' + formatAMPM(slot.end_time) + '</option>');
                });
            }
        });

        // Update the hidden input field (slot_id) when the user selects an appointment slot
        $('#slot').change(function() {
            var selectedSlotId = $(this).val();
            $('#slot_id').val(selectedSlotId); // Set the value of the hidden input field
        });
    });


    function formatDate(date) {
        var formattedDate = new Date(date);
        var day = formattedDate.getDate();
        var month = formattedDate.getMonth() + 1; // Months are zero based
        var year = formattedDate.getFullYear();
        return day + '/' + month + '/' + year;
    }

    function formatAMPM(time) {
        var hours = time.split(':')[0];
        var minutes = time.split(':')[1];
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    }
</script>