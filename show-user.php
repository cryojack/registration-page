<?php
session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}

if ($_SESSION["lgname"] !== "ADMIN") {
  header("Location: ../welcome.php?error=noaccess");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Username's profile</title>
  <link rel="icon" href="assets/webicon.png"/>
  <!--link rel="stylesheet" type="text/css" href="resources/css/bootstrap.css"/-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="resources/js/demo-page.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
  <body class="bg-light">
    <?php include_once "includes/navbar.php"; ?>
    <div class="container">
      <div class="card-body">
        <?php
        if ($_GET["error"] === "userBanSuccess") {
          echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>User banned successfully!</p>";
        }
        if ($_GET["error"] === "userUnbanSuccess") {
          echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>User unbanned successfully!</p>";
        }
        if ($_GET["error"] === "sqlerror") {
          echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Something went wrong, try again later!</p>";
        }
        include_once "includes/dbconn.php";
        include_once "includes/user-validate.php";
        showUserDetails($connectDB,$_GET['id']);
        ?>
      </div>
    </div>
  </body>
</html>