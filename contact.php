<?php
session_start();
include('dbcon.php');
include('functions.php');
$page_title = "Contact Us";
include('includes/header.php');



// button requestquot is clicked
if (isset($_POST['requestquot'])) {
  $fullname = $_POST['fullname'];
  $phoneNo = $_POST['phoneNo'];
  $email = $_POST['email'];
  $total_pax = $_POST['total_pax'];
  $event_date = $_POST['event_date'];
  $event_type = $_POST['event_type'];
  $event_location = $_POST['event_location'];
  $question = $_POST['message'];

  $query = "INSERT INTO quotation_request (fullname, phoneNo, email, total_pax, event_date, event_type, event_location, question) 
                  VALUES ('$fullname','$phoneNo','$email','$total_pax','$event_date','$event_type','$event_location','$question')";

  $query_run = mysqli_query($con, $query);

  // Check if the query was successful
  if ($query_run) {
    echo "<script>
          alert ('Thank you for contacting us. We will be in touch with you soon. Have a good day.');
          document.location = 'contact.php';
          </script>";
  } else {
    echo "<script>
              alert('Add Data Unsuccessful.');
              document.location = 'contact.php';
              </script>";
  }
}

?>

<section class="py-lg-7">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="card overflow-hidden mb-5">
          <div class="row">
            <div class="col-lg-7">
              <form class="p-3" id="contact-form" action="contact.php" method="post">
                <div class="card-header px-4 py-sm-5 py-3">
                  <h2>Say Hi!</h2>
                  <p class="lead"> "Need a hand with your plans? Let's chat!"</p>
                </div>
                <div class="card-body pt-1">
                  <div class="row">
                    <div class="col-md-12 pe-2 mb-3">
                      <label>My name is</label>
                      <input class="form-control" placeholder="Full Name" type="text" name="fullname" required>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-">
                        <label>Phone Number</label>
                        <input class="form-control" placeholder="eg: 0123456789" type="text" name="phoneNo" required>
                      </div>
                      <div class="col-md-6 pe-2 mb-3 ">
                        <label>Email</label>
                        <input class="form-control" placeholder="eg: abc@gmail.com" type="text" name="email" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 pe-2 mb-3">
                        <label>Event Type</label>
                        <input class="form-control" placeholder="eg: nikah" type="text" name="event_type" required>
                      </div>
                      <div class="col-md-6 pe-2 mb-3">
                        <label>Event Location</label>
                        <input class="form-control" placeholder="eg: Kajang, Selangor" type="text" name="event_location" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 pe-2 mb-3">
                        <label>Event Date (Tarikh Majlis)</label>
                        <input class="form-control" placeholder="dd/mm/yyyy" type="date" name="event_date" required>
                      </div>
                      <div class="col-md-6 pe-2 mb-3">
                        <label>Total Pax (Estimated)</label>
                        <input class="form-control" placeholder="eg: 200" type="text" name="total_pax" required>
                      </div>
                    </div>
                    <div class="col-md-12 pe-2 mb-3">
                      <div class="form-group mb-0">
                        <label>Your message</label>
                        <textarea name="message" class="form-control" id="message" rows="6" placeholder="I want to say that..." ></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 text-end ms-auto">
                      <button type="submit" class="btn btn-round bg-gradient-warning mb-0" name="requestquot">Send Message</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="col-lg-5 position-relative bg-cover px-0" style="background-image: url('assets/img/curved-images/curved5.jpg')">
              <div class="position-absolute z-index-2 w-100 h-100 top-0 start-0 d-lg-block d-none">
                <img src="assets/img/wave-1.svg" class="h-100 ms-n2" alt="vertical-wave">
              </div>
              <div class="z-index-2 text-center d-flex h-100 w-100 d-flex m-auto justify-content-center">
                <div class="mask bg-gradient-warning opacity-5"></div>
                <div class="p-5 ps-sm-8 position-relative text-start my-auto z-index-2">
                  <h3 class="text-white">Contact Us</h3>
                  <p class="text-white opacity-8 mb-4">"Discover enchanting locations and stay connected. Your next unforgettable experience is just a visit away!"</p>
                  <div class="d-flex p-2 text-white">
                    <div>
                      <i class="fas fa-phone text-sm"></i>
                    </div>
                    <div class="ps-3">
                      <span class="text-sm opacity-8">(+60)17 025 2345</span>
                    </div>
                  </div>
                  <div class="d-flex p-2 text-white">
                    <div>
                      <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <div class="ps-3">
                      <span class="text-sm opacity-8">wanieybridal@gmail.com</span>
                    </div>
                  </div>
                  <div class="d-flex p-2 text-white">
                    <div>
                      <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <div class="ps-3">
                      <span class="text-sm opacity-8">G-11-G Jalan pp 25 taman pinggiran putra, Putra Walk, 43300 Seri Kembangan, Selangor</span>
                    </div>
                  </div>
                  <div class="mt-4">
                    <button type="button" class="btn btn-icon-only btn-link text-white btn-lg mb-0" data-toggle="tooltip" data-placement="bottom" data-original-title="Log in with Facebook">
                      <i class="fab fa-facebook"></i>
                    </button>
                    <button type="button" class="btn btn-icon-only btn-link text-white btn-lg mb-0" data-toggle="tooltip" data-placement="bottom" data-original-title="Log in with Twitter">
                      <i class="fab fa-whatsapp"></i>
                    </button>
                    <button type="button" class="btn btn-icon-only btn-link text-white btn-lg mb-0" data-toggle="tooltip" data-placement="bottom" data-original-title="Log in with Dribbble">
                      <i class="fab fa-instagram"></i>
                    </button>
                    <button type="button" class="btn btn-icon-only btn-link text-white btn-lg mb-0" data-toggle="tooltip" data-placement="bottom" data-original-title="Log in with Instagram">
                      <i class="fab fa-tiktok"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php') ?>


