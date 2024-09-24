<?php
session_start();
include('../dbcon.php');
include('functions.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if fields are not empty
    if (!empty($email) && !empty($password)) {
        // Use prepared statement to retrieve user data
        $query = "SELECT * FROM admins WHERE email = ? LIMIT 1";

        $stmt = mysqli_prepare($con, $query);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if (password_verify($password, $user_data['password'])) {
                $_SESSION['admin_id'] = $user_data['admin_id'];
                header("Location: index.php");
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
}else{
    //$message = "Login Unsuccessful";
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>
        Admin Login
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="./assets/css/corporate-ui-dashboard.css?v=1.0.0" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content  mt-0">
        <section>

            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-black text-dark display-6">Welcome back</h3>
                                <p class="mb-0">Welcome back! Please enter your details.</p>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($message)) : ?>
                                    <div class="alert text-danger mb-3 pb-2 text-center" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                <form role="form" action="admin_login.php" method="post">
                                    <label>Email Address</label>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Enter your email address" aria-label="Email" aria-describedby="email-addon">
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                    </div>

                                    <div class="text-center">
                                        <div class="form-btn">
                                            <input type="submit" class="btn btn-dark w-100 mt-4 mb-3" name="submit" value="Login">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-xs mx-auto">
                                    Back to
                                    <a href="../index.php" class="text-dark font-weight-bold">Home</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                            <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8" style="background-image:url('./assets/img/image-sign-in.jpg')">
                                <div class="blur mt-12 p-4 text-center border border-white border-radius-md position-absolute fixed-bottom m-4">
                                    <h2 class="mt-3 text-dark font-weight-bold">Get started with managing tasks efficiently.</h2>
                                    <h6 class="text-dark text-sm mt-5">Copyright © <script>
                                            document.write(new Date().getFullYear())
                                        </script> Waniey Bridal Wedding Planner Ent. </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
    <!--   Core JS Files   -->
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="./assets/js/corporate-ui-dashboard.min.js?v=1.0.0"></script>
</body>

</html>