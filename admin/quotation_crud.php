<?php
include('../dbcon.php');

// button delete is clicked
if (isset($_POST['deletequot'])) {
    $request_id = mysqli_real_escape_string($con, $_POST['request_id']);

    $query = mysqli_query($con, "DELETE FROM quotation_request WHERE request_id = '$request_id'");

    // if update expenses is successful
    if ($query) {
        echo "<script>
            alert('Quotation Deleted Successfully.');
            document.location = 'quotation.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'quotation.php';
        </script>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_update'])) {
    $status_updates = $_POST['status_update'];

    foreach ($status_updates as $request_id) {
        // Assuming you have a column named 'status' in your 'quotation_request' table
        // Perform validation and update in your database
        // Example query:
        $query = "UPDATE quotation_request SET status = 1 WHERE request_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $request_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Redirect to the same page or another page after update
    header("Location: quotation.php");
    exit;
}

?>