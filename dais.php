<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Bridal dais";
include('includes/header.php');
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
                                    <a class="text-white" href="dais.php"><?= $page_title ?> </a>
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
    <div class="container mt-sm-5 mt-3">
        <div class="row">
            <div class="col-lg-3">
                <div class="position-sticky pb-lg-5 pb-3 mt-lg-0 mt-5 ps-2" style="top: 100px">
                    <div class="container">
                        <form method="GET" action="">
                            <p class="text-secondary text-sm">Filter by date:</p>
                            <div class="row">
                                <div class="d-flex">
                                    <div class="col-md-10" style="margin-right: 3px;">
                                        <input type="date" class="form-control form-control-m" name="date" id="dateInput" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" style="padding-left: 0.8rem; padding-right:0.8rem;">
                                            <span><i class="fas fa-search text-secondary text-sm"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 mb-3">
                                <p class="text-secondary text-sm">Filter by categories:</p>
                                <?php
                                $category = getCategory("bridal_dais");

                                if (mysqli_num_rows($category) > 0) {
                                    foreach ($category as $ctg) {
                                        $active_class = (isset($_GET['category']) && $_GET['category'] == $ctg['category']) ? 'btn-warning' : 'btn-outline-secondary';
                                ?>
                                        <div class="d-flex flex-column mb-2">
                                            <a href="?category=<?= urlencode($ctg['category']) ?>&date=<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>" class="btn btn-sm <?= $active_class ?> mb-0 me-1 mt-2 mt-md-0"><?= htmlspecialchars($ctg['category']) ?></a>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "No data available";
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <?php
                    $category = isset($_GET['category']) ? $_GET['category'] : '';
                    $selected_date = isset($_GET['date']) ? $_GET['date'] : '';

                    $query = "SELECT * FROM bridal_dais";
                    $params = [];
                    $types = "";

                    if ($category) {
                        $query .= " WHERE category = ?";
                        $params[] = $category;
                        $types .= "s";
                    }

                    if ($selected_date) {
                        if ($category) {
                            $query .= " AND";
                        } else {
                            $query .= " WHERE";
                        }
                        $query .= " dais_id NOT IN (SELECT item_id FROM rentals WHERE rental_type = 'dais' AND (
                            ? BETWEEN DATE_SUB(event_date, INTERVAL 5 DAY) AND return_date
                            OR ? BETWEEN event_date AND DATE_ADD(return_date, INTERVAL 3 DAY) 
                        ))";
                        $params[] = $selected_date;
                        $params[] = $selected_date;
                        $types .= "ss";
                    }

                    $query .= " ORDER BY created_at DESC";

                    $stmt = $con->prepare($query);

                    if (!$stmt) {
                        die("Prepare failed: " . $con->error);
                    }

                    if (!empty($params)) {
                        $stmt->bind_param($types, ...$params);
                    }

                    $stmt->execute();
                    $dais = $stmt->get_result();

                    if (mysqli_num_rows($dais) > 0) {
                        foreach ($dais as $item) {
                    ?>
                            <div class="col-md-4 mt-md-0">
                                <a href="dais_detail.php?dais_id=<?= urlencode($item['dais_id']) ?>">
                                    <div class="card shadow-lg move-on-hover min-height-160 max-height-160">
                                        <img class="w-100 my-auto text-secondary" src="admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="image">
                                    </div>
                                    <div class="mt-2 ms-2">
                                        <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                        <p class="text-secondary text-sm">RM <?= htmlspecialchars($item['normal_price']) ?></p>
                                    </div>
                                </a>
                            </div>
                    <?php
                        }
                    } else {
                        echo "No data available";
                    }

                    $stmt->close();
                    ?>

                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php') ?>

<script>
    // Set the minimum date to today
    document.addEventListener("DOMContentLoaded", function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('dateInput').setAttribute('min', today);
    });
</script>
