<?php
session_start();
include_once("connection.php");

if (isset($_POST["logout"])) {
  session_destroy();
  header("Location:index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="stylesss.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="scripts.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<header>
  <div class="logo">
    <a href="index.php"><img src="images/logo.jpg" alt="University Icon"></a>
  </div>
  <div class="us-action">
    <?php if (isset($_SESSION['depart']) && $_SESSION['depart'] == 9) { ?>
      <a style="margin-right: 10px; " onclick="location.href='admin page.php'">Admin
      </a>
    <?php }
    if (isset($_SESSION['depart']) && $_SESSION['depart'] == 6) { ?>
      <a style="margin-right: 10px; " onclick="location.href='coordinator_page.php'">Coordinator
      </a>
    <?php }
    if (isset($_SESSION['depart']) && $_SESSION['depart'] == 8) { ?>
      <a style="margin-right: 10px; " onclick="location.href='student_page.php'">Staff Page</a>
    <?php }
    if (isset($_SESSION['depart']) && $_SESSION['depart'] == 7) { ?>
      <a style="margin-right: 10px; " onclick="location.href='manager_business.php'">Manage Business</a>
    <?php } ?>
    <?php
    if (isset($_SESSION['email']) && $_SESSION['email'] != null) { ?>
      <a onclick="location.href='student_page.php?user_id=<?php echo $_SESSION['us_id']; ?>'">
        <?php echo $_SESSION['email']; ?>
      </a>
      <form method="post">
        <button type="submit" name="logout" style="margin-left: 10px;
    padding: 15px 10px;
    border-radius: 5px;">Logout</button>
      </form>
    <?php } else {
      ?>
      <a href="login page.php">Login</a>
    <?php } ?>
  </div>
</header>

<body>
  <div class="topic">
    <label id="btn-bar" for="nv-bar"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></label>
    <input type="checkbox" id="nv-bar">
    <div class="topic-contents">
      <?php
      include_once('connection.php');

      $sql = "SELECT * FROM IdeaCategories";
      $result = mysqli_query($conn, $sql);


      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<p>" . $row["CategoryName"] . "</p>";
        }
      } else {
        echo "No topics have been added yet";
      }
      ?>
    </div>
  </div>

  <div class="idea">
    <?php
    $idea_sql = "SELECT Users.Email,Ideas.IdeaID, Ideas.SubmissionDate, Ideas.Description, SUM(CASE WHEN Thumbs.Vote = 1 THEN 1 ELSE 0 END) AS Likes, SUM(CASE WHEN Thumbs.Vote = 0 THEN 1 ELSE 0 END) AS Dislikes
  FROM Ideas
  INNER JOIN Users ON Ideas.UserID = Users.UserID
  LEFT JOIN Thumbs ON Ideas.IdeaID = Thumbs.IdeaID
  where ideas.status=1
  GROUP BY Ideas.IdeaID, Users.Email, Ideas.SubmissionDate, Ideas.Description
  ORDER BY Ideas.IdeaID DESC";
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
                if (in_array(strtolower($filetype), array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'ico', 'svg'))) {

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
          <div class="post-comments">
            <form class="comment-form" id="comment-form-<?php echo $idea_row['IdeaID']; ?>">
              <input type="hidden" name="idea_id" value="<?php echo $idea_row['IdeaID']; ?>">
              <textarea name="comment" class="comment-input" placeholder="Leave a comment"></textarea>
              <label style="display:inline-block;">
                <input type="checkbox" name="anonymous" value="1">
                Anonymous
              </label>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>



        </div>


        <?php
      }
    }

    ?>
  </div>

  </div>
  </div>
  ?>
  <script>
    $(document).ready(function () {
      $('.comment-form').submit(function (event) {
        event.preventDefault();
        if ("<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" == "") {
          alert("Please log in to add a comment");
        }
        else {
          var form = $(this);
          var ideaId = form.find('input[name="idea_id"]').val();
          var commentText = form.find('textarea[name="comment"]').val();
          var anonymous = form.find('input[name="anonymous"]').is(':checked') ? 1 : 0;
          $.ajax({
            url: 'add_comment.php',
            type: 'POST',
            data: {
              idea_id: ideaId,
              comment_text: commentText,
              anonymous: anonymous
            },
            success: function (result) {

              $('#comment-form-' + ideaId).siblings('.post-comments').append(result);

              form.find('textarea[name="comment"]').val('');
              location.reload();
            }
          });
        }
      });
    });


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