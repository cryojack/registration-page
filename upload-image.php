<?php
session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Your Inbox</title>
  <link rel="icon" href="assets/webicon.png"/>
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
        <form action="includes/user-img-validate.php" method="post" enctype="multipart/form-data">
          <div class="row justify-content-center">
            <div class="col-sm-9" style="outline:solid black 1px">
              <div class="card-body">
                <div class="form-group row">
                  <label for="upload-img-title" class="col-sm-2 col-form-label" style="font-weight:bold">Image Title</label>
                  <input type="text" class="form-control col-sm-9" name="upload-img-title" placeholder="Image Title">
                </div>
                <?php
                if (isset($_GET["error"])) {
                  if ($_GET["error"] === "noTitleGiven") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please enter a title!</p>";
                  }
                }
                ?>
                <div class="form-group row">
                  <label for="upload-img-desc" class="col-sm-2 col-form-label" style="font-weight:bold">Description</label>
                  <input type="text" class="form-control col-sm-9" name="upload-img-desc" placeholder="Description">
                </div>
                <div class="form-group row">
                  <label for="upload-img-file" class="col-sm-2 col-form-label" style="font-weight:bold">Upload image</label>
                  <input type="file" class="form-control-file col-sm-9" name="gallerypic">
                </div>
                <?php
                if (isset($_GET["error"])) {
                  if ($_GET["error"] === "noImagePresent") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please upload an image!</p>";
                  }
                  if ($_GET["error"] === "imgSizeLarge") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Image exceeds accepted limit!</p>";
                  }
                  if ($_GET["error"] === "imgFormatUnsupported") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Image format unsupported!</p>";
                  }
                  if ($_GET["error"] === "imgUploadError") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>File upload error!</p>";
                  }
                  if ($_GET["error"] === "sqlerror") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Something went wrong, try again later!</p>";
                  }
                  if ($_GET["error"] === "imgUploadFailed") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Unable to upload image!</p>";
                  }
                  if ($_GET["error"] === "imgMoveFailed") {
                    echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Image copy failed, try again later!</p>";
                  }
                  if ($_GET["error"] === "imgUploadSuccess") {
                    echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Image uploaded successfully!</p>";
                  }
                }
                ?>
              </div>
              <div class="form-group row">
                <div class="offset-5">
                  <button type="submit" name="upload-img-bttn" class="btn btn-primary">Upload Image!</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
