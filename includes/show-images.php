<?php

session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}

include_once "dbconn.php";
include_once "user-validate.php";
showUserImages($connectDB2,$_SESSION["uid"]);

?>