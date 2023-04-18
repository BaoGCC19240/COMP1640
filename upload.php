<!DOCTYPE html>
<html>

<head>
  <title>File Upload Example</title>
</head>

<body>
  <h1>File Upload Example</h1>
  <form action="" method="post" enctype="multipart/form-data">
    <label for="file">Select file to upload:</label>
    <input type="file" name="file" id="file">
    <br><br>
    <input type="submit" name="submit" value="Upload">
  </form>
</body>

</html>

<?php

include_once('connection.php');


if (isset($_POST["submit"])) {

  if ($_FILES["file"]["error"] > 0) {
    echo "Error: " . $_FILES["file"]["error"] . "<br>";
  } else {

    $fileType = $_FILES["file"]["type"];
    $fileContent = addslashes(file_get_contents($_FILES["file"]["tmp_name"]));
    $ideaID = 1;
    $documentName = $_FILES["file"]["name"];

    $sql = "INSERT INTO `documents` (`FileType`, `FileContent`, `IdeaID`, `DocumentName`) 
            VALUES ('$fileType', '$fileContent', '$ideaID', '$documentName')";

    if ($conn->query($sql) === TRUE) {
      echo "File uploaded successfully.";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}
?>