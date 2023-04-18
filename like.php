<?php
include_once('connection.php');
session_start();

$userID = $_SESSION['us_id'];
$ideaID = $_POST["idea_id"];
$vote = $_POST['vote'];


$sql = "SELECT * FROM thumbs WHERE UserID = $userID AND IdeaID = $ideaID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {

  $sql = "INSERT INTO Thumbs (UserID, IdeaID, Vote) VALUES ($userID, $ideaID, $vote)";
  mysqli_query($conn, $sql);
} else {

  $sql = "UPDATE Thumbs SET Vote = $vote WHERE UserID = $userID AND IdeaID = $ideaID";
  mysqli_query($conn, $sql);
}


$sql = "SELECT * FROM thumbs WHERE UserID = $userID AND IdeaID = $ideaID";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo "Vote: " . $row['Vote'];


header("Location: index.php");
?>