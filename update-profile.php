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
  <title>Change details</title>
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
      <div class="card-body p-0">

        <form action="includes/update-details.php" method="post">
          <div class="justify-content-center form-group row">
            <div class="col-sm-5"><h5><b>Update profile details here</b></h5></div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>First Name</b></h5></div>
            <input type="text" class="form-control col-sm-5" name="fname-up" placeholder="<?php echo $_SESSION["fname"] ?>">
          </div>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>Last Name</b></h5></div>
            <input type="text" class="form-control col-sm-5" name="lname-up" placeholder="<?php echo $_SESSION["lname"] ?>">
          </div>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>Email ID</b></h5></div>
            <input type="text" class="form-control col-sm-5" name="email-up" placeholder="<?php echo $_SESSION["email"] ?>">
          </div>
          <?php
          if ($_GET["error"] === "emptyDetailFields") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please enter a field!</p>";
          }
          if ($_GET["error"] === "invalidEmailid") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Invalid email!</p>";
          }
          if ($_GET["error"] === "emailidTaken") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Email already taken!</p>";
          }
          if ($_GET["error"] === "emailidSame") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Same email entered!</p>";
          }
          if ($_GET["error"] === "updateDetailSuccess") {
            echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Details updated successfully!</p>";
          }
          ?>
          <div class="form-group offset-3">
            <button type="submit" name="update-details-btn" class="btn btn-primary">Update details</button>
          </div>
        </form>

        <!---Just a separation!! -->

        <form action="includes/update-details.php" method="post">
          <div class="justify-content-center form-group row">
            <div class="col-sm-5"><h5><b>To update password, enter old password</b></h5></div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>Old password</b></h5></div>
            <input type="password" class="form-control col-sm-5" name="old-password-up" placeholder="Old password">
          </div>
          <?php
          if ($_GET["error"] === "incorrectOldPassword") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Incorrect Password!</p>";
          }
          ?>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>New password</b></h5></div>
            <input type="password" class="form-control col-sm-5" name="new-password-up" placeholder="New password">
          </div>
          <?php
          if ($_GET["error"] === "invalidPassword") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Invalid password!</p>";
          }
          ?>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>Re-enter new password</b></h5></div>
            <input type="password" class="form-control col-sm-5" name="new-repassword-up" placeholder="Repeat new password">
          </div>
          <?php
          if ($_GET["error"] === "emptyPasswordFields") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please enter all fields!</p>";
          }
          if ($_GET["error"] === "passwordNoMatch") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Passwords don't match!</p>";
          }
          if ($_GET["error"] === "passwordChanged") {
            echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Password changed successfully!</p>";
          }
          ?>
          <div class="form-group offset-3">
            <button type="submit" name="update-pass-btn" class="btn btn-primary">Change password</button>
          </div>
        </form>

        <form action="includes/update-details.php" method="post" enctype="multipart/form-data">
          <div class="justify-content-center form-group row">
            <div class="col-sm-5">
              <h5><b>Change profile pic here</b></h5>
              <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB.</p>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3"><h5><b>Change profile pic</b></h5></div>
            <input type="file" class="col-sm-5 form-control-file" name="profilepic">
          </div>
          <?php
          if ($_GET["error"] === "emptyPictureField") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please upload an image!</p>";
          }
          if ($_GET["error"] === "largePictureUploaded") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please upload a smaller image!</p>";
          }
          if ($_GET["error"] === "imgFormatNotSupported") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Image format not supported!</p>";
          }
          if ($_GET["error"] === "imgUploadError") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Cannot upload file!</p>";
          }
          if ($_GET["error"] === "imgResizeError") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Image resize unsuccessful!</p>";
          }
          if ($_GET["error"] === "imgUploadSuccess") {
            echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Image uploaded successfully!</p>";
          }
          if ($_GET["error"] === "noImgPresent") {
            echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>No image present!</p>";
          }
          if ($_GET["error"] === "imgDeleteSuccess") {
            echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Image deleted successfully!</p>";
          }
          ?>
          <div class="row">
            <div class="form-group offset-3">
              <button type="submit" name="update-profile-pic-btn" class="btn btn-primary">Change profile pic</button>
            </div>
            <div class="form-group offset-1">
              <button type="submit" name="delete-profile-pic-btn" class="btn btn-primary">Delete profile pic</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </body>
</html>
