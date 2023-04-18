<?php
	// Connect to database
	$servername = "localhost";
	$username = "your_username";
	$password = "your_password";
	$dbname = "your_database_name";

	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get form data
	$name = $_POST["name"];
	$email = $_POST["email"];
	$role = $_POST["role"];
	$faculty = $_POST["faculty"];

	// Insert new student into database
	$sql = "INSERT INTO students (name, email, role, faculty) VALUES ('$name', '$email', '$role', '$faculty')";

	if ($conn->query($sql) === TRUE) {
		echo "New student added successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	// Close the database connection
	$conn->close();
?>
