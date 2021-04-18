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
  
  if (isset($_POST['upload-img-bttn'])) {
    $upload_img_title = $_POST['upload-img-title'];
    $upload_img_desc = $_POST['upload-img-desc'];

    $upload_img_name = $_FILES['gallerypic']['name'];
    $upload_img_tmp = $_FILES['gallerypic']['tmp_name'];
    $upload_img_type = $_FILES['gallerypic']['type'];
    $upload_img_size = $_FILES['gallerypic']['size'];
    $upload_img_error = $_FILES['gallerypic']['error'];
    
    if (empty($upload_img_title)) {
      header("Location: ../upload-image.php?error=noTitleGiven");
    }
    
    //elseif (empty($upload_img_desc)) {
      //$upload_img_desc = "NO DESCRIPTION GIVEN";
    //}

    elseif (empty($upload_img_size)) {
      header("Location: ../upload-image.php?error=noImagePresent");
    }

    elseif (isUploadedImgBig($upload_img_size) === true) {
      header("Location: ../upload-image.php?error=imgSizeLarge");
    }

    elseif (isImgTypeCorrect($upload_img_name) === false) {
      header("Location: ../upload-image.php?error=imgFormatUnsupported");
    }

    elseif ($upload_img_error != 0) {
      header("Location: ../upload-image.php?error=imgUploadError");
    }

    else {
      /*
      echo $upload_img_title . "</br>";
      echo $upload_img_desc . "</br>";
      echo $upload_img_name . "</br>";
      echo $upload_img_tmp . "</br>";
      echo $upload_img_type . "</br>";
      echo $upload_img_size . "</br>";
      echo $upload_img_error;
      */
      uploadGalleryPic($connectDB2,$_SESSION["uid"],$upload_img_name,$upload_img_tmp,$upload_img_title,$upload_img_desc);
    }
  }
}
else {
    header("Location: ../upload-image.php?error=sqlerror");
  }

?>