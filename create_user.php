<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Create User</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<h1>Create User</h1>
	<form action="create_user.php" method="post">
		<label for="firstname">First Name:</label>
		<input type="text" name="firstname" id="firstname" required>

		<label for="lastname">Last Name:</label>
		<input type="text" name="lastname" id="lastname" required>

		<label for="email">Email:</label>
		<input type="text" name="email" id="email" required>

		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required>

		<label for="department">Department:</label>
		<select name="department" id="department" required>
			<option value="">-- Select Department --</option>
			<?php
			// Connect to database
			include_once('connection.php');

			// Get list of departments from database
			$sql = "SELECT DepartmentID, DepartmentName FROM Departments ORDER BY DepartmentName";
			$result = $conn->query($sql);

			// Loop through departments and display in select element
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo "<option value=\"" . $row["DepartmentID"] . "\">" . $row["DepartmentName"] . "</option>";
				}
			}
			?>
			<option value="new">Add New Department</option>
		</select>

		<div id="new-department">
			<label for="new_department_name">New Department Name:</label>
			<input type="text" name="new_department_name" id="new_department_name">
		</div>
		<label for="terms">Agree to Terms:</label>
		<input type="checkbox" name="terms" id="terms" required>Agree

		<input type="submit" name="create" value="Create">
		<a href="admin page.php" style="all:unset;">Back</a>

		</form>
</body>
<?php
if(isset($_POST['create'])){
include_once('connection.php');
// Get user input
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$department_id = $_POST['department'];
$new_department_name = (isset($_POST['new_department_name'])) ? $_POST['new_department_name'] : '';


// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if new department was selected
if ($department_id === 'new' && !empty($new_department_name)) {
  // Insert new department into database using prepared statement
  $insert_department_sql = "INSERT INTO Departments (DepartmentName) VALUES (?)";
  $stmt = $conn->prepare($insert_department_sql);
  $stmt->bind_param("s", $new_department_name);
  $stmt->execute();

  // Get ID of new department
  $department_id = $stmt->insert_id;
}
$AgreedToTerms=true;
// Insert user into database using prepared statement
$insert_user_sql = "INSERT INTO Users (FirstName, LastName, Email, Password, AgreedToTerms, DepartmentID) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_user_sql);
$stmt->bind_param("ssssii", $firstname, $lastname, $email, $hashed_password, $AgreedToTerms ,$department_id);
$stmt->execute();

// Close prepared statement
$stmt->close();
header("Location:admin page.php");
}
// Connect to database

?>

<script>
	// Get the department select element and the new department input element
const departmentSelect = document.getElementById('department');
const newDepartmentInput = document.getElementById('new-department');

// Hide the new department input element
newDepartmentInput.style.display = 'none';

// Add event listener to the department select element
departmentSelect.addEventListener('change', function() {
  if (departmentSelect.value === 'new') {
    // Show the new department input element
    newDepartmentInput.style.display = 'block';
  } else {
    // Hide the new department input element
    newDepartmentInput.style.display = 'none';
  }
});
</script>
		