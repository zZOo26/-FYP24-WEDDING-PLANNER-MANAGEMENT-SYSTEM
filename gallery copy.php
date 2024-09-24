<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Gallery";
include('includes/header.php');
?>


<section class="my-5 py-5">
    <div class="col-md-12">
        <div class="row bg-light">
            <div class="row justify-content-center text-center my-sm-5">
                <div class="col-lg-6">
                    <h1 class="text-dark mb-0" style="font-size: 50px;"><?= $page_title ?></h1>
                    <!-- <p class="lead">Explore our events gallery, where memories shine</p> -->
                    <div class="lead mt-2">
                        <p>
                            <a href="home.php">Home / </a>
                            <!-- <a href="#" style="pointer-events: none;">Services / </a> -->
                            <a href="gallery.php"><?= $page_title ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-sm-5 mt-3">
            <div class="row">
                <?php
                $gallery = getAllActive("gallery");

                if (mysqli_num_rows($gallery) > 0) {
                    foreach ($gallery as $item) {
                ?>
                        <div class="col-md-4 mt-md-4">
                            <div class="card shadow-lg move-on-hover min-height-100 max-height-250">

                                <img class="w-100 my-auto" src="admin/uploads/<?= $item['img_file'] ?>" alt="image">

                            </div>
                        </div>

                <?php
                    }
                } else {
                    echo "No data available";
                }
                ?>
            </div>
        </div>
    </div>

</section>


<?php include('includes/footer.php') ?>