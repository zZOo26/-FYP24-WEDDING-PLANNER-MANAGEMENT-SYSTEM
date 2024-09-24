<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Bridal Dais";
include('includes/header.php');

$message = "";

if (isset($_POST['check'])) {
    $date = $_POST['date'];

    // SQL query to check if the date is within 3 days before any event_date or within 3 days after any return_date
    $date_query = "
        SELECT * FROM rentals 
        WHERE rental_type = 'dais' 
        AND item_id = ? 
        AND (
            ? BETWEEN DATE_SUB(event_date, INTERVAL 5 DAY) AND return_date
            OR ? BETWEEN event_date AND DATE_ADD(return_date, INTERVAL 3 DAY)
        )
    ";

    $stmt = mysqli_prepare($con, $date_query);
    mysqli_stmt_bind_param($stmt, "iss", $_GET['dais_id'], $date, $date);
    mysqli_stmt_execute($stmt);
    $date_result = mysqli_stmt_get_result($stmt);

    if ($date_result) {
        if (mysqli_num_rows($date_result) > 0) {
            $message = "Not Available";
        } else {
            $message = "Available";
        }
    } else {
        $message = "Query execution failed.";
    }

    mysqli_stmt_close($stmt);
}



if (isset($_GET['dais_id'])) {
    $id = $_GET['dais_id'];
    $dais_data = getIDActive("bridal_dais", "dais_id", $id);
    $dais = mysqli_fetch_array($dais_data);

    if ($dais) {
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
                                            <a class="text-white" href="dais.php"><?= $page_title ?> / </a>
                                            <a class="text-white" href="#dais_detail"><?= $dais['name'] ?></a>
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
                                    <img class="w-100 border-radius-lg shadow-lg mx-auto" src="admin/uploads/<?= $dais['image'] ?>" alt="image">
                                </div>
                                <div class="col-lg-5 mx-auto">
                                    <h2 class="mt-lg-0 mt-4 text-gradient text-warning"><?= $dais['name'] ?></h2>
                                    <!-- <div class="rating">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                                    </div> -->
                                    <br>
                                    <h6 class="mb-0 mt-3">Price</h6>
                                    <h4>RM<?= $dais['normal_price'] ?></h4>
                                    <br>
                                    <h6 class="mt-4">Description</h6>
                                    <p><?= $dais['dais_desc'] ?></p>
                                    <br>
                                    <h6 class="text-dark mt-4">Items:</h6>
                                    <ul>
                                        <?php
                                        // Explode the concatenated items into an array
                                        $items = explode(', ', $dais['items']);
                                        // Iterate over the array and display each accessory as a list item
                                        foreach ($items as $accessory) {
                                            echo "<li>$accessory</li>";
                                        }
                                        ?>
                                    </ul>
                                    <br>
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="date" class="form-control" name="date" id="dateInput" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '' ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-gradient btn-dark" name="check">Check Availability</button>
                                            </div>
                                        </div>
                                        <?php
                                        if ($message == 'Available') {
                                            // Append date to checkout link only if available
                                            $checkout_link = "dais_checkout.php?dais_id={$dais['dais_id']}&date={$date}";
                                        ?>
                                            <span class="text-sm opacity-8 text-success"><?= $message ?></span>
                                        <?php
                                        } else {
                                            $checkout_link = "#";
                                        ?>
                                            <span class="text-sm opacity-8 text-danger"><?= $message ?></span>
                                        <?php
                                        }
                                        ?>

                                    </form>
                                    <div class="row mt-4">
                                        <div class="col-lg-5">
                                            <a href="<?= $checkout_link ?>" class="btn  btn-gradient btn-warning mb-2 me-2 mt-4 mt-md-2 <?= $message !== 'Available' ? 'disabled' : '' ?>">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php
    } else {
        echo 'Product not found.';
    }
} else {
    echo 'Something went wrong.';
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