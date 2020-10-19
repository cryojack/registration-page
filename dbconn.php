<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "system123";
$dbselect = "users";

$connectDB = mysqli_connect($servername,$dbusername,$dbpassword,$dbselect);

if ($connectDB == false){
  die("Error : Cannot connect to the server/database. " . mysqli_connect_error());
}

?>
