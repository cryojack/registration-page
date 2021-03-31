<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}

if ($_SESSION["lgname"] !== "ADMIN") {
  header("Location: ../welcome.php?error=noaccess");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include_once "dbconn.php";
  include_once "user-validate.php";
  
  if (isset($_POST['adm-view-usr-btn'])) {
    header("Location: ../show-user.php?id=" . $_GET['id']);
  }
  
  if (isset($_POST['adm-update-usr-btn'])) {
    header("Location: ../update-user.php?id=" . $_GET['id']);
  }
  
  if (isset($_POST['adm-delete-usr-btn'])) {
    deleteUser($connectDB,$_GET["id"]);
  }
  
  if (isset($_POST['adm-disable-user-btn'])) {
    disableUser($connectDB,$_GET["id"]);
  }
  
  if (isset($_POST['adm-enable-user-btn'])) {
    enableUser($connectDB,$_GET["id"]);
  }
  
  /*  Admin update functions  */

  if (isset($_POST['adm-update-usr-details-btn'])) {
    $adm_fname_up = $_POST["adm-fname-up"];
    $adm_lname_up = $_POST["adm-lname-up"];
    $adm_email_up = $_POST["adm-email-up"];
    
    if (isUpdateDetailFieldEmpty($adm_fname_up,$adm_lname_up,$adm_email_up) === true) {
      header("Location: ../update-profile.php?error=emptyDetailFields");
    }

    elseif (!empty($adm_email_up) && isEmailValid($adm_email_up) === false) {
      header("Location: ../update-profile.php?error=invalidEmailid");
    }

    elseif (!empty($adm_email_up) && isEmailTaken($connectDB,$adm_email_up) === true) {
      header("Location: ../update-profile.php?error=emailidTaken");
    }
    admUpdateUsrDetails($connectDB,$_GET["id"]);
  }
  
  if (isset($_POST['adm-update-usr-pass-btn'])) {
    admUpdateUsrPassword($connectDB,$_GET["id"]);
  }
  
  if (isset($_POST['adm-update-usr-profilepic-btn'])) {
    admUpdateUsrProfilePic($connectDB,$_GET["id"]);
  }
  
  if (isset($_POST['adm-delete-usr-profilepic-btn'])) {
    admDeleteUsrProfilePic($connectDB,$_GET["id"]);
  }
}
else {
  header("Location: ../dashboard-users.php?error=sqlerror");
}
?>