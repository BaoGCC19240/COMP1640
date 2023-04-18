<!DOCTYPE html>
<html>
<head>
	<title>Admin Page</title>
    <link rel="stylesheet" href="styleadmin.css">
</head>
<html>
<body>
    

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
		while($row = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>".$row['CategoryName']."</td>";
			echo "<td>".$row['Description']."</td>";
			echo "<td><button onclick=\"location.href='update_topic.php?id=".$row['IdeaCategoryID']."'\" class=\"edit\">Edit</button></td>";
			echo "<td><button onclick=\"location.href='delete_topic.php?id=".$row['IdeaCategoryID']."'\" class=\"delete\">Delete</button></td>";
			echo "</tr>";
		}
		// Close the connection
		mysqli_close($conn);
	?>
	</tbody>
</table>
<br>
<button onclick="location.href='add_topic_page.php'" class="add">Add New Topic</button>
<br>
</body>
</html>