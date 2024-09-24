<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Promotions";
include('includes/header.php');
?>

<section>
    <?php
    // Include database connection and other necessary files

    // Fetch the latest 7 rows from the 'packages' table
    $query = "SELECT * FROM promotion ORDER BY created_at DESC LIMIT 7";
    $result = mysqli_query($con, $query);

    // Check if there are any rows in the result
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['promo_name'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
    ?>

            <div class="col-md-4 mt-md-0 mt-4">
                <a href="menu_detail.php">
                    <div class="card shadow-lg move-on-hover min-height-160 min-height-160">
                        <img class="w-100 my-auto" src="<?php echo $start_date; ?>" alt="<?php echo $title; ?>">
                    </div>
                    <div class="mt-2 ms-2">
                        <h6 class="mb-0"><?php echo $title; ?></h6>
                        <p class="text-secondary text-sm">RM <?php echo $end_date; ?></p>
                    </div>
                </a>
            </div>

    <?php
        }
    }
    ?>
</section>


<?php include('includes/footer.php') ?>