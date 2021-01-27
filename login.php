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
  <!--link rel="stylesheet" type="text/css" href="resources/css/bootstrap.css"/-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
  <body>
    <?php include_once "includes/navbar.php"; ?>
    <div class="container">
      <div class="card-body p-0">
        <form action="includes/login-validate.php" method="post">
          <div class="form-group row justify-content-center">
            <label for="login-user" class="col-sm-3 col-form-label" style="font-weight:bold">Username / Email ID</label>
            <input type="text" class="form-control col-sm-5" name="login-user" placeholder="Enter username / email address">
          </div>
          <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] === "usernameInvalid") {
              echo "<p class='text-danger form-group row justify-content-center' style='font-style:italic'>Invalid username!</p>";
            }
            elseif ($_GET["error"] === "noUserFound") {
              echo "<p class='text-danger form-group row justify-content-center' style='font-style:italic'>Username not found!</p>";
            }
          }
          ?>
          <div class="form-group row justify-content-center">
            <label for="login-password" class="col-sm-3 col-form-label" style="font-weight:bold">Password</label>
            <input type="password" class="form-control col-sm-5" name="login-password" placeholder="Enter password">
          </div>
          <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] === "emptyFields") {
              echo "<p class='text-danger form-group row justify-content-center' style='font-style:italic'>Please enter all fields!</p>";
            }
            elseif ($_GET["error"] === "invalidPassword") {
              echo "<p class='text-danger form-group row justify-content-center' style='font-style:italic'>Invalid password!</p>";
            }
            elseif ($_GET["error"] === "incorrectPassword") {
              echo "<p class='text-danger form-group row justify-content-center' style='font-style:italic'>Incorrect password!</p>";
            }
            elseif ($_GET["error"] === "sqlerror") {
              echo "<p class='text-danger form-group row justify-content-center' style='font-style:italic'>Something went wrong! Try again later!</p>";
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
    </div>
  </body>
</html>
