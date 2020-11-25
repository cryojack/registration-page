<?php
session_start();
if (isset($_SESSION["IS_LOGGED_IN"]) && $_SESSION["IS_LOGGED_IN"] === true) {
  header("Location: ../welcome.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login here</title>
  <link rel="icon" href="assets/webicon.png"/>
  <link rel="stylesheet" type="text/css" href="resources/css/bootstrap.css"/>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .pheading1{
      text-align: center;
      font-weight: bold;
      text-decoration: underline;
    }
    .pdiv1{
      margin-left: 10em;
      margin-top: 2em;
      margin-right: 10em;
    }
  </style>
</head>
  <body>
    <h2 class="pheading1">Welcome</h2>
    <?php include_once "includes/navbar.php"; ?>
    <div>
      <form action="includes/login-validate.php" method="post">
        <div class="form-group row justify-content-center">
          <label for="login-user" class="col-sm-2 col-form-label" style="font-weight:bold">Username / Email ID</label>
          <input type="text" class="form-control col-sm-5" name="login-user" placeholder="Enter username / email address">
        </div>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] === "usernameInvalid") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Invalid username!</p>";
          }
          else if ($_GET["error"] === "noUserFound") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Username not found!</p>";
          }
        }
        ?>
        <div class="form-group row justify-content-center">
          <label for="login-password" class="col-sm-2 col-form-label" style="font-weight:bold">Password</label>
          <input type="password" class="form-control col-sm-5" name="login-password" placeholder="Enter password">
        </div>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] === "emptyFields") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Please enter all fields!</p>";
          }
          else if ($_GET["error"] === "invalidPassword") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Invalid password!</p>";
          }
          else if ($_GET["error"] === "incorrectPassword") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Incorrect password!</p>";
          }
          else if ($_GET["error"] === "sqlerror") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Something went wrong! Try again later!</p>";
          }
        }
        ?>
        <div class="form-group row">
          <div class="offset-5">
            <button type="submit" name="login-btn" class="btn btn-primary">Login</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
