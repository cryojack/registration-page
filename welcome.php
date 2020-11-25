<?php
session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Welcome</title>
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
    margin-right: 10em;
  }
  </style>
</head>
  <body>
    <h2 class="pheading1">Welcome <?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]?>!</h2>
    <?php include_once "includes/navbar.php"; ?>
    <div class=" pdiv1 justify-content-center">
      <ul class="list-group">
        <li class="list-group-item"><h4 class="pdiv1">User ID - <?php echo $_SESSION["uid"]?></h4></li>
        <li class="list-group-item"><h4 class="pdiv1">Username - <?php echo $_SESSION["lgname"]?></h4></li>
        <li class="list-group-item"><h4 class="pdiv1">Email - <?php echo $_SESSION["email"]?></h4></li>
        <li class="list-group-item"><h4 class="pdiv1">Date joined - <?php echo $_SESSION["date"]?></h4></li>
      </ul>
    </div>
  </br>
    <div class="form-group row">
      <div class="offset-5">
        <a name="logout-btn" class="btn btn-primary" href="includes/logout.php">Logout</a>
      </div>
  </body>
</html>
