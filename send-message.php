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
        <form action="includes/message-validate.php" method="post">
          <div class="row justify-content-center">
            <div class="col-sm-9" style="outline:solid black 1px">
              <div class="card-body">
                <div class="form-group row">
                  <label for="send-msg-user" class="col-sm-2 col-form-label" style="font-weight:bold">Recipient</label>
                  <input type="text" class="form-control col-sm-9" name="send-msg-user" placeholder="Username">
                </div>
                <?php
                if ($_GET["error"] === "emptyUserField") {
                  echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Please enter a recipient!</p>";
                }
                if ($_GET["error"] === "noUserFound") {
                  echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>User doesn't exist!</p>";
                }
                if ($_GET["error"] === "sameUserID") {
                  echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Cannot send message to yourself!</p>";
                }
                ?>
                <div class="form-group row">
                  <label for="send-msg-title" class="col-sm-2 col-form-label" style="font-weight:bold">Title</label>
                  <input type="text" class="form-control col-sm-9" name="send-msg-title" placeholder="Title">
                </div>
                <div class="form-group row">
                  <label for="send-msg-body" class="col-sm-2 col-form-label" style="font-weight:bold">Body</label>
                  <textarea id="msg-txtarea" class="form-control col-sm-9" name="send-msg-body" rows="5" cols="30"></textarea>
                </div>
                <?php
                if ($_GET["error"] === "messageExceededLimit") {
                  echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Exceeded message limit of 50000 characters!</p>";
                }
                if ($_GET["error"] === "msgSendSuccess") {
                  echo "<p class='form-group row justify-content-center text-success' style='font-style:italic'>Message sent successfully!</p>";
                }
                if ($_GET["error"] === "sqlerror") {
                  echo "<p class='form-group row justify-content-center text-danger' style='font-style:italic'>Something went wrong, try again later!</p>";
                }
                ?>
              </div>
              <div class="form-group row">
                <div class="offset-5">
                  <button type="submit" name="send-msg-btn" class="btn btn-primary">Send message!</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
