<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Catering Menu";
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
                                    <a class="text-white" href="catering.php"><?= $page_title ?> </a>
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
        <!-- Filter by categories -->
        <div class="row">
            <div class="col-lg-3">
                <div class="position-sticky pb-lg-5 pb-3 mt-lg-0 mt-5 ps-2" style="top: 100px">
                    <p class="text-secondary text-sm">Filter by categories:</p>
                    <?php
                    // Fetch all active menu categories
                    $category = getAllActive("menu_category");

                    if (mysqli_num_rows($category) > 0) {
                        foreach ($category as $ctg) {
                            $active_class = (isset($_GET['menu_ctg_id']) && $_GET['menu_ctg_id'] == $ctg['menu_ctg_id']) ? 'btn-warning' : 'btn-outline-secondary';
                    ?>
                            <div class="d-flex flex-column mb-2">
                                <a href="?menu_ctg_id=<?= $ctg['menu_ctg_id'] ?>" class="btn btn-sm <?= $active_class ?> mb-0 me-1 mt-2 mt-md-0"><?= $ctg['ctg_name'] ?></a>
                            </div>
                    <?php
                        }
                    } else {
                        echo "No categories available";
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row mt-3">
                    <?php
                    // Prepare the base SQL query for menus
                    $query = "SELECT * FROM menus";
                    $where_conditions = array();
                    $params = array();

                    // Add category filter condition
                    if (isset($_GET['menu_ctg_id']) && !empty($_GET['menu_ctg_id'])) {
                        $where_conditions[] = "menu_ctg_id = ?";
                        $params[] = $_GET['menu_ctg_id'];
                    }

                    // Add WHERE clause if there are conditions
                    if (!empty($where_conditions)) {
                        $query .= " WHERE " . implode(" AND ", $where_conditions);
                    }

                    // Fetch menus based on the constructed query
                    $stmt = mysqli_prepare($con, $query);

                    // Bind parameters for the query
                    if (!empty($params)) {
                        $types = str_repeat('s', count($params)); // Assuming all params are strings
                        mysqli_stmt_bind_param($stmt, $types, ...$params);
                    }

                    // Execute the query
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    // Display menus
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($item = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="col-md-4 mt-md-0 mb-3">
                                <a >
                                    <div class="card shadow-lg move-on-hover min-height-160 max-height-160">
                                        <img class="w-100 my-auto" src="admin/uploads/<?= $item['menu_img'] ?>" alt="">
                                    </div>
                                    <div class="mt-2 ms-2">
                                        <h6 class="mb-0"><?= $item['menu_name'] ?></h6>
                                        <p class="text-secondary text-sm"><?= $item['description'] ?></p>
                                    </div>
                                    <div class="align-item-bottom mt-2 ms-2">
                                        <p class="text-dark text-sm text-end">RM <?= $item['price_per_pax'] * 50 ?> / 50pax</p>
                                    </div>
                                </a>
                            </div>
                    <?php
                        }
                    } else {
                        echo "No menus available";
                    }

                    // Close statement and connection
                    mysqli_stmt_close($stmt);
                    mysqli_close($con);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php') ?>