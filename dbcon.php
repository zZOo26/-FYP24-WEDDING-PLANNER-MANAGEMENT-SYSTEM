<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "wanieybridaldb";

//create connection
$con = mysqli_connect($host, $username, $password, $dbname);

//check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}else{
    //echo "Connected successfully";
}

?>