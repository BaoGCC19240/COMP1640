<!DOCTYPE html>
<html>
<head>
	<title>Update Idea Category</title>
	<link rel="stylesheet" href="add_topic.css">
</head>
<body>
	<h1>Update Topic</h1>
	<?php
		// connect to the database
		include_once('connection.php');
		// check if id is set and is a number
	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$id = $_GET['id'];
		
		// prepare the SQL query
		$sql = "SELECT CategoryName, Description FROM ideacategories WHERE IdeaCategoryID=$id";
		$stmt = $conn->prepare($sql);
		
		if($stmt){
			// bind the id parameter and execute the query
			//$stmt->bind_param("id", $id);
			$stmt->execute();
			
			// bind the results to variables
			$stmt->bind_result($name, $description);
			$stmt->fetch();
			
			// display the form
			echo '<form action="" method="post">';
			echo '<input type="hidden" name="id" value="'.$id.'">';
			echo '<label for="name">Topic Name:</label>';
			echo '<input type="text" name="name" id="name" value="'.$name.'" required>';
			echo '<br>';
			echo '<label for="description">Description:</label>';
			echo '<textarea name="description" id="description" required>'.$description.'</textarea>';
			echo '<br>';
			echo '<input type="submit" name="update-topic" value="Update Topic">';
			echo '<a href="admin page.php" style="all:unset;">Back</a>';
			echo '</form>';
			
			$stmt->close();
		} else {
			echo "Error preparing SQL statement: " ;//. $conn->error;
		}
	} else {
		// if no id is provided, redirect to the page showing all idea categories
		echo '<meta http-equiv="refresh" content="0;URL=index.php"/>';
		exit();
	}
?>
</body>
</html>
<?php
	// update the values in the database if the form is submitted
	if(isset($_POST['update-topic'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		// update the data in the IdeaCategories table
		$sql = "UPDATE ideacategories SET CategoryName=?, Description=? WHERE IdeaCategoryID=?";
		$stmt = $conn->prepare($sql);
		if (!$stmt) {
			echo "Error: " . $sql . "<br>";// . $conn->error;
		}
		$stmt->bind_param("ssi", $name, $description, $id);
		$stmt->execute();
		
		// redirect to the page showing all idea categories
		echo '<meta http-equiv="refresh" content="0;URL=u.php"/>';
		exit();
	}
?>