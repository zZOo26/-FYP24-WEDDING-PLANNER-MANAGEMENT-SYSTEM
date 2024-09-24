<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Homepage";
include('includes/header.php');


?>

<!-- start welcome hero section -->
<header class="header-2">
  <div class="page-header min-vh-85" style="background-image: url('assets/img/IMG_2297.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 text-center mx-auto">
          <h1 class="text-white pt-3 mt-n5">Your Dream Wedding, Our Affordable Plans</h1>
          <p class="lead text-white mt-3">Cherish the joy of your dream wedding with our budget-friendly plans. <br /> Say 'I do' without compromising on style, quality, or the magic of the moment. </p>
        </div>
      </div>
    </div>

    <div class="position-absolute w-100 z-index-1 bottom-0">
      <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 40" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
          <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="moving-waves">
          <use xlink:href="#gentle-wave" x="48" y="-1" fill="rgba(255,255,255,0.40" />
          <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.35)" />
          <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.25)" />
          <use xlink:href="#gentle-wave" x="48" y="8" fill="rgba(255,255,255,0.20)" />
          <use xlink:href="#gentle-wave" x="48" y="13" fill="rgba(255,255,255,0.15)" />
          <use xlink:href="#gentle-wave" x="48" y="16" fill="rgba(255,255,255,0.95" />
        </g>
      </svg>
    </div>
  </div>
</header>
<!-- end welcome hero section -->
<br>

<!-- card section -->
<section class="pt-3 pb-4" id="count-stats">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 z-index-2 border-radius-xl mt-n10 mx-auto py-2 blur shadow-blur ">
        <div class="row">
          <div class="col-md-4 position-relative">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-warning"><span id="state1" countTo="5">0</span>+</h1>
              <h5 class="mt-3">All In Packages</h5>
              <p class="text-sm">Event spaces, attires, dais, food, makeup, photos—you're covered.</p>
            </div>
            <hr class="vertical warning">
          </div>
          <div class="col-md-4 position-relative">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-warning"> <span id="state2" countTo="15">0</span>+</h1>
              <h5 class="mt-3">Daises</h5>
              <p class="text-sm">Customize your wedding stage with vibrant colors and intricate designs.</p>
            </div>
            <hr class="vertical warning">
          </div>
          <div class="col-md-4">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-warning"> <span id="state3" countTo="30">0</span>+</h1>
              <h5 class="mt-3">Bridal Attires</h5>
              <p class="text-sm">Explore our bridal collection: kebayas, dresses, songkets, and more.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end card section -->

<!-- promotions -->
<section id="promotion" class="my-5 py-5">
  <div class="row justify-content-center text-center my-sm-5">
    <div class="col-lg-6">
      <h2 class="text-warning mb-0">Promotions</h2>
      <p class="lead">Explore our promotions just for you</p>
    </div>
  </div>
  <div class="container">
    <div id="promotionsCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $promotions = getActivePromotion();
        if (mysqli_num_rows($promotions) > 0) : ?>
          <?php $index = 0; ?>
          <?php while ($promo = mysqli_fetch_assoc($promotions)) : ?>
            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
              <div class="row justify-content-center">
                <div class="col-lg-12">
                  <div class="card card-blog card-plain" data-promo-code="<?php echo $promo['promo_code']; ?>">
                    <div class="position-relative">
                      <div class="card shadow-lg min-height-250 max-height-300 text-white" style="background-image: url('admin/uploads/<?php echo $promo['poster']; ?>'); background-size: cover; background-position: center;" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to copy promotional code">
                        <div class="position-absolute top-0 end-0 m-2">
                          <span class="badge <?php echo ($promo['promo_status'] == 'Ongoing') ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo ucfirst($promo['promo_status']); ?>
                          </span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end">
                          <p class="card-title mt-3 text-dark bg-light opacity-8 text-center"><?= $promo['promo_code'] ?> : <?= $promo['promo_desc'] ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php $index++; ?>
          <?php endwhile; ?>
        <?php else : ?>
          <div class="carousel-item active">
            <div class="row">
              <div class="col text-center">
                <p>No promotions available</p>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <a class="carousel-control-prev" href="#promotionsCarousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#promotionsCarousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
  <!-- Toast Notification for Success Message -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container">
    <div id="promo-toast" class="toast " role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header bg-light">
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close bg-dark" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Promotion code copied to clipboard!
      </div>
    </div>
  </div>
</section>
<!-- promotions -->


<!-- latest package section -->
<section id="latestpkg" class="my-5 py-5 ">
  <div class="row">
    <div class="row justify-content-center text-center my-sm-5">
      <div class="col-lg-6">
        <h2 class="text-warning mb-0">Latest Packages</h2>
        <p class="lead">Explore our newest offerings tailored just for you</p>
      </div>
    </div>
  </div>
  <div class="container">
    <div id="latest-packages-carousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $package = getLatestPackage();
        $totalPackages = mysqli_num_rows($package);
        $active = 'active'; // To mark the first carousel item as active

        // Iterate through packages in groups of 3
        for ($i = 0; $i < $totalPackages; $i += 3) {
          // Open a carousel item
          echo '<div class="carousel-item ' . ($i === 0 ? $active : '') . '"><div class="row">';

          // Display up to 3 packages in this carousel item
          for ($j = $i; $j < $i + 3 && $j < $totalPackages; $j++) {
            mysqli_data_seek($package, $j); // Move pointer to correct position
            $item = mysqli_fetch_assoc($package);
        ?>
            <div class="col-md-4 mt-md-0">
              <a href="package_detail.php?pkg_id=<?= urlencode($item['pkg_id']) ?>">
                <div class="card shadow-lg move-on-hover min-height-250 max-height-250">
                  <img class="w-100 my-auto text-secondary" src="admin/uploads/<?= $item['pkg_img'] ?>" alt="image">
                </div>
                <div class="mt-2 ms-2">
                  <p class="text-gradient text-dark mb-2 text-sm"> • <?= $item['duration'] ?> Hours </p>
                  <h6 class="mb-0"><?= $item['pkg_name'] ?></h6>
                  <p class="text-secondary text-sm"><?= $item['pkg_desc'] ?></p>
                  <p class="text-secondary text-sm">RM<?= $item['pkg_price'] ?></p>
                </div>
              </a>
            </div>
        <?php
          }

          // Close the carousel item and row
          echo '</div></div>';
        }
        ?>
      </div>

      <!-- Carousel controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#latest-packages-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#latest-packages-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

</section>
<!-- end latest package section -->



<!-- start new arrival section -->
<section id="newarrival" class="my-5 py-5 ">
  <div class="row">
    <div class="row justify-content-center text-center my-sm-5">
      <div class="col-lg-6">
        <h2 class="text-warning mb-0">New Collections</h2>
        <p class="lead">Experience the freshest arrivals tailored to inspire your style</p>
      </div>
    </div>
  </div>
  <div class="container mt-sm-5 mt-3">
    <!-- bridal attires -->
    <div class="row">
      <div class="col-lg-3">
        <div class="position-sticky pb-lg-5 pb-3 mt-lg-0 mt-5 ps-2" style="top: 100px">
          <h3>Bridal Attires</h3>
          <a href="attire.php" class="btn btn-sm btn-outline-warning btn-round mb-0 me-1 mt-2 mt-md-0">View more</a>
        </div>
      </div>
      <div class="col-lg-9">
        <div class="row">
          <?php
          $attire = getLatest("bridal_attire");

          if (mysqli_num_rows($attire) > 0) {
            foreach ($attire as $item) {
          ?>
              <div class="col-md-4 mt-md-0">
                <a href="attire_detail.php?attire_id=<?= urlencode($item['attire_id']) ?>">
                  <div class="card shadow-lg move-on-hover  min-height-160 max-height-160">
                    <img class="w-100 my-auto text-secondary" src="admin/uploads/<?= $item['image'] ?>" alt="image">
                  </div>
                  <div class="mt-2 ms-2">
                    <h6 class="mb-0"><?= $item['name'] ?></h6>
                    <p class="text-secondary text-sm">RM <?= $item['normal_price'] ?></p>
                  </div>
                </a>
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
    <!-- end bridal attire  -->
    <!-- bridal dais -->
    <div class="row pt-lg-6">
      <div class="col-lg-3">
        <div class="position-sticky pb-lg-5 pb-3 mt-lg-0 mt-5 ps-2" style="top: 100px">
          <h3>Bridal Daises</h3>
          <a href="dais.php" class="btn btn-sm btn-outline-warning btn-round mb-0 me-1 mt-2 mt-md-0">View more</a>
        </div>
      </div>
      <div class="col-lg-9">
        <div class="row mt-3">
          <?php
          $dais = getLatest("bridal_dais");

          if (mysqli_num_rows($dais) > 0) {
            foreach ($dais as $item) {
          ?>
              <div class="col-md-4">
                <a href="dais_detail.php?dais_id=<?= urlencode($item['dais_id']) ?>">
                  <div class="card shadow-lg move-on-hover min-height-160 max-height-160">
                    <img class="w-100 my-auto text-secondary" src="admin/uploads/<?= $item['image'] ?>" alt="image">
                  </div>
                  <div class="mt-2 ms-2">
                    <h6 class="mb-0"><?= $item['name'] ?></h6>
                    <p class="text-secondary text-sm">RM <?= $item['normal_price'] ?></p>
                  </div>
                </a>
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
    <!-- end bridal dais -->
  </div>
</section>
<!-- end new arrival section -->
<!-- start gallery section -->
<section class="mb-5">
  <div class="container">
    <div class="row">
      <div class="row justify-content-center text-center my-sm-5">
        <div class="col-lg-6">
          <h2 class="text-warning mb-0">Event Gallery</h2>
          <p class="lead">Explore our events gallery, where memories shine</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="container mt-sm-2">
        <div class="row">
          <?php
          $gallery = getLatest("gallery");

          if (mysqli_num_rows($gallery) > 0) {
            foreach ($gallery as $item) {
          ?>
              <div class="col-md-3 mt-md-4">
                <div class="card shadow-lg move-on-hover  min-height-160 max-height-160">

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
  </div>
</section>
<!-- end gallery section -->
<!-- about us section -->
<!-- -------- START Features w/ icons and text on left & gradient title and text on right -------- -->
<section class="py-9 ">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h3 class="text-gradient text-warning mb-0 mt-2">Discover the Magic of Waniey Bridal</h3>
        <h5>Your premier wedding planning partner</h5><br>
        <p>
          We specialize in crafting unforgettable celebrations tailored to your unique style.
          With our dedicated team of professionals, we ensure a seamless and stress-free journey from concept to execution.
          Let us turn your wedding dreams into a reality, creating cherished memories that last a lifetime.
          Welcome to a world where every detail matters, and your special day is our top priority.
        </p>
        <a href="about.php" class="text-warning icon-move-right">More about us
          <i class="fas fa-arrow-right text-sm ms-1"></i>
        </a>
      </div>
      <div class="col-lg-6 mt-lg-0 mt-5 ps-lg-0 ps-0">
        <div class="p-3 info-horizontal">
          <div class="icon icon-shape rounded-circle bg-gradient-warning shadow text-center">
            <i class="fas fa-phone opacity-10"></i>
          </div>
          <div class="description ps-3">
            <p class="mb-0">+6017 340 5912</p>
          </div>
        </div>

        <div class="p-3 info-horizontal">
          <div class="icon icon-shape rounded-circle bg-gradient-warning shadow text-center">
            <i class="fas fa-envelope opacity-10"></i>
          </div>
          <div class="description ps-3">
            <p class="mb-0">wanieybridal@gmail.com</p>
          </div>
        </div>

        <div class="p-3 info-horizontal">
          <div class="icon icon-shape rounded-circle bg-gradient-warning shadow text-center">
            <i class="fas fa-map-pin opacity-10" style="color:white;"></i>
          </div>
          <div class="description ps-3">
            <p class="mb-0">G-11-G Jalan PP 25 Taman Pinggiran Putra,<br> Putra Walk, 43300 Seri Kembangan, Selangor</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- -------- END Features w/ icons and text on left & gradient title and text on right -------- -->
<!-- end about us section -->

<!-- feedback section -->
<section id="feedback" class="py-5">
  <div class="row">
    <div class="row justify-content-center text-center my-sm-5">
      <div class="col-lg-6">
        <h2 class="text-warning mb-0">Feedback</h2>
        <p class="lead">What our customers say about us</p>
      </div>
    </div>
  </div>
  <div class="container">
    <div id="feedback-carousel" class="carousel" data-bs-ride="carousel">
      <div class="row ">
        <?php
        $feedback = getFeedback();
        $totalFeedback = mysqli_num_rows($feedback);
        $active = 'active'; // To mark the first carousel item as active

        // Iterate through feedback in groups of 3
        for ($i = 0; $i < $totalFeedback; $i += 3) {
          // Open a carousel item
          echo '<div class="carousel-item ' . ($i === 0 ? $active : '') . '"><div class="row">';

          // Display up to 3 feedback in this carousel item
          for ($j = $i; $j < $i + 3 && $j < $totalFeedback; $j++) {
            mysqli_data_seek($feedback, $j); // Move pointer to correct position
            $item = mysqli_fetch_assoc($feedback);
        ?>
            <div class="col-lg-4 col-md-8">
              <div class="card card-plain border border-warning shadow-xl min-height-300 bg-gradient-white">
                <div class="card-body">
                  <div class="author">
                    <div class="name">
                      <h6 class="mb-0 font-weight-bolder"><?= $item['firstname'] ?> <?= $item['lastname'] ?></h6>
                      <div class="stats">
                        <i class="far fa-clock"></i> <?= date('F j, Y', strtotime($item['created_at'])) ?>
                      </div>
                    </div>
                  </div>

                  <p class="mt-4">"<?= $item['feedback'] ?>"</p>
                </div>
                <div class="card-footer">
                  <div class="rating mt-3">
                    <?php for ($k = 1; $k <= 5; $k++) { ?>
                      <?php if ($k <= $item['rating']) { ?>
                        <i class="fas fa-star text-warning"></i>
                      <?php } else { ?>
                        <i class="far fa-star text-secondary"></i>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }

          // Close the carousel item and row
          echo '</div></div>';
        }
        ?>
      </div>

      <!-- Carousel controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#feedback-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#feedback-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

</section>
<!-- end feedback section -->


<!-- -------   START PRE-FOOTER 2 - simple social line w/ title & 3 buttons    -------- -->
<div class="my-5 py-6">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 ms-auto">
        <h3 class="mb-1">Need Help?</h3>
        <p class="lead mb-0">Embark on Your Dream Celebration with Us – We're Ready to Make It Unforgettable!</p>
      </div>
      <div class="col-lg-5 me-lg-auto my-lg-auto text-lg-end mt-5">
        <a href="contact.php" class="btn btn-gradient btn-warning mb-0 me-2" target="_blank">
          Contact Us
        </a>
      </div>
    </div>
  </div>
</div>
<!-- -------   END PRE-FOOTER 2 - simple social line w/ title & 3 buttons    ---------->
<?php include('includes/footer.php') ?>





<script>
  document.addEventListener('DOMContentLoaded', (event) => {
    const promoCards = document.querySelectorAll('.card-blog');

    promoCards.forEach(card => {
      card.addEventListener('click', () => {
        const promoCode = card.getAttribute('data-promo-code');
        navigator.clipboard.writeText(promoCode).then(() => {
          // Show toast notification
          const toastContainer = document.getElementById('toast-container');
          const promoToast = new bootstrap.Toast(document.getElementById('promo-toast'));
          promoToast.show();
        }).catch(err => {
          console.error('Failed to copy promo code: ', err);
        });
      });
    });
  });
</script>