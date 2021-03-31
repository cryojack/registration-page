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
  <title>Your profile</title>
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
        <div class="row">
          <div class="col-sm-3">
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
            <div class="row justify-content-center">
              <h4>Profile Photo</h4>
            </div>
          </div>
          <div class="col-sm-9">
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>User ID</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["uid"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>Username</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["lgname"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>First Name</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["fname"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>Last Name</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["lname"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>Email ID</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["email"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>Date joined</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["date_cr"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>Last updated</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["date_up"] ?></h5></div>
            </div>
            <div class="form-group row">
              <div class="col-sm-3"><h5><b>Last login</b></h5></div>
              <div class="col-sm-6"><h5><?php echo $_SESSION["date_lg"] ?></h5></div>
            </div>
            <div class="form-group row">
              <a href="update-profile.php" class="offset-2 btn btn-primary">Change profile details</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
