<?php
session_start();
if (!isset($_SESSION['us_id'])) {

  exit('Error: User ID not defined');
}
?>

<!DOCTYPE html>
<html>
<title>Add New Idea</title>
<link rel="stylesheet" href="style_add_idea.css">

</html>

<body>
  <h1>Add New Idea</h1>
  <form method="post" action="" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>
    <br>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>
    <br>
    <label for="document">Document:</label>
    <input type="file" name="document[]" id="document" multiple required>
    <br>
    <label for="submission_date">Submission date:</label>
    <input type="datetime-local" name="submission_date" id="submission_date" required>
    <br>

    <label for="closure_date">Closure date:</label>
    <input type="datetime-local" name="closure_date" id="closure_date" required>
    <br>

    <label for="final_closure_date">Final closure date:</label>
    <input type="datetime-local" name="final_closure_date" id="final_closure_date" required>
    <br>

    <label for="anonymous">Anonymous:</label>
    <input type="checkbox" name="anonymous" id="anonymous">
    <br>

    <label for="category">Category:</label>
    <select name="category" id="category" required>
      <option value="">Select a category</option>
      <?php

      include_once('connection.php');
      $sql = "SELECT IdeaCategoryID, CategoryName FROM IdeaCategories";
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['IdeaCategoryID'] . '">' . $row['CategoryName'] . '</option>';
      }
      ?>
    </select>
    <br>

    <input type="submit" name="add-idea" value="Add Idea">
    <?php if (isset($_SESSION['depart']) && $_SESSION['depart'] == 9) { ?>
      <a href="admin page.php" style="all:unset;">Back</a>
    <?php }
    if (isset($_SESSION['depart']) && $_SESSION['depart'] == 6) {
      ?>
      <a href="coordinator_page.php" style="all:unset;">Back</a>
      <?php
    } else { ?>
      <a href="student_page.php" style="all:unset;">Back</a>
    <?php } ?>
  </form>
</body>

<?php
if (isset($_POST['add-idea'])) {

  $user_id = $_SESSION['us_id'];

  $title = $_POST['title'];
  $description = $_POST['description'];
  $submission_date = $_POST['submission_date'];
  $closure_date = $_POST['closure_date'];
  $final_closure_date = $_POST['final_closure_date'];
  $anonymous = isset($_POST['anonymous']) ? true : false;
  $category_id = $_POST['category'];
  $status = false;


  include_once('connection.php');


  $sql = "SELECT Email FROM Users WHERE UserID = ? AND departmentID = 9";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $email = $row['Email'];


  $sql = "INSERT INTO Ideas (Title, Description, SubmissionDate, ClosureDate, FinalClosureDate, Anonymous, status, UserID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssiii", $title, $description, $submission_date, $closure_date, $final_closure_date, $anonymous, $status, $user_id);
  $stmt->execute();
  $idea_id = $stmt->insert_id;



  foreach ($_FILES['document']['tmp_name'] as $key => $tmp_name) {
    $fileType = $_FILES["document"]["type"][$key];
    $fileContent = addslashes(file_get_contents($tmp_name));
    $documentName = $_FILES["document"]["name"][$key];

    move_uploaded_file($tmp_name, "images/" . $documentName);

    $sql = "INSERT INTO `documents` (`FileType`, `FileContent`, `IdeaID`, `DocumentName`) 
            VALUES ('$fileType', '$fileContent', '$idea_id', '$documentName')";
    if ($conn->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }



  $sql = "INSERT INTO IdeaCategoryMapping (IdeaID, IdeaCategoryID) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $idea_id, $category_id);
  $stmt->execute();


  if (isset($_SESSION['depart']) && $_SESSION['depart'] == 9) {
    header("Location:admin page.php?userid=$user_id");
    exit();
  } else {
    header("Location:student_page.php?userid=$user_id");
  }

}
?>