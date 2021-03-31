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
<style media="screen">
  #prf-txtarea {
    resize: none;
    font-size: 20px;
  }
</style>
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
            <h4>You have <a href="inbox.php"><b><?php echo $_SESSION["msg_count"] ?></b></a> messages!</h4>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-sm-9" >
          <div class="card">
            <div class="card-body text-center">
              <?php
              if (empty($_SESSION["prf_msg"])) {
                echo "<h3>Put an intro message</h3>";
              }
              else {
                echo '<h4>'. $_SESSION["prf_msg"] .'</h4>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <form action="includes/message-validate.php" method="post">
        <div class="row justify-content-center">
          <div class="col-sm-9" >
            <div class="card-body">
              <h4><i><b>Write your introduction message here</b></i></h4> <h6>(Limit of 500 characters)<h6>
              <textarea id="prf-txtarea" class="form-control" name="prf-intro-msg" rows="5" cols="30"></textarea>
            </div>
            <?php
            if ($_GET["error"] === "emptyMessageField") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please write something!</p>";
            }
            if ($_GET["error"] === "messageExceededLimit") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Message exceeded character limit of 500!</p>";
            }
            if ($_GET["error"] === "msgUpdateSuccess") {
              echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Message updated successfully!</p>";
            }
            if ($_GET["error"] === "sqlerror") {
              echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Something went wrong, try again later!</p>";
            }
            ?>
            <div class="form-group row">
              <div class="offset-5">
                <button type="submit" name="update-prf-msg-btn" class="btn btn-primary">Update message!</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
