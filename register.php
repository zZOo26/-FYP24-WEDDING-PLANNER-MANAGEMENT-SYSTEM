<?php
session_start();
include('dbcon.php');
include('functions.php');


$message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something is posted
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phoneNo = $_POST['phoneNo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpwd = $_POST['confirmpwd'];

    // Check if fields are not empty
    if (!empty($firstname) && !empty($lastname) && !empty($phoneNo) && !empty($email) && !empty($password) && !empty($confirmpwd)) {

        // Check if passwords match
        if ($password == $confirmpwd) {

            // Check if email already exists
            $query_check_email = "SELECT * FROM customers WHERE email = ?";
            $stmt_check_email = mysqli_prepare($con, $query_check_email);
            mysqli_stmt_bind_param($stmt_check_email, "s", $email);
            mysqli_stmt_execute($stmt_check_email);
            mysqli_stmt_store_result($stmt_check_email);
            $num_rows = mysqli_stmt_num_rows($stmt_check_email);

            if ($num_rows == 0) {
                // Email doesn't exist, proceed with insertion
                $query_insert_customer = "INSERT INTO customers (firstname, lastname, phoneNo, email, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt_insert_customer = mysqli_prepare($con, $query_insert_customer);
                mysqli_stmt_bind_param($stmt_insert_customer, "sssss", $firstname, $lastname, $phoneNo, $email, password_hash($password, PASSWORD_DEFAULT));
                mysqli_stmt_execute($stmt_insert_customer);
                mysqli_stmt_close($stmt_insert_customer);

                header("Location: login.php");
                die;
            } else {
                // Email already exists, show error message
                $message = "Email already exists!";
            }

            mysqli_stmt_close($stmt_check_email);
        } else {
            $message = "Passwords do not match!";
        }
    } else {
        $message = "All Fields Are Required!";
    }
}


$page_title = "Create new Account";
include('includes/header.php');

?>



<section >
    <div class="page-header min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-left ">
                            <h4 class="font-weight-bolder" style="margin-top: 50px ">Create Account</h4>
                            <p class="mb-0">"Sign up now for exclusive perks!"</p>
                        </div>


                        <div class="card-body">
                            <?php if (!empty($message)) : ?>
                                <div class="alert alert-danger mb-3 pb-2 text-center" role="alert" style="font-size: 14px; color: white;">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            <form role="form" action="register.php" method="post">
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg" name="firstname" placeholder="First Name">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg" name="lastname" placeholder="Last Name">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg" name="phoneNo" placeholder="Phone Number">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="password" class="form-control form-control-lg" name="confirmpwd" placeholder="Confirm Password" aria-label="Password" aria-describedby="password-addon">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="form-btn">
                                        <input type="submit" class="btn btn-lg bg-gradient-warning btn-lg w-100 mt-4 mb-0" name="submit" value="Create account">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-4 text-sm mx-auto">
                                Already have an account?
                                <a href="login.php" class="text-warning text-gradient font-weight-bold">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                    <div class="position-relative bg-gradient-warning h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
                        <img src="assets/img/shapes/pattern-lines.svg" alt="pattern-lines" class="position-absolute opacity-4 start-0">
                        <div class="position-relative">
                            <img class="max-width-500 w-100 position-relative z-index-2" src="assets/img/illustrations/chat.png">
                        </div>
                        <h4 class="mt-5 text-white font-weight-bolder">"Get ready to unlock the magic of wedding planning with WANIEY BRIDAL"</h4>
                        <p class="text-white">Register new account to get started.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include('includes/footer.php'); ?>