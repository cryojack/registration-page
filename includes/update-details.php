<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  include_once "dbconn.php";
  include_once "user-validate.php";

  if (isset($_POST['update-details-btn'])) {

    $fname_up = $_POST["fname-up"];
    $lname_up = $_POST["lname-up"];
    $email_up = $_POST["email-up"];

    if (isUpdateDetailFieldEmpty($fname_up,$lname_up,$email_up) === true) {
      header("Location: ../update-profile.php?error=emptyDetailFields");
    }

    elseif (!empty($email_up) && isEmailValid($email_up) === false) {
      header("Location: ../update-profile.php?error=invalidEmailid");
    }

    elseif (!empty($email_up) && isEmailTaken($connectDB,$email_up) === true) {
      header("Location: ../update-profile.php?error=emailidTaken");
    }

    else {
      updateDetails($connectDB,$fname_up,$lname_up,$email_up);
    }
  }

  if (isset($_POST['update-pass-btn'])) {

    $oldpass_up = $_POST["old-password-up"];
    $newpass_up = $_POST["new-password-up"];
    $newrepass_up = $_POST["new-repassword-up"];

    if (isUpdatePasswordFieldEmpty($oldpass_up,$newpass_up,$newrepass_up) === true) {
      header("Location: ../update-profile.php?error=emptyPasswordFields");
    }

    elseif (!empty($oldpass_up) && oldPasswordMatch($connectDB,$oldpass_up) === false) {
      header("Location: ../update-profile.php?error=incorrectOldPassword");
    }

    elseif (!empty($newpass_up) && isPasswordValid($newpass_up) === false) {
      header("Location: ../update-profile.php?error=invalidPassword");
    }

    elseif (!empty($newpass_up) && !empty($newrepass_up) && isPasswordMatch($newpass_up,$newrepass_up) === false) {
      header("Location: ../update-profile.php?error=passwordNoMatch");
    }

    else {
      updatePassword($connectDB,$newpass_up);
    }
  }
/*************************************************************************************
We want this piece of code to take the image which was uploaded and :
- copy this to the new location in ~/data/images/profile-pics
- rename it to the user's uid eg:- ~/data/images/profile-pics/[uid].jpeg
- copy the path of the new file and insert it into the database field profile_pic_path
- show it on the main profile page
- If the user uploads a new image, delete existing image and restart all steps
**************************************************************************************/
  if (isset($_POST['update-profile-pic-btn'])) {
    $upload_file_name = $_FILES['profilepic']['name'];
    $upload_file_tmpname = $_FILES['profilepic']['tmp_name'];
    $upload_file_type = $_FILES['profilepic']['type'];
    $upload_file_size = $_FILES['profilepic']['size'];
    $upload_file_error = $_FILES['profilepic']['error'];

    if (empty($upload_file_size)) {
      header("Location: ../update-profile.php?error=emptyPictureField");
    }

    elseif (isUploadedImgBig($upload_file_size) === true) {
      header("Location: ../update-profile.php?error=largePictureUploaded");
    }

    elseif (isImgTypeCorrect($upload_file_name) === false) {
      header("Location: ../update-profile.php?error=imgFormatNotSupported");
    }

    elseif ($upload_file_error != 0) {
      header("Location: ../update-profile.php?error=imgUploadError");
    }

    else {
      uploadProfilePic($connectDB,$upload_file_name,$upload_file_tmpname);
    }
  }

  if (isset($_POST['delete-profile-pic-btn'])) {
    if ($_SESSION["prf_pic"] === NULL) {
      header("Location: ../update-profile.php?error=noImgPresent");
    } else {
      profileImgDelete($connectDB);
    }
  }

}
else {
    header("Location: ../update-profile.php?error=sqlerror");
  }
?>
