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
<html>

<head>
	<title>Admin Page</title>
	<link rel="stylesheet" href="styleadmin.css">
	<script src="scripts.js"></script>
</head>

<body>
	<a href="index.php" style="all:unset;">Back</a>
	<div class="us-action">
		<?php
		if (isset($_SESSION['email']) && $_SESSION['email'] != null) { ?>
			<h1 onclick="location.href='student_page.php?user_id=<?php echo $_SESSION['us_id']; ?>'">
				<?php echo "WELCOME ADMIN"; ?>
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
	<br>
	<h1>Information Of User Table</h1>
	<table>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Role</th>
			<th>Faculty</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		<?php


		include_once("connection.php");




		$query = "SELECT users.UserID, users.LastName, users.Email, users.AgreedToTerms, departments.DepartmentName
			FROM users
			INNER JOIN departments ON users.DepartmentID = departments.DepartmentID";
		$result = mysqli_query($conn, $query);


		while ($row = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>" . $row['LastName'] . "</td>";
			echo "<td>" . $row['Email'] . "</td>";
			if ($row['AgreedToTerms'] == 1) {
				echo "<td>Role Accepted</td>";
			} else {
				echo "<td>" . $row['AgreedToTerms'] . "</td>";
			}
			echo "<td>" . $row['DepartmentName'] . "</td>";
			echo "<td><button onclick=\"location.href='update_student.php?id=" . $row['UserID'] . "'\" class=\"edit\">Edit</button></td>";
			echo "<td><button onclick=\"location.href='delete_student.php?id=" . $row['UserID'] . "'\" class=\"delete\">Delete</button></td>";
			echo "</tr>";
		}



		?>
	</table>
	<br>
	<button onclick="location.href='create_user.php'" class="add">Add New User</button>
	<br>
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


		while ($row = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>" . $row['IdeaID'] . "</td>";
			echo "<td>" . $row['Title'] . "</td>";
			echo "<td>" . $row['Description'] . "</td>";
			echo "<td>" . $row['SubmissionDate'] . "</td>";
			echo "<td>" . $row['ClosureDate'] . "</td>";
			echo "<td>" . $row['FinalClosureDate'] . "</td>";
			echo "<td>" . $row['LastName'] . "</td>";
			if ($row['status'] == 1) {
				echo "<td>Accepted</td>";
			} else {
				echo "<td>Not Accept</td>";
			}
			echo "<td><button onclick=\"location.href='update_idea.php?id=" . $row['IdeaID'] . "'\" class=\"edit\">Edit</button></td>";
			echo "<td><button onclick=\"location.href='delete_idea.php?id=" . $row['IdeaID'] . "'\" class=\"delete\">Delete</button></td>";
			echo "</tr>";
		}


		//mysqli_close($conn);
		?>
	</table>
	<br>
	<button onclick="location.href='add_idea.php?user_id=<?php echo $_SESSION['us_id']; ?>'" class="add">Add New
		Idea</button>
	<!-- <button onclick="location.href='u.php?user_id=<?php echo $_SESSION['us_id']; ?>'" class="add">Topic Management Page</button> -->
	<br>
	<br>
	<h1>Topic Current</h1>
	<table>
		<thead>
			<tr>
				<th>Topic Name</th>
				<th>Description</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php

			// Connect to the database
			include_once("connection.php");
			// Retrieve data from the ideacategories table
			$query = "SELECT * FROM ideacategories";
			$result = mysqli_query($conn, $query);

			// Check if the query executed successfully
			if (!$result) {
				die("Query failed: " . mysqli_error($conn));
			}

			// Display data from the ideacategories table
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>" . $row['CategoryName'] . "</td>";
				echo "<td>" . $row['Description'] . "</td>";
				echo "<td><button onclick=\"location.href='update_topic.php?id=" . $row['IdeaCategoryID'] . "'\" class=\"edit\">Edit</button></td>";
				echo "<td><button onclick=\"location.href='delete_topic.php?id=" . $row['IdeaCategoryID'] . "'\" class=\"delete\">Delete</button></td>";
				echo "</tr>";
			}
			// Close the connection
			//mysqli_close($conn);
			?>
		</tbody>
	</table>
	<br>
	<button onclick="location.href='add_topic_page.php'" class="add">Add New Topic</button>
</body>

</html>