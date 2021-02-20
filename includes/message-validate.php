<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION["IS_LOGGED_IN"])) {
  header("Location: ../login.php?error=noaccess");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include_once "dbconn.php";
  include_once "user-validate.php";

  if (isset($_POST['update-prf-msg-btn'])) {
    $prf_message = $_POST["prf-intro-msg"];
    $char_nums = 500;

    if (empty($prf_message)) {
      header("Location: ../welcome.php?error=emptyMessageField");
    }

    elseif (strlen($prf_message) > $char_nums) {
      header("Location: ../welcome.php?error=messageExceededLimit");
    }
    else {
      updateProfileMsg($connectDB,$prf_message);
    }
  }

  if (isset($_POST['send-msg-btn'])) {
    $send_msg_user = $_POST["send-msg-user"];
    $send_msg_title = $_POST["send-msg-title"];
    $send_msg_body = $_POST["send-msg-body"];

    if (empty($send_msg_user)) {
      header("Location: ../send-message.php?error=emptyUserField");
    }

    elseif (getRecipientID($connectDB,$send_msg_user) === $_SESSION["uid"]) {
      header("Location: ../send-message.php?error=sameUserID");
    }

    elseif (isUsernameTaken($connectDB,$send_msg_user) === false) {
      header("Location: ../send-message.php?error=noUserFound");
    }

    elseif (strlen($send_msg_body) > 50000) {
      header("Location: ../send-message.php?error=messageExceededLimit");
    }

    else {
      sendMessage($connectDB,$send_msg_user,$send_msg_title,$send_msg_body);
    }
  }
  
  if (isset($_POST['delete-msg-btn'])) {
    $message_id = $_GET["id"];
    deleteMessage($connectDB,$message_id);
  }
}
else{
    header("Location: ../welcome.php?error=sqlerror");
  }

?>
