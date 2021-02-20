<?php

session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}

if ($_SESSION["lgname"] === "ADMIN") {
  include_once "dbconn.php";
  include_once "user-validate.php";
  displayAllMessages($connectDB);
} else {
  header("Location: ../welcome.php?error=noaccess");
  exit();
}

?>