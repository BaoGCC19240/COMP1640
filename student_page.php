<?php
session_start();
include_once("connection.php");
?>
<!DOCTYPE html>
<html>

<head>
  <title>Staff Page</title>
  <!-- <link rel="stylesheet" href="style_student_page.css"> -->
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="style_student_page.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<body>
  <?php
  include_once('connection.php');

  // Assuming the student ID is passed via $_SESSION
  $id = $_SESSION['us_id'];

  // Retrieve student information from the database
  $sql = "SELECT LastName, UserID FROM users WHERE UserID = '$id'"; // assuming username is used to identify the student
  $result = $conn->query($sql);

  // Check if there is exactly one result
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $student_name = $row["LastName"];
    $student_id = $row["UserID"];
  } else {
    // Handle error: either no results or multiple results
    $student_name = "Unknown";
    $student_id = "Unknown";
  }

  // Close the database connection
  $avatar_url = "https://tse1.mm.bing.net/th?id=OIP.STdrVT87X1tnQJWSdE5VeQHaHa&pid=Api&P=0";
  ?>

  <h1>Staff Information</h1>
  <img src="<?php echo $avatar_url ?>" alt="Avatar" class="avt">
  <h2>
    <?php echo $student_name ?>
  </h2>
  <h2>Student ID:
    <?php echo $student_id ?>
  </h2>
  <button onclick="location.href='add_idea.php?user_id=<?php echo $_SESSION['us_id']; ?>'" class="add">Add New
    Post</button>
  <button onclick="location.href='index.php?user_id=<?php echo $_SESSION['us_id']; ?>'" class="add">Home</button>

  <h1>Article Available</h1>

  <div class="idea">
    <?php
    include_once('connection.php');
    $idea_sql = "SELECT users.Email,ideas.IdeaID, ideas.SubmissionDate, ideas.Description, SUM(CASE WHEN Thumbs.Vote = 1 THEN 1 ELSE 0 END) AS Likes, SUM(CASE WHEN Thumbs.Vote = 0 THEN 1 ELSE 0 END) AS Dislikes
  	FROM ideas
  	INNER JOIN users ON ideas.UserID = users.UserID
  	LEFT JOIN Thumbs ON ideas.IdeaID = thumbs.IdeaID WHERE ideas.UserID = $id
  	GROUP BY ideas.IdeaID, users.Email, ideas.SubmissionDate, ideas.Description
  	ORDER BY ideas.SubmissionDate DESC";
    $idea_result = mysqli_query($conn, $idea_sql);
    if (mysqli_num_rows($idea_result) > 0) {
      while ($idea_row = mysqli_fetch_assoc($idea_result)) {
        ?>

        <div class="post">
          <div class="post-header">
            <div class="post-meta">
              <h4 class="username">
                <?php echo $idea_row['Email']; ?>
              </h4>
              <p class="timestamp">
                <?php echo $idea_row['SubmissionDate']; ?>
              </p>
            </div>
          </div>
          <div class="post-body">
            <p>
              <?php echo $idea_row['Description']; ?>
            </p>
            <?php

            include_once('connection.php');

            $IdeaID = $idea_row['IdeaID'];
            $sql = "SELECT * FROM documents where IdeaID = $IdeaID";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $filename = $row['DocumentName'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($filetype), array('jpg', 'jpeg', 'png', 'gif'))) {

                  ?>
                  <img class="post-image" src="images/<?php echo $filename; ?>" alt="<?php echo $filename; ?>">
                  <?php
                } else {

                  ?>
                  <div class="post-document">
                    <a href="download.php?id=<?php echo $row['DocumentID']; ?>"><?php echo $filename; ?></a>
                  </div>
                  <?php
                }
              }
            }
            ?>
          </div>
          <div class="post-footer">
            <button class="like-button" data-ideaid="<?php echo $idea_row['IdeaID']; ?>">
              <i class="fa fa-thumbs-o-up fa-lg" aria-hidden="true">
                <?php echo $idea_row['Likes']; ?>
              </i>
            </button>
            <button class="dislike-button" data-ideaid="<?php echo $idea_row['IdeaID']; ?>">
              <i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true">
                <?php echo $idea_row['Dislikes']; ?>
              </i>
            </button>
            <button class="comment-button"><i class="fa fa-comment-o fa-lg" aria-hidden="true"></i></button>
          </div>
          <hr style="margin: 5px;">
          <div class="post-comments">

          </div>
          <?php

          include_once('connection.php');

          $sql = "SELECT * FROM comments where IdeaID = $IdeaID";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $username = 'Anonymous';
              if ($row['Anonymous'] == 0) {
                $user_id = $row['UserID'];
                $sq_us_cmt = "Select Email from users where UserID =$user_id";
                $res = mysqli_query($conn, $sq_us_cmt);
                $us_row = $res->fetch_assoc();
                $username = $us_row['Email'];
              }



              ?>
              <div class="comment">
                <div class="comment-header">
                  <h4 class="username">
                    <?php echo $username; ?>
                  </h4>
                  <p class="timestamp">
                    <?php echo $row['CommentDate']; ?>
                  </p>
                </div>
                <div class="comment-body">
                  <p>
                    <?php echo $row['CommentText']; ?>
                  </p>
                </div>
                <div class="comment-footer">
                  <button class="like-button-cmt"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="true"></i></button>
                  <button class="dislike-button-cmt"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></button>
                </div>
              </div>
              <?php
            }
          }
          ?>
          <form class="comment-form" method="POST">
            <textarea class="comment-input" placeholder="Write a comment..." name="comment_text"></textarea>
            <label>
              <input type="checkbox" name="anonymous" value="1"> Anonymous
            </label>
            <input type="hidden" name="idea_id" value="<?php echo $IdeaID; ?>">
            <button type="submit" class="comment-submit" name='btn-cmt'>Comment</button>
          </form>
        </div>


      <?php
      }
    }

    ?>
  </div>
  <?php
  if (isset($_POST['btn-cmt'])) {

    if (!isset($_SESSION['us_id'])) {

      header('Location: login page.php');
      exit;
    }


    $comment_text = $_POST['comment_text'];
    $idea_id = $_POST['idea_id'];
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;
    $user_id = $_SESSION['us_id'];


    include_once('connection.php');


    $sql = "INSERT INTO comments (CommentText, CommentDate, Anonymous, UserID, IdeaID) VALUES (?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siii', $comment_text, $anonymous, $user_id, $idea_id);
    $stmt->execute();
  }
  ?>

  </div>
  </div>
  <script>
    var btnBar = document.getElementById("btn-bar");
    var topic = document.querySelector(".topic");
    var idea = document.querySelector(".idea");
    var topicContents = document.querySelector(".topic-contents");

    btnBar.addEventListener("click", function () {
      if (window.innerWidth <= 600) {
        if (topic.style.height == "40%") {
          topic.style.height = "6%";
          topic.style.width = "100%";
          idea.style.height = "94%";
          idea.style.width = "100%";
          topicContents.style.display = "none";
        } else {
          topic.style.height = "40%";
          topic.style.width = "100%";
          idea.style.height = "60%";
          idea.style.width = "100%";
          topicContents.style.display = "block";
        }
      } else {
        if (topic.style.width == "35%") {
          topic.style.width = "5%";
          topic.style.height = "100%";
          idea.style.width = "95%";
          idea.style.height = "100%";
          topicContents.style.display = "none";
        } else {
          topic.style.width = "35%";
          topic.style.height = "100%";
          idea.style.width = "65%";
          idea.style.height = "100%";
          topicContents.style.display = "block";
        }
      }
    });


  </script>
  <script>
    $(document).ready(function () {
      $('.like-button').click(function () {
        var idea_id = $(this).data('ideaid');
        $.ajax({
          url: 'like.php',
          type: 'POST',
          data: {
            idea_id: idea_id,
            vote: 1
          },
          success: function (result) {

            $('.idea-' + idea_id + ' .like-count').text(result);
            location.reload();
          }
        });
      });

      $('.dislike-button').click(function () {
        var idea_id = $(this).data('ideaid');
        $.ajax({
          url: 'like.php',
          type: 'POST',
          data: {
            idea_id: idea_id,
            vote: 0
          },
          success: function (result) {

            $('.idea-' + idea_id + ' .dislike-count').text(result);
            location.reload();
          }
        });
      });
    });

  </script>

</body>

</html>