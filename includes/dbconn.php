<?php

include_once "config.php";

$connectDB = new mysqli($servername,$dbusername,$dbpassword,$dbselect); // Connection to main database incl. user details
$connectDB2 = new mysqli($servername,$dbusername,$dbpassword,$dbselect2); // Connection to the gallery database

if ($connectDB == false){
  die("Error : Cannot connect to the server/database. " . $connectDB->connect_error());
}

if ($connectDB2 == false){
  die("Error : Cannot connect to the server/database. " . $connectDB2->connect_error());
}
?>

