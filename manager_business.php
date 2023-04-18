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
	<title>Welcome Manager</title>
	<link rel="stylesheet" href="styleadmin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

</head>
<body>
	<div class="us-action">
    <?php
    if (isset($_SESSION['email']) && $_SESSION['email'] != null) { ?>
    <h1 onclick="location.href='student_page.php?user_id=<?php echo $_SESSION['us_id']; ?>'">
        <?php echo "WELCOME BUSINESS MANAGER"; ?>
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
	<h1>Statistic Number of Ideas</h1>
	<?php
		// connect to the database
		include_once('connection.php');

	
		$sql = "SELECT COUNT(*) as total, IdeaCategoryID FROM ideacategorymapping GROUP BY IdeaCategoryID";
		$result = $conn->query($sql);

	
		$it_count = 0;
		$business_count = 0;
		$design_count = 0;
		$total_count = 0;

		if ($result->num_rows > 0) {
		 
		  while ($row = $result->fetch_assoc()) {
		    $category_id = $row['IdeaCategoryID'];
		    $total = $row['total'];

		    if ($category_id == 1) {
		      $design_count = $total;
		    } elseif ($category_id == 2) {
		      $business_count = $total;
		    } elseif ($category_id == 3) {
		      $it_count = $total;
		    }

		    $total_count += $total;
		  }
		}

	
		echo "<table>
		        <tr>
		            <th></th>
		            <th>IT</th>
		            <th>Business</th>
		            <th>Design</th>
		            <th>Totals</th>
		        </tr>
		        <tr>
		            <td>Number of Ideas</td>
		            <td>{$it_count}</td>
		            <td>{$business_count}</td>
		            <td>{$design_count}</td>
		            <td>{$total_count}</td>
		        </tr>
		    </table><br><br>";

		// Query the database to get user idea counts
		$sql = "SELECT u.UserID, u.LastName, u.Email, COUNT(i.IdeaID) AS IdeaCount
				FROM users u
				INNER JOIN Ideas i ON u.UserID = i.UserID
				GROUP BY u.UserID";
		$result = $conn->query($sql);

		// Output the results in the table
		if ($result->num_rows > 0) {
			echo "<table>
					<tr>
						<th>User ID</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Idea Count</th>
					</tr>";
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . $row['UserID'] . "</td>";
				echo "<td>" . $row['LastName'] . "</td>";
				echo "<td>" . $row['Email'] . "</td>";
				echo "<td>" . $row['IdeaCount'] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No results found.";
		}
        ?>
        <!-- create a button to export both tables to an excel file -->
        <button onclick='exportBothTablesToExcel()' class="add">Export Both Tables to Excel</button>

        <!-- create a script to export both tables to an excel file -->
        <script>
        function exportBothTablesToExcel() {
        // create a new workbook
        var wb = XLSX.utils.book_new();

        // create a new worksheet for the first table
        var ws1 = XLSX.utils.table_to_sheet(document.getElementsByTagName('table')[0]);
        XLSX.utils.book_append_sheet(wb, ws1, 'Statistic Number of Ideas');

        // create a new worksheet for the second table
        var ws2 = XLSX.utils.table_to_sheet(document.getElementsByTagName('table')[1]);
        XLSX.utils.book_append_sheet(wb, ws2, 'User Idea Counts');

        // save the workbook as an excel file
        XLSX.writeFile(wb, 'Both_Tables.xlsx');
        }
        </script>

    </body>
</html>

