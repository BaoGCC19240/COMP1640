<!DOCTYPE html>
<html>

<head>
  <title>Document List</title>
</head>

<body>
  <h1>Document List</h1>
  <table>
    <thead>
      <tr>
        <th>Document Name</th>
        <th>File Type</th>
        <th>Download</th>
      </tr>
    </thead>
    <tbody>
      <?php

      include_once('connection.php');

      $sql = "SELECT * FROM `documents`";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['DocumentName'] . "</td>";
          echo "<td>" . $row['FileType'] . "</td>";
          echo "<td><a href='download.php?id=" . $row['DocumentID'] . "'>Download</a></td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>
</body>

</html>