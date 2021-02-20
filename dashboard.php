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
  <title>Welcome</title>
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
  <body class="bg-light">
    <?php include_once "includes/navbar.php"; ?>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-sm-3 justify-content-center" >
          <div class="card">
            <div class="card-body text-center">
              <?php
              if ($_SESSION['prf_pic'] === NULL) {
                echo "NO PHOTO UPLOADED!";
              }
              else {
                echo '<img src = "'. $_SESSION['prf_pic'] .'" alt="Your Profile Pic">';
              }
              ?>
            </div>
          </div>
        </div>
        <div class="col-sm-6" >
          <div class="card-body">
            <h3>Welcome <b><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"] ?></b>!</h3>
            <h4>You have <b><?php echo $_SESSION["msg_count"] ?></b> messages!</h4>
          </div>
        </div>
      </div>
      
      <div class="row justify-content-center">
        <div class="col-sm-4">
          <div class="card float-left">
            <div class="card-body text-center">
              <h4>There are <a href="dashboard-users.php"><b><?php echo $_SESSION["user_count"] ?></b></a> total users!</h4>
            </div>
          </div>
        </div>
        
        <div class="col-sm-4">
          <div class="card">
            <div class="card-body text-center">
              <h4>There are <a href="dashboard-messages.php"><b><?php echo $_SESSION["msg_total"] ?></b></a> total messages!</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
