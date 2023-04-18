<?php
session_start();
include_once("connection.php");

if (isset($_POST["logout"])) {
	session_destroy();
	header("Location: login page.php");
	exit();}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Coordinator Manager Page</title>
	<link rel="stylesheet" href="styleadmin.css">
</head>
<body>
<div class="us-action">
<a href="index.php" style="all:unset;">Back</a>
    <?php
    if (isset($_SESSION['email']) && $_SESSION['email'] != null) { ?>
    <h1 onclick="location.href='student_page.php?user_id=<?php echo $_SESSION['us_id']; ?>'">
        <?php echo "WELCOME COORDINATOR MANAGER"; ?>
	</h1>
      <form method="post">
        <button type="submit" name="logout" style="margin-left: 48%;
		padding: 15px 10px; background-color:darkgrey;
		border-radius: 5px;" class="add">Logout</button>
	</form>
    <?php } else {
      ?>
      <a href="login page.php">Login</a>
    <?php } ?>
  	</div>
<h1>Ideas Table</h1>
	<table>
		<tr>
			<th>Idea Id</th>
			<th>Title</th>
			<th>Description</th>
			<th>SubmissionDate</th>
			<th>ClosureDate</th>
			<th>FinalClosureDate</th>
			<th>User Name</th>
			<th>Status</th>
			<th></th>
			<th></th>
		</tr>
		<?php

		
		include_once("connection.php");

		
		$query = "SELECT ideas.IdeaID, ideas.Title, ideas.Description, ideas.SubmissionDate, ideas.ClosureDate, ideas.FinalClosureDate, users.LastName, ideas.status
		FROM ideas
		INNER JOIN users ON ideas.UserID = users.UserID";
		$result = mysqli_query($conn, $query);

	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>".$row['IdeaID']."</td>";
			echo "<td>".$row['Title']."</td>";
			echo "<td>".$row['Description']."</td>";
			echo "<td>".$row['SubmissionDate']."</td>";
			echo "<td>".$row['ClosureDate']."</td>";
			echo "<td>".$row['FinalClosureDate']."</td>";
			echo "<td>".$row['LastName']."</td>";
			if ($row['status'] == 1) {
				echo "<td>Accepted</td>";
			} else {
				echo "<td>Not Accept</td>";
			}
			echo "<td><button onclick=\"location.href='update_idea.php?id=".$row['IdeaID']."'\" class=\"edit\">Edit</button></td>";
			echo "<td><button onclick=\"location.href='delete_idea.php?id=".$row['IdeaID']."'\" class=\"delete\">Delete</button></td>";
			echo "</tr>";
		}

	
		//mysqli_close($conn);
	?>
	</table>
	<br>
	<button onclick="location.href='add_idea.php?user_id=<?php echo $_SESSION['us_id']; ?>'" class="add">Add New Idea</button>
	<button onclick="sortTable()" class="add">Sort By Name</button>
<script>
	function sortTable() {
		var table, rows, switching, i, x, y, shouldSwitch;
		table = document.getElementsByTagName("table")[0];
		switching = true;
		while (switching) {
			switching = false;
			rows = table.rows;
			for (i = 1; i < (rows.length - 1); i++) {
				shouldSwitch = false;
				x = rows[i].getElementsByTagName("td")[1];
				y = rows[i + 1].getElementsByTagName("td")[1];
				if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
					shouldSwitch = true;
					break;
				}
			}
			if (shouldSwitch) {
				rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
				switching = true;
			}
		}
	}
</script>
</body>
</html>

