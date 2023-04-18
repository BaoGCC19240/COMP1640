<?php
session_start();
include_once("connection.php");
if (isset($_POST["idea_id"]) && isset($_POST["comment_text"])) {
  $idea_id = $_POST["idea_id"];
  $user_id = $_SESSION["us_id"];
  $comment_text = $_POST["comment_text"];
  $anonymous = isset($_POST["anonymous"]) ? $_POST["anonymous"] : 0;


  $sql = "INSERT INTO comments (IdeaID, UserID, CommentDate, CommentText, Anonymous)
          VALUES ('$idea_id', '$user_id', NOW(), '$comment_text', '$anonymous')";
  mysqli_query($conn, $sql);


  $comment_id = mysqli_insert_id($conn);
  $sql = "SELECT * FROM comments WHERE CommentID = '$comment_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  $username = 'Anonymous';
  if ($row['Anonymous'] == 0) {
    $user_id = $row['UserID'];
    $sq_us_cmt = "Select Email from users where UserID =$user_id";
    $res = mysqli_query($conn, $sq_us_cmt);
    $us_row = $res->fetch_assoc();
    $username = $us_row['Email'];
  }


  echo '<div class="comment">
          <div class="comment-header">
            <h4 class="username">
              ' . $username . '
            </h4>
            <p class="timestamp">
              ' . $row['CommentDate'] . '
            </p>
          </div>
          <div class="comment-body">
          ' . $row['CommentText'] . '
</div>
</div>';
} else {
  echo 'Error: Missing parameters.';
}
?>