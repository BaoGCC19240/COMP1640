<?php
// Replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your database details
session_start();
include_once("connection.php");



// Get the UserID from the URL parameter
$UserID = $_GET['id'];

// Query the database to retrieve the current information of the student
$sql = "SELECT * FROM users WHERE UserID = $UserID";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error: " . mysqli_error($conn));
}
$row = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    // Get the updated information of the student from the form
    $name = $_POST['firstname'];
	$lastname = $_POST['lastname'];
    $email = $_POST['email'];

    
    // Update the information of the student in the database
    $sql = "UPDATE users SET FirstName='$name', Email='$email', LastName='$lastname' WHERE UserID=$UserID";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "User information updated successfully.";
    } else {
        echo "Error updating user information: " . mysqli_error($conn);
    }
	header("Location:admin page.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Update Staff Information</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<h1>Update Staff Information</h1>
	<form action="update_student.php?id=<?php echo $UserID; ?>" method="POST">
		<label for="firstname">First Name:</label>
		<input type="text" name="firstname" id="firstname" value="<?php echo $row['FirstName']; ?>" required>

		<label for="lastname">Last Name:</label>
		<input type="text" name="lastname" id="lastname" value="<?php echo $row['LastName']; ?>" required>

		<label for="email">Email:</label>
		<input type="text" name="email" id="email" value="<?php echo $row['Email']; ?>" required>

		<label for="password">Password:</label>
		<input type="password" name="password" id="password" value="<?php echo $row['Password']; ?>"required>	

		<input type="submit" name="submit" value="Update Information">
		<a href="admin page.php" style="all:unset;">Back</a>
	</form>
</body>
</html>

<?php
mysqli_close($conn);
?>
