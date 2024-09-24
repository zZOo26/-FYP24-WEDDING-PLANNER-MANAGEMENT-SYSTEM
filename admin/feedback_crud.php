<?php
include('../dbcon.php');

// Button delete category is clicked
if (isset($_POST['deletefb'])) {
    $fb_id = mysqli_real_escape_string($con, $_POST['fb_id']);

    $query = mysqli_query($con, "DELETE FROM feedback WHERE fb_id = '$fb_id'");

    // If delete feedback is successful
    if ($query) {
        echo "<script>
            alert('Feedback Deleted Successfully.');
            document.location = 'feedback.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'feedback.php';
        </script>";
    }
}

// Update view status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_update'])) {
    $view_update = $_POST['view_update'];

    // First, set all feedback views to 0
    $query_reset = "UPDATE feedback SET view = 0";
    mysqli_query($con, $query_reset);

    // Then, update checked feedbacks to 1
    foreach ($view_update as $feedback_id) {
        $query = "UPDATE feedback SET view = 1 WHERE fb_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $feedback_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Redirect to the same page or another page after update
    header("Location: feedback.php");
    exit;
}
?>
