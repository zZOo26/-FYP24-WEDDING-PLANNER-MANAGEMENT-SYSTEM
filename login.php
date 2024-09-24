<?php
session_start();
include('./dbcon.php');
include('functions.php');

$message = "";

if (isset($_GET['redirect'])) {
    $_SESSION['redirect_url'] = $_GET['redirect'];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if fields are not empty
    if (!empty($email) && !empty($password)) {
        // Use prepared statement to retrieve user data
        $query = "SELECT * FROM customers WHERE email = ? LIMIT 1";

        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if (password_verify($password, $user_data['password'])) {
                $_SESSION['cust_id'] = $user_data['cust_id'];
                $_SESSION['firstname'] = $user_data['firstname'];
                $_SESSION['lastname'] = $user_data['lastname'];
                $_SESSION['email'] = $user_data['email'];

                // Check for stored redirect URL
                $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'myprofile.php';
                unset($_SESSION['redirect_url']);

                header("Location: " . $redirect_url);
                die;
            } else {
                $message = "Invalid email or password!";
            }
        } else {
            $message = "Invalid email or password!";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "All fields are required!";
    }
}


include('includes\header.php');
?>


<section>
    <div class="page-header min-vh-75">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                    <div class="card card-plain mt-8">
                        <div class="card-header pb-0 text-left bg-transparent">
                            <h3 class="font-weight-bolder text-warning text-gradient">Welcome back</h3>
                            <p class="mb-0">Enter your email and password to sign in</p>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($message)) : ?>
                                <div class="alert alert-danger mb-3 pb-2 text-center" role="alert" style="font-size: 13px; color: white;">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            <form role="form" action="login.php" method="post">
                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                </div>
                                <div class="text-center">
                                    <div class="form-btn">
                                        <input type="submit" class="btn btn-lg bg-gradient-warning btn-lg w-100 mt-4 mb-0" name="submit" value="Login">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-4 text-sm mx-auto">
                                Don't have an account?
                                <a href="register.php" class="text-warning text-gradient font-weight-bold">Create account</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                        <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('assets/img/IMG_2073.jpg')"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include('includes/footer.php'); ?>