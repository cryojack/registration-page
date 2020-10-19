<?php
include "dbconn.php";

$fname = $_POST['first-name'];
$lname = $_POST['last-name'];
$email = $_POST['emailid'];
$comments = $_POST['comments'];

  $sql = "INSERT INTO contacts (firstname,lastname,emailid,comments) VALUES ('$fname','$lname','$email','$comments')";

if(empty($fname) || empty($lname) || empty($email) || empty($comments)) {
  header("Location: ../index.php");
  exit();
} else {

  if (mysqli_query($connectDB,$sql)) {
    echo "Thank you for the comments! We will get back to you shortly!</br>";
    echo "Click <a href='index.php'>here</a> to return to the main page.";
  }
  else {
    echo "Sorry, cannot process your request at the moment, please try again later!</br>";
    echo "Click <a href='index.php'>here</a> to return to the main page.";
  }
}
mysqli_close($connectDB);
?>
