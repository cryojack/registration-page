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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style media="screen">
  #msg-txtarea {
    resize: none;
    font-size: 20px;
  }
</style>
  <body class="bg-light">
    <?php include_once "includes/navbar.php"; ?>
    <div class="container">
      <div class="card-body p-0">
        <div class="form-group row">
          <div class="offset-5">
            <a href="send-message.php" class="btn btn-primary">Create new message</a>
          </div>
        </div>
        <div class="row justify-content-center">
          <?php
          include_once "includes/dbconn.php";
          include_once "includes/user-validate.php";
          displayMessages($connectDB,$_SESSION["uid"]);
          ?>
          <!--div class="form-group col-sm-9">
            <div class="card">
              <div class="card-body p-1" style="outline:solid black 1px">
                <h4><b>Echo Message sender</b></h4>
                <h4>Echo Message title</h4>
                <h5>Echo Message body</h5>
                <h6><i>Echo Message date received</i></h6>
              </div>
            </div>
          </div-->
          
        </div>
        </div>
      </div>
    </div>
  </body>
</html>
