<?php

if (isset($_POST['register-btn'])) {
  include_once "dbconn.php";
  include_once "user-validate.php";

  $login_name = $_POST['login-name'];
  $first_name = $_POST['first-name'];
  $last_name = $_POST['last-name'];
  $email_id = $_POST['emailid'];
  $password = $_POST['password'];
  $re_password = $_POST['re-password'];

  $user_id = generateUid($connectDB);

  if (isFieldEmpty($login_name,$first_name,$last_name,$email_id,$password,$re_password) === true) {
    header("Location: ../index.php?error=emptyFields");
    exit();
  }

  if (isUsernameValid($login_name) === false) {
    header("Location: ../index.php?error=usernameInvalid");
    exit();
  }

  if (isUsernameTaken($connectDB,$login_name) === true) {
    header("Location: ../index.php?error=usernameTaken");
    exit();
  }

  if (isEmailValid($email_id) === false) {
    header("Location: ../index.php?error=invalidEmailid");
    exit();
  }


  if (isEmailTaken($connectDB,$email_id) === true) {
    header("Location: ../index.php?error=emailidTaken");
    exit();
  }

  if (isPasswordValid($password) === false) {
    header("Location: ../index.php?error=invalidPassword");
    exit();
  }

  if (isPasswordMatch($password,$re_password) === false) {
    header("Location: ../index.php?error=passwordNoMatch");
    exit();
  }

  else {
    createUser($connectDB,$user_id,$login_name,$first_name,$last_name,$email_id,$password);
  }
}
else {
  header("Location: ../index.php?error=noaccess");
  exit();
}

?>
