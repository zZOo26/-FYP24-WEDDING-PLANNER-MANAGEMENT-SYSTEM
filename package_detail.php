<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Package Detail";
include('includes/header.php');

$message = "";
$isAvailable = false; // Flag to track availability status

if (isset($_POST['check'])) {
    $date = $_POST['date'];
    $slot_id = $_POST['slot_id'];

    // SQL query to check if the date is available for booking
    $date_query = "
        SELECT * FROM package_bookings 
        WHERE  event_date = ? AND slot_id = ?
    ";

    $stmt = mysqli_prepare($con, $date_query);
    mysqli_stmt_bind_param($stmt, "si", $date, $slot_id);
    mysqli_stmt_execute($stmt);
    $date_result = mysqli_stmt_get_result($stmt);

    if ($date_result) {
        if (mysqli_num_rows($date_result) > 0) {
            $message = "Not Available";
        } else {
            $message = "Available";
            $isAvailable = true; // Set availability flag to true
        }
    } else {
        $message = "Query execution failed.";
    }

    mysqli_stmt_close($stmt);
}


if (isset($_GET['pkg_id'])) {
    $id = $_GET['pkg_id'];
    $pkg_data = getPackage($id);
    $package = mysqli_fetch_array($pkg_data);

    if ($package) {
        // Initialize an array to store menu items grouped by sections
        $menu_by_sections = array();

        // Fetch all menu items with their sections from the database
        while ($row = mysqli_fetch_assoc($pkg_data)) {
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

            <section>
                <div class="section-shaped my-0 skew-separator skew-mini">
                    <div class="page-header min-vh-65" style="background-image: url('assets/img/IMG_2297.jpg');"><span class="mask bg-warning opacity-1"></span>
                        <div class="container">
                            <div class="header-body text-center mb-7">
                                <div class="row justify-content-center text-center my-sm-5">
                                    <div class="col-lg-6">
                                        <h1 class="text-white mb-0" style="font-size: 50px;"><?= $page_title ?></h1>
                                        <div class="lead mt-2">
                                            <p>
                                                <a class="text-white" href="index.php">Home / </a>
                                                <a class="text-white" href="#" style="pointer-events: none;">Services / </a>
                                                <a class="text-white" href="packages.php"><?= $page_title ?> / </a>
                                                <a class="text-white" href="#package_detail"><?= $package['pkg_name'] ?></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="position-relative overflow-hidden" style="height:36px;margin-top:-33px;">
                        <div class="w-full absolute bottom-0 start-0 end-0" style="transform: scale(2);transform-origin: top center;color: #fff;">
                            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row mt-n8 pb-4 p-3 mx-sm-0 mx-1 position-relative">
                        <div class="card shadow-lg blur">
                            <div class="card-body">
                                <h5 class="mb-4"><?= $page_title ?> Details</h5>
                                <div class="row">
                                    <div class="col-xl-5 col-lg-6 text-center">
                                        <img class="w-100 border-radius-lg shadow-lg mx-auto" src="admin/uploads/<?= $package['pkg_img'] ?>" alt="image">
                                    </div>
                                    <div class="col-lg-5 mx-auto">
                                        <h2 class="mt-lg-0 mt-4 text-gradient text-warning"><?= $package['pkg_name'] ?></h2>
                                        <br>
                                        <h6 class="mb-0 mt-3">Price</h6>
                                        <h4>RM<?= $package['pkg_price'] ?></h4>
                                        <br>
                                        <h6 class="mt-4">Description</h6>
                                        <p><?= $package['pkg_desc'] ?></p>
                                        <br>
                                        <h6 class="mb-0 mt-3">Duration</h6>
                                        <p><?= $package['duration'] ?> Hours</p>
                                        <br>
                                        <h6 class="mt-4">Package Includes:</h6>
                                        <ul>
                                            <?php if ($package['eventspace_inc'] == 1) : ?>
                                                <li>Eventspace</li>
                                            <?php endif; ?>
                                            <?php if ($package['attire_inc'] == 1) : ?>
                                                <li>Baju Pengantin (L/P)</li>
                                            <?php endif; ?>
                                            <?php if ($package['dais_inc'] == 1) : ?>
                                                <li>Pelamin</li>
                                            <?php endif; ?>
                                            <?php if ($package['eventhost_inc'] == 1) : ?>
                                                <li>Event Host</li>
                                            <?php endif; ?>
                                            <?php if ($package['makeup_inc'] == 1) : ?>
                                                <li>Makeup</li>
                                            <?php endif; ?>
                                            <?php if ($package['photographer_inc'] == 1) : ?>
                                                <li>Photographer</li>
                                                <ul>
                                                    <li>Shoot, Edit, Copy</li>
                                                    <li>Photo Album</li>
                                                    <li>Free Outdoor (T&C Applied)</li>
                                                </ul>
                                            <?php endif; ?>
                                        </ul>

                                        <br>
                                        <h6 class="text-dark mt-4">Catering Menus:</h6>
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
                                            ?>
                                        </ul>
                                        <br>
                                        <?php if ($package['free_items'] != 'N/A') : ?>
                                            <h6 class="mt-4">Free</h6>
                                            <ul>
                                                <?php
                                                $items = explode(', ', $package['free_items']);
                                                echo "<li>" . implode('</li><li>', $items) . "</li>";
                                                ?>
                                            </ul>
                                        <?php endif; ?>

                                        <br>

                                        <form method="POST">
                                            <label for="">Check Availability</label>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="date" class="form-control" name="date" id="dateInput" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '' ?>" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <select class="form-select form-select" name="slot_id" id="slotSelect" required>
                                                        <option value="" selected disabled>Select Slot</option>
                                                        <?php
                                                        // Fetch the selected slot ID from the form submission
                                                        $selected_slot_id = isset($_POST['slot_id']) ? $_POST['slot_id'] : '';

                                                        $slots = mysqli_query($con, "SELECT * FROM event_slot");
                                                        if (mysqli_num_rows($slots) > 0) {
                                                            foreach ($slots as $slot) {
                                                                // Assuming $slot['start_time'] is in 24-hour format (e.g., '14:00:00')
                                                                $start_time_24 = $slot['start_time'];
                                                                $start_time_12 = date("g:i A", strtotime($start_time_24));
                                                        ?>
                                                                <option value="<?= $slot['slot_id'] ?>" <?= $slot['slot_id'] == $selected_slot_id ? 'selected' : '' ?>><?= $slot['slot'] ?>: <?= $start_time_12 ?></option>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo "<option disabled>No slot available</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-gradient btn-dark" name="check">Check</button>
                                                </div>
                                            </div>


                                            <?php if ($message == 'Available') { ?>
                                                <span class="text-sm opacity-8 text-success"><?= $message ?></span>
                                                <div class="mb-3">
                                                    <label class="form-label">Total Pax</label>
                                                    <input type="number" class="form-control" name="total_pax" id="totalpax" value="<?= isset($_POST['total_pax']) ? htmlspecialchars($_POST['total_pax']) : '' ?>">
                                                </div>
                                            <?php } else { ?>
                                                <span class="text-sm opacity-8 text-danger"><?= $message ?></span>
                                            <?php } ?>

                                        </form>

                                        <div class="row mt-5">
                                            <div class="col-lg-5">
                                                <button class="btn btn-gradient btn-warning mb-0 mt-lg-auto w-100" type="button" id="bookNowButton" <?= $isAvailable ? '' : 'disabled' ?>>Book Now</button>
                                            </div>
                                        </div>

                                        <script>
                                            document.getElementById('bookNowButton').addEventListener('click', function() {
                                                var date = document.getElementById('dateInput').value;
                                                var slot = document.getElementById('slotSelect').value;
                                                var totalpax = document.getElementById('totalpax').value;
                                                var pkgId = <?= $package['pkg_id'] ?>;
                                                if (date && slot && totalpax) {
                                                    var checkoutLink = `package_booking.php?pkg_id=${pkgId}&date=${date}&slot=${slot}&total_pax=${totalpax}`;
                                                    window.location.href = checkoutLink;
                                                } else {
                                                    alert('Please select a date and slot and enter the total pax.');
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

<?php
        }
    } else {
        echo "Package not found.";
    }
} else {
    echo "Package ID not specified.";
}

include('includes/footer.php');
?>

<script>
    // Set the minimum date to today
    document.addEventListener("DOMContentLoaded", function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('dateInput').setAttribute('min', today);
    });
</script>