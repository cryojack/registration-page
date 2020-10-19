<!DOCTYPE html>
<html lang="en">
<head>
  <title>All Contacts Here</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="resources/css/bootstrap.css"/>
</head>
<body>
  <h2>All comments here</h2>
<?php
include "dbconn.php";
$sql = "SELECT * FROM contacts";
echo "<br/>";
  echo "<table class='table table-bordered'>";
  echo "<tr>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
    echo "<th>Email Id Name</th>";
    echo "<th>Comments</th>";
  echo "</tr>";

if ($result = mysqli_query($connectDB, $sql)) {
  while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
      echo "<th>".$row[firstname]."</th>";
      echo "<th>".$row[lastname]."</th>";
      echo "<th>".$row[emailid]."</th>";
      echo "<th>".$row[comments]."</th>";
    echo "</tr>";
  }
}
else {
  header("Location: ../index.php?sqlerror");
}
echo "</table>";
?>
</body>
</html>
