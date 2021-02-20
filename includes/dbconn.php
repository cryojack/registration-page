<?php

include_once "config.php";

$connectDB = new mysqli($servername,$dbusername,$dbpassword,$dbselect);

if ($connectDB == false){
  die("Error : Cannot connect to the server/database. " . $connectDB->connect_error());
}

?>

