<?php

session_start();
include_once("connection.php");


$id = $_GET['id'];


$sql = "DELETE FROM documents WHERE IdeaID = $id";
if (mysqli_query($conn, $sql)) {
    $sql = "DELETE FROM comments WHERE IdeaID = $id";
    if (mysqli_query($conn, $sql)) {
        $sql = "DELETE FROM thumbs WHERE IdeaID = $id";
        if (mysqli_query($conn, $sql)) {
            $sql = "DELETE FROM ideacategorymapping WHERE IdeaID= $id";
            if (mysqli_query($conn, $sql)) {
                $sql = "DELETE FROM ideas WHERE IdeaID = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "Deleted!";
                    if (isset($_SESSION['depart']) && $_SESSION['depart'] == 9) {
                        header('Location: admin page.php');
                    }
                    if (isset($_SESSION['depart']) && $_SESSION['depart'] == 6) {
                        header('Location: coordinator_page.php');
                    }

                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {

            echo "Error: " . mysqli_error($conn);
        }
    } else {

        echo "Error: " . mysqli_error($conn);
    }
} else {

    echo "Error: " . mysqli_error($conn);
}


mysqli_close($conn);

?>