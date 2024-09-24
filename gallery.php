<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Gallery";
include('includes/header.php');
?>

<section>
    <div class="col-md-12">
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
                                        <a class="text-white" href="gallery.php"><?= $page_title ?> </a>
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
        <div class="container  mt-sm-5 mt-3">
            <div class="row">
                <?php
                $gallery = getAllActive("gallery");

                if (mysqli_num_rows($gallery) > 0) {
                    $carouselIndicators = '';
                    $carouselItems = '';
                    $index = 0;

                    foreach ($gallery as $item) {
                        $activeClass = $index === 0 ? 'active' : '';
                        $carouselIndicators .= "<li data-bs-target='#imageCarousel' data-bs-slide-to='$index' class='$activeClass'></li>";
                        $carouselItems .= "
                            <div class='carousel-item $activeClass'>
                                <img class='d-block w-100' src='admin/uploads/{$item['img_file']}' alt='Image' data-description='{$item['description']}'>
                            </div>";
                        $index++;
                ?>
                        <div class="col-md-4 mt-md-4">
                            <div class="card shadow-lg move-on-hover min-height-100 max-height-250">
                                <img class="w-100 my-auto" src="admin/uploads/<?= $item['img_file'] ?>" alt="image" data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-slide-to="<?= $index - 1 ?>" data-description="<?= $item['description'] ?>">
                            </div>
                            <div class="mt-2 ms-2">
                                <!-- <img class="w-100 my-auto" src="admin/uploads/<?= $item['img_file'] ?>" alt="image" data-toggle="modal" data-target="#imageModal" data-slide-to="<?= $index - 1 ?>" data-description="<?= $item['description'] ?>"> -->
                                <p> </p>
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

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <ol class="carousel-indicators">
                            <?= $carouselIndicators ?>
                        </ol>
                        <div class="carousel-inner">
                            <?= $carouselItems ?>
                        </div>
                        <a class="carousel-control-prev" href="#imageCarousel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#imageCarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div id="imageDescription" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php') ?>

<script>
    $('#imageModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var slideTo = button.data('slide-to');
        var description = button.data('description');

        $('#imageCarousel').carousel(slideTo);
        $('#imageDescription').text(description);
    });

    $('#imageCarousel').on('slid.bs.carousel', function(event) {
        var activeItem = $(event.relatedTarget);
        var description = activeItem.find('img').data('description');

        $('#imageDescription').text(description);
    });
</script>