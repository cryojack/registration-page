<?php
session_start();

if (isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../welcome.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Main Page</title>
  <link rel="icon" href="assets/webicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
  <body class="bg-light">
    <?php include_once "includes/navbar.php"; ?>
    <div class="container">
      <div class="card-body p-0">
        <form action="includes/addcontact.php" method="post">
          <div class="form-group row justify-content-center">
            <label for="login-name" class="col-sm-3 col-form-label" style="font-weight:bold">Login Name</label>
            <input type="text" class="form-control col-sm-5" name="login-name" placeholder="Login Name">
          </div>
          <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] === "usernameInvalid") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Invalid username!</p>";
            }
            elseif ($_GET["error"] === "usernameTaken") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Username already taken!</p>";
            }
          }
          ?>
          <div class="form-group row justify-content-center">
            <label for="first-name" class="col-sm-3 col-form-label" style="font-weight:bold">First Name</label>
            <input type="text" class="form-control col-sm-5" name="first-name" placeholder="First Name">
          </div>
          <div class="form-group row justify-content-center">
            <label for="last-name" class="col-sm-3 col-form-label" style="font-weight:bold">Last Name</label>
            <input type="text" class="form-control col-sm-5" name="last-name" placeholder="Last Name">
          </div>
          <div class="form-group row justify-content-center">
            <label for="emailid" class="col-sm-3 col-form-label" style="font-weight:bold">Email</label>
            <input type="text" class="form-control col-sm-5" name="emailid" placeholder="abc@site.com">
          </div>
          <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] === "invalidEmailid") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Invalid email!</p>";
            }
            elseif ($_GET["error"] === "emailidTaken") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Email already taken!</p>";
            }
          }
          ?>
          <div class="form-group row justify-content-center">
            <label for="password" class="col-sm-3 col-form-label" style="font-weight:bold">Password</label>
            <input type="password" class="form-control col-sm-5" name="password" placeholder="Enter Password">
          </div>
          <?php
          if ($_GET["error"] === "invalidPassword") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Invalid password!</p>";
          }
          ?>
          <div class="form-group row justify-content-center">
            <label for="re-password" class="col-sm-3 col-form-label" style="font-weight:bold">Confirm Password</label>
            <input type="password" class="form-control col-sm-5" name="re-password" placeholder="Re-enter Password">
          </div>
          <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] === "emptyFields") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please enter all fields!</p>";
            }
            elseif ($_GET["error"] === "passwordNoMatch") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Passwords don't match!</p>";
            }
            elseif ($_GET["error"] === "sqlerror") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Something went wrong! Try again later!</p>";
            }
            elseif ($_GET["error"] == "registerSuccess") {
              echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Registration successful!</p>";
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
    </div>
  </body>
</html>
