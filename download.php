<?php

include_once('connection.php');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "SELECT * FROM `documents` WHERE `DocumentID` = '$id'";
  $result = $conn->query($sql);
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    header('Content-Type: ' . $row['FileType']);
    header('Content-Disposition: attachment; filename="' . $row['DocumentName'] . '"');

    echo $row['FileContent'];
    exit;
  }
}

header('Location: index.php');
?>