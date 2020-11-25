<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Main Page</title>
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
    <h2 class="pheading1">Register now</h2>
    <?php include_once "includes/navbar.php"; ?>
    <div>
      <form action="includes/addcontact.php" method="post">
        <div class="form-group row justify-content-center">
          <label for="login-name" class="col-sm-2 col-form-label" style="font-weight:bold">Login Name</label>
          <input type="text" class="form-control col-sm-5" name="login-name" placeholder="Login Name">
        </div>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] === "usernameInvalid") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Invalid username!</p>";
          }
          else if ($_GET["error"] === "usernameTaken") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Username already taken!</p>";
          }
        }
        ?>
        <div class="form-group row justify-content-center">
          <label for="first-name" class="col-sm-2 col-form-label" style="font-weight:bold">First Name</label>
          <input type="text" class="form-control col-sm-5" name="first-name" placeholder="First Name">
        </div>
        <div class="form-group row justify-content-center">
          <label for="last-name" class="col-sm-2 col-form-label" style="font-weight:bold">Last Name</label>
          <input type="text" class="form-control col-sm-5" name="last-name" placeholder="Last Name">
        </div>
        <div class="form-group row justify-content-center">
          <label for="emailid" class="col-sm-2 col-form-label" style="font-weight:bold">Email</label>
          <input type="text" class="form-control col-sm-5" name="emailid" placeholder="abc@site.com">
        </div>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] === "invalidEmailid") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Invalid email!</p>";
          }
          else if ($_GET["error"] === "emailidTaken") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Email already taken!</p>";
          }
        }
        ?>
        <div class="form-group row justify-content-center">
          <label for="password" class="col-sm-2 col-form-label" style="font-weight:bold">Password</label>
          <input type="password" class="form-control col-sm-5" name="password" placeholder="Enter Password">
        </div>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] === "invalidPassword") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Invalid password!</p>";
          }
        }
        ?>
        <div class="form-group row justify-content-center">
          <label for="re-password" class="col-sm-2 col-form-label" style="font-weight:bold">Confirm Password</label>
          <input type="password" class="form-control col-sm-5" name="re-password" placeholder="Re-enter Password">
        </div>
        <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] === "emptyFields") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Please enter all fields!</p>";
          }
          else if ($_GET["error"] === "passwordNoMatch") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Passwords don't match!</p>";
          }
          elseif ($_GET["error"] === "sqlerror") {
            echo "<p class='form-group row justify-content-center' style='font-style:italic'>Something went wrong! Try again later!</p>";
          }
        }
        ?>
        <div class="form-group row">
          <div class="offset-5">
            <button type="submit" name="register-btn" class="btn btn-primary">Register Now</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
