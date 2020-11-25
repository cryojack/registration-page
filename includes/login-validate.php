<?php

// All functions to validate the user at login
if (isset($_POST['login-btn'])) {

  include_once "dbconn.php";
  include_once "user-validate.php";

  $login_id = $_POST['login-user'];
  $login_pwd = $_POST['login-password'];

  if (isLoginEmpty($login_id,$login_pwd) === true) {
    header("Location: ../login.php?error=emptyFields");
    exit();
  }

  if (isUsernameValid($login_id) === false) {
    header("Location: ../login.php?error=usernameInvalid");
    exit();
  }

  // We use the same function to check if a login name with the given input exists
  if (isUsernameTaken($connectDB,$login_id) === false) {
    header("Location: ../login.php?error=noUserFound");
    exit();
  }

  if (isPasswordValid($login_pwd) === false) {
    header("Location: ../login.php?error=invalidPassword");
    exit();
  }

  else {
    loginUser($connectDB,$login_id,$login_pwd);
  }

}
else {
  header("Location: ../login.php?error=noaccess");
  exit();
}
?>
