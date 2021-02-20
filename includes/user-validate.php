<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//All functions to validate the user and generate a unique 12 digit ID

function isFieldEmpty($lgname,$fname,$lname,$email,$pass,$re_pass) {
  $result;
  if(empty($lgname) || empty($fname) || empty($lname) || empty($email) || empty($pass) || empty($re_pass)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function isUsernameValid($lgname) {
  $result;
  $regex = "/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/";
  if(preg_match($regex, $lgname)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function isUsernameTaken($conn,$lgname) {
  $result;
  $sql = "SELECT * FROM users WHERE login_name = ? OR email_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$lgname,$lgname);
  if ($stmt->execute()) {
    $rows = $stmt->get_result();
    if ($rows->num_rows > 0) {
      $result = true;
    }
    else {
      $result = false;
    }
  }
  else {
    header("Location: ../index.php?error=sqlerror");
    exit();
  }
  return $result;
}

function isEmailValid($email) {
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function isEmailTaken($conn,$email) {
  $result;
  $sql = "SELECT email_id FROM users WHERE email_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s',$email);
  if ($stmt->execute()) {
    $rows = $stmt->get_result();
    if ($rows->num_rows > 0) {
      $result = true;
    }
    else {
      $result = false;
    }
  }
  else {
    header("Location: ../index.php?error=sqlerror");
    exit();
  }
  return $result;
}

function isPasswordValid($pass) {
  $result;
  $regex = '/^(?=.*[0-9])(?=.*[A-Z]).{8,40}$/';
  if (preg_match($regex, $pass)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function isPasswordMatch($pass,$re_pass) {
  $result;
  if ($pass === $re_pass) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function generateUid($conn) {
  $uid;
  do {
    $uid = mt_rand(100000000000,999999999999);
    $sql = "SELECT user_id FROM users WHERE user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$uid);
    if ($stmt->execute()) {
      $rows = $stmt->get_result();
    }
  } while ($rows->num_rows != 0);
  return $uid;
}

function createUser($conn,$uid,$lgname,$fname,$lname,$email,$password) {
  $sql = "INSERT INTO users (user_id,login_name,first_name,last_name,email_id,password) VALUES (?,?,?,?,?,?);";
  $password_hashed = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('isssss',$uid,$lgname,$fname,$lname,$email,$password_hashed);
  if ($stmt->execute()) {
    header("Location: ../index.php?error=registerSuccess");
  }
  else {
    header("Location: ../index.php?error=sqlerror");
  }
  $conn->close();
}

// Login functions

function isLoginEmpty($lgname,$pass) {
  $result;
  if(empty($lgname) || empty($pass)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function isLoginNameValid($lgname) {
  $result;
  $regex = "/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/";
  if(preg_match($regex, $lgname) || filter_var($lgname, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function formatDate($datetime) {
  if ($datetime === NULL) {
    return "Not logged in";
  } else {
    $tempD = strtotime($datetime);
    $formatDateTime = date("l, jS F, Y, H:i", $tempD);
    return $formatDateTime;
  }
}

function loginUser($conn,$lgname,$pass) {
  $sql = "SELECT * FROM users WHERE login_name = ? OR email_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$lgname,$lgname);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if (password_verify($pass, $row["password"])) {
      session_start();
      $_SESSION["IS_LOGGED_IN"] = true;
      $_SESSION["uid"] = $row["user_id"];
      $_SESSION["lgname"] = $row["login_name"];
      $_SESSION["fname"] = $row["first_name"];
      $_SESSION["lname"] = $row["last_name"];
      $_SESSION["email"] = $row["email_id"];
      $_SESSION["date_cr"] = formatDate($row["date_created"]);
      $_SESSION["date_up"] = formatDate($row["date_updated"]);
      $_SESSION["prf_pic"] = $row["profile_pic_path"];
      $_SESSION["prf_msg"] = $row["profile_message"];
      getMessageCount($conn,$_SESSION["uid"]);
      $sql = "UPDATE users SET last_login = NOW() WHERE user_id = ?;";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i',$_SESSION["uid"]);
      if ($stmt->execute()) {
        $sql = "SELECT last_login FROM users WHERE user_id = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i',$_SESSION["uid"]);
        if ($stmt->execute()) {
          $res = $stmt->get_result();
          $row = $res->fetch_assoc();
          $_SESSION["date_lg"] = formatDate($row["last_login"]);
        }
      }
      if ($_SESSION["lgname"] === "ADMIN") {
        $_SESSION["user_count"] = getTotalUserCount($conn);
        $_SESSION["msg_total"] = getTotalMsgCount($conn);
        header("Location: ../dashboard.php");
      } else {
        header("Location: ../welcome.php");
      }
    }
    else {
      header("Location: ../login.php?error=incorrectPassword");
    }
  }
}

// Update functions

function isUpdateDetailFieldEmpty($fname,$lname,$email) {
  $result;
  if (empty($fname) && empty($lname) && empty($email)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function isUpdatePasswordFieldEmpty($oldpass,$newpass,$newrepass) {
  $result;
  if (empty($oldpass) || empty($newpass) || empty($newrepass)) {
    $result = true;
  }
  else {
    $result = false;
  }
  return $result;
}

function oldPasswordMatch($conn,$oldpass) {
  $result;
  $sql = "SELECT password FROM users WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$_SESSION["uid"]);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if (password_verify($oldpass, $row["password"])) {
      $result = true;
    }
    else {
      $result = false;
    }
  }
  else {
    header("Location: ../update-profile.php?error=sqlerror");
  }
  return $result;
}

function updateDetails($conn,$fname,$lname,$email) {
  if (empty($fname)) {
    $fname = $_SESSION["fname"];
  }
  if (empty($lname)) {
    $lname = $_SESSION["lname"];
  }
  if (empty($email)) {
    $email = $_SESSION["email"];
  }

  $sql = "UPDATE users SET first_name = ?,last_name = ?,email_id = ?,date_updated = NOW() WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssi',$fname,$lname,$email,$_SESSION["uid"]);
  if ($stmt->execute()) {
    $sql = "SELECT * FROM users WHERE user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$_SESSION["uid"]);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      $row = $res->fetch_assoc();
      $_SESSION["fname"] = $row["first_name"];
      $_SESSION["lname"] = $row["last_name"];
      $_SESSION["email"] = $row["email_id"];
      $_SESSION["date_up"] = formatDate($row["date_updated"]);
    }
    header("Location: ../update-profile.php?error=updateDetailSuccess");
  } else {
    header("Location: ../update-profile.php?error=sqlerror");
  }
  $conn->close();
}

function updatePassword($conn,$newpass) {
  $newpass_hashed = password_hash($newpass, PASSWORD_DEFAULT);
  $sql = "UPDATE users SET password = ?,date_updated = NOW() WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si',$newpass_hashed,$_SESSION["uid"]);
  if ($stmt->execute()) {
    $sql = "SELECT date_updated FROM users WHERE user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$_SESSION["uid"]);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      $row = $res->fetch_assoc();
      $_SESSION["date_up"] = formatDate($row["date_updated"]);
    }
    header("Location: ../update-profile.php?error=passwordChanged");
  } else {
    header("Location: ../update-profile.php?error=sqlerror");
  }
  $conn->close();
}

// Upload image functions

function isUploadedImgBig($up_f_size) {
  $result;
  $maxsize = 5 * (1024 * 1024);
  if ($up_f_size > $maxsize) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function isImgTypeCorrect($up_f_name) {
  $result;
  $file_formats = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
  $file_ext = pathinfo($up_f_name, PATHINFO_EXTENSION);
  if (array_key_exists($file_ext, $file_formats)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function resizeImage($img_file) {
  $new_file = $img_file;
  $img_details = getimagesize($img_file);
  $orig_width = $img_details[0];
  $orig_height = $img_details[1];
  $img_type = $img_details[2];
  $new_width = 205;
  $new_height = 255;
  if ($img_type === IMAGETYPE_JPEG) {
    $temp_img = imagecreatetruecolor($new_width, $new_height);
    $image = imagecreatefromjpeg($img_file);
    imagecopyresampled($temp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height) ;
    imagejpeg($temp_img, $new_file, 100);
  }
  elseif ($img_type === IMAGETYPE_PNG) {
    $temp_img = imagecreatetruecolor($new_width, $new_height);
    $image = imagecreatefrompng($img_file);
    imagecopyresampled($temp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height) ;
    imagepng($temp_img, $new_file, 9);
  }
  else {
    header("Location: ../update-profile.php?error=imgResizeError");
  }
}

function uploadProfilePic($conn,$up_f_name,$up_f_tmpname) {
  $img_dir = "../data/images/profile-pics/";
  $file_ext = pathinfo($up_f_name, PATHINFO_EXTENSION);
  $new_file = $img_dir . $_SESSION["uid"] . "." . $file_ext;
  if (move_uploaded_file($up_f_tmpname, $new_file)) {
    resizeImage($new_file);
    $sql = "UPDATE users SET profile_pic_path = ? WHERE user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si',$new_file,$_SESSION["uid"]);
    if ($stmt->execute()) {
      $sql = "SELECT * FROM users WHERE user_id = ?;";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i',$_SESSION["uid"]);
      if ($stmt->execute()) {
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $_SESSION["prf_pic"] = $row["profile_pic_path"];
      }
    }
    header("Location: ../update-profile.php?error=imgUploadSuccess");
  } else {
    header("Location: ../update-profile.php?error=imgUploadError");
  }
}

function profileImgDelete($conn) {
  $sql = "UPDATE users SET profile_pic_path = NULL WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$_SESSION["uid"]);
  if ($stmt->execute()) {
    $sql = "SELECT * FROM users WHERE user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$_SESSION["uid"]);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      $row = $res->fetch_assoc();
      $_SESSION["prf_pic"] = $row["profile_pic_path"];
    }
    header("Location: ../update-profile.php?error=imgDeleteSuccess");
  } else {
    header("Location: ../update-profile.php?error=sqlerror");
  }
}

// Message validation functions

function updateProfileMsg($conn,$prf_msg) {
  $sql = "UPDATE users SET profile_message = ? WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si',$prf_msg,$_SESSION["uid"]);
  if ($stmt->execute()) {
    $sql = "SELECT * FROM users WHERE user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$_SESSION["uid"]);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      $row = $res->fetch_assoc();
      $_SESSION["prf_msg"] = $row["profile_message"];
    }
    header("Location: ../welcome.php?error=msgUpdateSuccess");
  } else {
    header("Location: ../welcome.php?error=sqlerror");
  }
}

function generateMsgUid($conn) {
  $msg_uid;
  do {
    $msg_uid = strtoupper(bin2hex(random_bytes(6)));
    $sql = "SELECT msg_id FROM messages WHERE msg_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$msg_uid);
    if ($stmt->execute()) {
      $rows = $stmt->get_result();
    }
  } while ($rows->num_rows != 0);
  return $msg_uid;
}

function getRecipientID($conn,$send_user) {
  $r_uid;
  $sql = "SELECT user_id FROM users WHERE login_name = ? OR email_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$send_user,$send_user);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $r_uid = $row["user_id"];
  } else {
    header("Location: ../send-message.php?error=sqlerror");
  }
  return $r_uid;
}

function getSenderLoginName($conn,$send_id) {
  $s_name;
  $sql = "SELECT login_name FROM users WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$send_id);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $s_name = $row["login_name"];
  } else {
    header("Location: ../inbox.php?error=sqlerror");
  }
  return $s_name;
}

function getSenderFullName($conn,$send_id) {
  $f_name;
  $sql = "SELECT first_name,last_name FROM users WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$send_id);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $f_name = $row["first_name"] . ' ' . $row["last_name"];
  } else {
    header("Location: ../inbox.php?error=sqlerror");
  }
  return $f_name;
}

function getMessageCount($conn,$uid) {
  $is_d = 'N';
  $sql = "SELECT COUNT(*) as msg_count FROM messages WHERE recipient_id = ? AND is_deleted = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('is',$uid,$is_d);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $_SESSION["msg_count"] = $row["msg_count"];
  }
}

function sendMessage($conn,$send_user,$send_title,$send_body) {
  $s_title;
  $s_body;
  if (empty($send_title)) {
    $s_title = "<< NO TITLE AVAILABLE >>";
  } else {
    $s_title = $send_title;
  }
  if (empty($send_body)) {
    $s_body = "<< NO BODY AVAILABLE >>";
  } else {
    $s_body = $send_body;
  }
  $msg_id = generateMsgUid($conn);
  $recipient_id = getRecipientID($conn,$send_user);
  $is_del = 'N';
  $sql = "INSERT INTO messages (msg_id,msg_title,msg_content,sender_id,recipient_id,is_deleted) VALUES (?,?,?,?,?,?);";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssiis',$msg_id,$s_title,$s_body,$_SESSION["uid"],$recipient_id,$is_del);
  if ($stmt->execute()) {
    header("Location: ../send-message.php?error=msgSendSuccess");
  } else {
    header("Location: ../send-message.php?error=sqlerror");
  }
}

function displayMessages($conn,$uid) {
  $is_d = 'N';
  $sql = "SELECT msg_id,sender_id,msg_title,msg_content,date_sent FROM messages WHERE recipient_id = ? AND is_deleted = ? ORDER BY date_sent DESC;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('is',$uid,$is_d);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
      echo '<div class="form-group col-sm-9">';
      echo '<div class="card">';
      echo '<div class="card-body p-1" style="outline:solid black 1px">';
      echo '<form action="includes/message-validate.php?id=' . $row['msg_id'] . '" method="post">';
      echo '<h4><b>' . getSenderFullName($conn,$row['sender_id']) . '</b> (' . getSenderLoginName($conn,$row['sender_id']) . ')</h4>';
      echo '<h4>' . $row['msg_title'] . '</h4>';
      echo '<h5>' . $row['msg_content'] . '</h5>';
      echo '<h6 class = "float-left"><i>Sent on - ' . formatDate($row['date_sent']) . '</i></h6>';
      echo '<button type="submit" name="delete-msg-btn" class="btn btn-secondary fa fa-trash float-right"/>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
}

function deleteMessage($conn,$msg_id) {
  $is_d = 'Y';
  $sql = "UPDATE messages SET is_deleted = ? WHERE msg_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$is_d,$msg_id);
  if ($stmt->execute()) {
    getMessageCount($conn,$_SESSION["uid"]);
    header("Location: ../inbox.php?error=messageDeleted");
  } else {
    header("Location: ../inbox.php?error=sqlerror");
  }
}

// Administrator functions

function getTotalUserCount($conn) {
  $user_count;
  $sql = "SELECT COUNT(*) AS user_count FROM users WHERE user_id > 000000000050;";
  $stmt = $conn->prepare($sql);
  //$stmt->bind_param();
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $user_count = $row["user_count"];
  }
  return $user_count;
}

function getTotalMsgCount($conn) {
  $msg_total;
  $sql = "SELECT COUNT(*) AS msg_total FROM messages;";
  $stmt = $conn->prepare($sql);
  //$stmt->bind_param();
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $msg_total = $row["msg_total"];
  }
  return $msg_total;
}

function displayAllMessages($conn) {
  $is_d = 'N';
  $sql = "SELECT msg_id,sender_id,msg_title,msg_content,date_sent FROM messages ORDER BY date_sent DESC;";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
      echo '<div class="form-group col-sm-9">';
      echo '<div class="card">';
      echo '<div class="card-body p-1" style="outline:solid black 1px">';
      echo '<form action="includes/message-validate.php?id=' . $row['msg_id'] . '" method="post">';
      echo '<h6 class = "float-right">Message ID - ' . $row['msg_id'] . '</h6>';
      echo '<h4><b>' . getSenderFullName($conn,$row['sender_id']) . '</b> (' . getSenderLoginName($conn,$row['sender_id']) . ')</h4>';
      echo '<h4>' . $row['msg_title'] . '</h4>';
      echo '<h5>' . $row['msg_content'] . '</h5>';
      echo '<h6 class = "float-left"><i>Sent on - ' . formatDate($row['date_sent']) . '</i></h6>';
      echo '<button type="submit" name="adm-delete-msg-btn" class="btn btn-secondary fa fa-trash float-right"/>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
}

function displayAllUsers($conn) {
  $sql = "SELECT user_id,login_name,first_name,last_name FROM users WHERE user_id > 000000000050 ORDER BY date_created DESC;";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
      echo '<div class="form-group col-sm-9">';
      echo '<div class="card">';
      echo '<div class="card-body p-1" style="outline:solid black 1px">';
      echo '<form action="includes/user-validate.php?id=' . $row['user_id'] . '" method="post">';
      echo '<h4><b>' . $row['first_name'] . ' ' . $row['last_name'] . '</b> (' . $row['login_name'] . ')</h4>';
      echo '<button type="submit" name="adm-view-usr-btn" class="btn btn-secondary fa fa-eye float-right"/>';
      echo '<button type="submit" name="adm-delete-usr-btn" class="btn btn-secondary fa fa-trash float-right"/>';
      echo '<button type="submit" name="adm-update-usr-btn" class="btn btn-secondary fa fa fa-pencil float-right"/>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
}
?>