<?php
include('../dbcon.php');
//button add expenses is clicked
if (isset($_POST['addexpenses'])) {
    $expense_name = $_POST['expense_name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    $query = mysqli_query($con, "INSERT INTO expenses (expense_name,amount,description, date_created) VALUES ('$expense_name','$amount','$description',NOW())");

    //if add expenses success
    if ($query) {
        echo "<script>
        alert ('Expenses Added Successfully.');
        document.location = 'expenses.php';
        </script>";
    } else {
        echo "<script>
            alert('Add Expenses Unsuccessful.');
            document.location = 'expenses.php';
            </script>";
    }
}

// button edit expenses is clicked
if (isset($_POST['editexpenses'])) {
    $expense_name = mysqli_real_escape_string($con, $_POST['expense_name']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $expense_id = mysqli_real_escape_string($con, $_POST['expense_id']);

    $query = mysqli_query($con, "UPDATE expenses SET expense_name = '$expense_name', amount = '$amount', description = '$description' WHERE expense_id = '$expense_id'");

    // if update expenses is successful
    if ($query) {
        echo "<script>
            alert('Expenses Updated Successfully.');
            document.location = 'expenses.php';
        </script>";
    } else {
        echo "<script>
            alert('Update Expenses Unsuccessful.');
            document.location = 'expenses.php';
        </script>";
    }
}


//button delete expenses is clicked
if (isset($_POST['deleteexpenses'])) {
    $expense_id = mysqli_real_escape_string($con, $_POST['expense_id']);

    $query = mysqli_query($con, "DELETE FROM expenses WHERE expense_id = '$expense_id'");

    // if update expenses is successful
    if ($query) {
        echo "<script>
            alert('Expenses Deleted Successfully.');
            document.location = 'expenses.php';
        </script>";
    } else {
        echo "<script>
            alert('Delete Unsuccessful.');
            document.location = 'expenses.php';
        </script>";
    }
}

?>
