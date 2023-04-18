<!DOCTYPE html>
<html>
<head>
  <title>Edit Idea</title>
  <link rel="stylesheet" href="style_add_idea.css">
</head>
<body>
  <?php
  // Connect to the database
  session_start();
  include_once("connection.php");

  // Retrieve the idea ID from the URL
  $id = $_GET['id'];

  // Retrieve the idea data from the database
  $query = "SELECT * FROM ideas WHERE IdeaID = '$id'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  // Check if the form has been submitted
  // Check if the form has been submitted
if (isset($_POST['submit'])) {
  // Retrieve the form data
  $title = $_POST['title'];
  $description = $_POST['description'];
  $submissionDate = $_POST['submissionDate'];
  $closureDate = $_POST['closureDate'];
  $finalClosureDate = $_POST['finalClosureDate'];
  $status = isset($_POST['status']) ? 1 : 0;

  // Update the idea data in the database
  $query = "UPDATE ideas
            SET Title = '$title',
                Description = '$description',
                SubmissionDate = '$submissionDate',
                ClosureDate = '$closureDate',
                FinalClosureDate = '$finalClosureDate',
                status = '$status'
            WHERE IdeaID = '$id'";
  if (mysqli_query($conn, $query)) {
    echo "Idea updated successfully.";
  } else {
    echo "Error updating idea: " . mysqli_error($conn);
  }
}

  ?>
  <h1>Edit Idea</h1>
  <form method="post">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?php echo $row['Title']; ?>"><br>

    <label>Description:</label><br>
    <textarea name="description"><?php echo $row['Description']; ?></textarea><br>

    <label>Submission Date:</label><br>
    <input type="date" name="submissionDate"
    value="<?php echo date('Y-m-d', strtotime($row['SubmissionDate'])); ?>"><br>

    <label>Closure Date:</label><br>
    <input type="date" name="closureDate" value="<?php echo date('Y-m-d', strtotime($row['ClosureDate'])); ?>"><br>

    <label>Final Closure Date:</label><br>
    <input type="date" name="finalClosureDate" value="<?php echo date('Y-m-d', strtotime($row['FinalClosureDate'])); ?>"><br>

    <label>Status:</label><br>
    <input type="checkbox" name="status" value="1" <?php if ($row['status'] == 1) { echo "checked"; } ?>>Accepted<br>

    <input type="submit" name="submit" value="Save Changes">
    <?php if(isset($_SESSION['depart'])&&$_SESSION['depart']==9){?>
  <a href="admin page.php" style="all:unset;">Back</a>
  <?php }else{
    ?>
    <a href="coordinator_page.php" style="all:unset;">Back</a>
    <?php
  }?>
  </form>
  <?php
  // Close the connection
  mysqli_close($conn);
  ?>
</body>
</html>
