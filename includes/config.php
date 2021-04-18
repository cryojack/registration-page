<?php
/*
$file = "../assets/dbpass.txt";
if (file_exists($file)) {
  $handle = fopen($file, "r") or die("Cannot open file");
  $contents = fread($handle,filesize($file));
  fclose($handle);
  $DBPASS = trim($contents);
}
else {
  echo "File not found!";
}
*/
$DBPASS = "system123";
$servername = "localhost";
$dbusername = "root";
$dbpassword = $DBPASS;
$dbselect = "demo-page";
$dbselect2 = "demo-page-gallery";

?>
