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
  <title>Image Gallery</title>
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
      <div class="form-group row">
        <div class="offset-5">
          <a href="upload-image.php" class="btn btn-primary">Upload image</a>
        </div>
      </div>
      <div class="row justify-content-center">
        <?php
        include_once "includes/show-images.php";
        ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>