<?php

session_start();
include_once("connection.php");


$id = $_GET['id'];


$sql = "DELETE FROM users WHERE UserID = $id";
if (mysqli_query($conn, $sql)) {
    echo "Deleted!";
    header('Location: admin page.php');
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}


mysqli_close($conn);
?>