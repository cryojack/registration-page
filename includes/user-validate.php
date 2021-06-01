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

function createUser($conn,$conn2,$uid,$lgname,$fname,$lname,$email,$password) {
  $sql = "INSERT INTO users (user_id,login_name,first_name,last_name,email_id,password) VALUES (?,?,?,?,?,?);";
  $password_hashed = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('isssss',$uid,$lgname,$fname,$lname,$email,$password_hashed);
  if ($stmt->execute()) {
    if(createUserImgTable($conn,$conn2,$uid) === true) {
      header("Location: ../index.php?error=registerSuccess");
    }
  } else {
    header("Location: ../index.php?error=sqlerror");
  }
  $conn->close();
  $conn2->close();
}

// User image gallery functions

function createUserImgTable($conn,$conn2,$uid) {
  $sql = "CREATE TABLE IF NOT EXISTS `$uid` (
    img_id VARCHAR(12) PRIMARY KEY NOT NULL,
    img_title TEXT NOT NULL,
    img_desc TEXT NOT NULL,
    img_path VARCHAR(255) NOT NULL,
    date_uploaded DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_deleted ENUM('Y','N') NOT NULL
  );";
  $stmt = $conn2->prepare($sql);
  if ($stmt->execute()) {
    if (createUserImgDir($conn,$uid) === true) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function createUserImgDir($conn,$uid) {
  $img_dir = "../data/images/general-pics/" . $uid;
  if (!file_exists($img_dir)) {
    if (mkdir($img_dir)) {
      $sql = "UPDATE users SET img_gallery_path = ? WHERE user_id = ?;";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('si',$img_dir,$uid);
      if ($stmt->execute()) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function generateImgId($conn,$uid) {
  $img_uid;
  do {
    $img_uid = strtoupper(bin2hex(random_bytes(6)));
    $sql = "SELECT img_id FROM `$uid` WHERE img_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$img_uid);
    if ($stmt->execute()) {
      $rows = $stmt->get_result();
    }
  } while ($rows->num_rows != 0);
  return $img_uid;
}

/*
This function will copy the uploaded file to the destination folder with the
same name as the user's ID and place an entry in a table with the same name as the 
user_id in the demo-page-gallery DB.
*/
function uploadGalleryPic($conn,$uid,$up_f_name,$up_f_tmpname,$img_title,$img_desc) {
  if (empty($img_desc)) {
    $img_desc = "NO DESCRIPTION GIVEN";
  }
  $is_del = 'N';
  $img_id = generateImgId($conn,$uid);
  $file_name = $img_id;
  $img_dir = $_SESSION["img_path"] . "/";
  $file_ext = pathinfo($up_f_name, PATHINFO_EXTENSION);
  $new_file = $img_dir . $file_name . "." . $file_ext;
  if (move_uploaded_file($up_f_tmpname, $new_file)) {
    resizeImage($new_file,2);
    $sql = "INSERT INTO `$uid` (img_id,img_title,img_desc,img_path,is_deleted) VALUES (?,?,?,?,?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss',$img_id,$img_title,$img_desc,$new_file,$is_del);
    if ($stmt->execute()) {
      header("Location: ../upload-image.php?error=imgUploadSuccess");
    } else {
      header("Location: ../upload-image.php?error=imgUploadFailed");
    }
  } else {
    header("Location: ../upload-image.php?error=imgMoveFailed");
  }
}

function showUserImages($conn,$uid) {
  $is_del = 'N';
  $sql = "SELECT * FROM `$uid` WHERE is_deleted = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s',$is_del);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
      echo '<div class="form-group col-md-4">';
      echo '<div class="card">';
      echo '<div class="card-body p-1 text-center">';
      echo '<form action="includes/user-img-validate.php?id=' . $row['img_id'] . '" method="post">';
      echo '<img src = "'. $row['img_path'] .'" alt="'.$row['img_title'].'">';
      echo '<div class="row justify-content-center">';
      echo '<button type="submit" name="usr-view-img-btn" class="btn btn-secondary fa fa-eye float-left"/>';
      echo '<button type="submit" name="usr-delete-img-btn" class="btn btn-secondary fa fa-trash float-left"/>';
      echo '</div>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '<div class="row justify-content-center">';
      echo '<h4>'. $row['img_title'] .'</h4>';
      echo '</div>';
      echo '<div class="row justify-content-center">';
      echo '<h5>'. $row['img_desc'] .'</h5>';
      echo '</div>';
      echo '</div>';
    }
  }
}

function deleteGalleryPic($conn,$uid,$img_id) {
  $is_d = 'Y';
  $sql = "UPDATE `$uid` SET is_deleted = ? WHERE img_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$is_d,$img_id);
  if ($stmt->execute()) {
    header("Location: ../gallery.php?error=imageDeleted");
  } else {
    header("Location: ../gallery.php?error=sqlerror");
  }
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
  $is_banned = 'N';
  $sql = "SELECT * FROM users WHERE login_name = ? OR email_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$lgname,$lgname);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row["is_banned"] === 'Y') {
      header("Location: ../login.php?error=userBanned");
    } else {
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
        $_SESSION["img_path"] = $row["img_gallery_path"];
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

function resizeImage($img_file,$rdir_const) {
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
    if ($rdir_const === 1) {
      header("Location: ../update-profile.php?error=imgResizeError");
    }
    elseif ($rdir_const === 2) {
      header("Location: ../upload-image.php?error=imgResizeError");
    }
  }
}

function uploadProfilePic($conn,$up_f_name,$up_f_tmpname) {
  $img_dir = "../data/images/profile-pics/";
  $file_ext = pathinfo($up_f_name, PATHINFO_EXTENSION);
  $new_file = $img_dir . $_SESSION["uid"] . "." . $file_ext;
  if (move_uploaded_file($up_f_tmpname, $new_file)) {
    resizeImage($new_file,1);
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

function getLoginName($conn,$send_id) {
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

function getFullName($conn,$send_id) {
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
      echo '<h4><b>' . getFullName($conn,$row['sender_id']) . '</b> (' . getLoginName($conn,$row['sender_id']) . ')</h4>';
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
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $msg_total = $row["msg_total"];
  }
  return $msg_total;
}

function displayAllMessages($conn) {
  $is_d = 'N';
  $sql = "SELECT * FROM messages ORDER BY date_sent DESC;";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
      echo '<div class="form-group col-sm-9">';
      echo '<div class="card">';
      echo '<div class="card-body p-1" style="outline:solid black 1px">';
      echo '<form action="includes/message-validate.php?id=' . $row['msg_id'] . '" method="post">';
      echo '<h6 class = "float-right">Message ID - ' . $row['msg_id'] . '</h6>';
      echo '<h5>Sender - <b>' . getFullName($conn,$row['sender_id']) . '</b> (' . getLoginName($conn,$row['sender_id']) . ')</h5>';
      echo '<h5>Recipient - <b>' . getFullName($conn,$row['recipient_id']) . '</b> (' . getLoginName($conn,$row['recipient_id']) . ')</h5>';
      echo '<h5>Title - ' . $row['msg_title'] . '</h5>';
      echo '<h5>Content - ' . $row['msg_content'] . '</h5>';
      echo '<h6 class = "float-left"><i>Sent on - ' . formatDate($row['date_sent']) . '</i></h6>';
      if ($row['is_deleted'] === 'Y') {
        echo '<h6 class="float-right"><i>Deleted</i></h6>';
      }
      elseif ($row['is_deleted'] === 'N') {
        echo '<button type="submit" name="adm-delete-msg-btn" class="btn btn-secondary fa fa-trash float-right"/>';
      }
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
      echo '<form action="includes/dashboard-validate.php?id=' . $row['user_id'] . '" method="post">';
      echo '<h4><b>' . $row['first_name'] . ' ' . $row['last_name'] . '</b> (' . $row['login_name'] . ')</h4>';
      echo '<button type="submit" name="adm-delete-usr-btn" class="btn btn-secondary fa fa-trash float-right"/>';
      echo '<button type="submit" name="adm-update-usr-btn" class="btn btn-secondary fa fa fa-pencil float-right"/>';
      echo '<button type="submit" name="adm-view-usr-btn" class="btn btn-secondary fa fa-eye float-right"/>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
}

function showUserDetails($conn,$uid) {
  $sql = "SELECT * FROM users WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$uid);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    echo '<form action="includes/dashboard-validate.php?id=' . $row['user_id'] . '" method="post">
    <div class="row">
    <div class="col-sm-3">
    <div class="card">
    <div class="card-body text-center">';
    if ($row['profile_pic_path'] === NULL) {
      echo "NO PHOTO UPLOADED!";
    }
    else {
      echo '<img src = "'. $row['profile_pic_path'] .'" alt="Your Profile Pic">';
    }
    echo '</div>
        </div>
        <div class="justify-content-center">
          <h4>Profile Photo</h4>
        </div>
        </div>
        <div class="col-sm-9">
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>User ID</b></h5></div>
          <div class="col-sm-6"><h5>' . $row['user_id'] . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>Username</b></h5></div>
          <div class="col-sm-6"><h5>' . $row['login_name'] . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>First Name</b></h5></div>
          <div class="col-sm-6"><h5>' . $row['first_name'] . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>Last Name</b></h5></div>
          <div class="col-sm-6"><h5>' . $row['last_name'] . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>Email ID</b></h5></div>
          <div class="col-sm-6"><h5>' . $row['email_id'] . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>Date joined</b></h5></div>
          <div class="col-sm-6"><h5>' . formatDate($row['date_created']) . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>Last updated</b></h5></div>
          <div class="col-sm-6"><h5>' . formatDate($row['date_updated']) . '</h5></div>
        </div>
        <div class="form-group row">
          <div class="col-sm-3"><h5><b>Last login</b></h5></div>
          <div class="col-sm-6"><h5>' . formatDate($row['last_login']) . '</h5></div>
        </div>
        <div class="form-group row">
        <div class="col-sm-3"><h5><b>Profile Message</b></h5></div>
          <div class="col-sm-6"><h5>';
          if ($row['profile_message'] === NULL) {
            echo 'No message added';
          } else {
            echo $row['profile_message'];
          }
        echo '</h5></div>
        </div>
        <div class="form-group row">';
        if ($row["is_banned"] === 'Y') {
          echo '<button type="submit" name="adm-enable-user-btn" class="offset-1 btn btn-primary"/>Enable user</button>';
        } 
        elseif ($row["is_banned"] === 'N') {
          echo '<button type="submit" name="adm-disable-user-btn" class="offset-1 btn btn-primary"/>Disable user</button>';
        }
        echo '<a href="dashboard-users.php" class="offset-1 btn btn-primary"/>Back to dashboard</a>
        </div>
        </div>
        </div>
        </form>';
  }
}

function updateUserDetails($conn,$uid) {
  $sql = "SELECT * FROM users WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$uid);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    echo '<form action="includes/dashboard-validate.php?id=' . $row['user_id'] . '" method="post">
    <div class="justify-content-center form-group row">
    <div class="col-sm-5"><h5><b>Update profile details here</b></h5></div>
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>First Name</b></h5></div>
    <input type="text" class="form-control col-sm-5" name="adm-fname-up" placeholder=' . $row["first_name"] . '>
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>Last Name</b></h5></div>
    <input type="text" class="form-control col-sm-5" name="adm-lname-up" placeholder=' . $row["last_name"] . '>
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>Email ID</b></h5></div>
    <input type="text" class="form-control col-sm-5" name="adm-email-up" placeholder=' . $row["email_id"] . '>
    </div>
    <div class="form-group offset-3">
    <button type="submit" name="adm-update-usr-details-btn" class="btn btn-primary">Update details</button>
    </div>
    </form>

    <!---Just a separation!! -->

    <form action="includes/dashboard-validate.php?id=' . $row["user_id"] . '" method="post">
    <div class="justify-content-center form-group row">
    <div class="col-sm-5"><h5><b>To update password, enter old password</b></h5></div>
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>Old password</b></h5></div>
    <input type="password" class="form-control col-sm-5" name="adm-old-password-up" placeholder="Old password">
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>New password</b></h5></div>
    <input type="password" class="form-control col-sm-5" name="adm-new-password-up" placeholder="New password">
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>Re-enter new password</b></h5></div>
    <input type="password" class="form-control col-sm-5" name="adm-new-repassword-up" placeholder="Repeat new password">
    </div>
    <div class="form-group offset-3">
    <button type="submit" name="adm-update-usr-pass-btn" class="btn btn-primary">Change password</button>
    </div>
    </form>

    <form action="includes/dashboard-validate.php?id=' . $row["user_id"] . '" method="post">
    <div class="justify-content-center form-group row">
    <div class="col-sm-5">
    <h5><b>Change profile pic here</b></h5>
    <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB.</p>
    </div>
    </div>
    <div class="form-group row">
    <div class="col-sm-3"><h5><b>Change profile pic</b></h5></div>
    <input type="file" class="col-sm-5 form-control-file" name="adm-profilepic">
    </div>
    <div class="row">
    <div class="form-group offset-3">
    <button type="submit" name="adm-update-usr-profilepic-btn" class="btn btn-primary">Change profile pic</button>
    </div>
    <div class="form-group offset-1">
    <button type="submit" name="adm-delete-usr-profilepic-btn" class="btn btn-primary">Delete profile pic</button>
    </div>
    </div>
    </form>';
  }
}

function admUpdateUsrDetails($conn,$uid,$fname_up,$lname_up,$email_up) {
  if (empty($fname_up)) {
    $fname_up = $_SESSION["fname"];
  }
  if (empty($lname_up)) {
    $lname_up = $_SESSION["lname"];
  }
  if (empty($email_up)) {
    $email_up = $_SESSION["email"];
  }

  $sql = "UPDATE users SET first_name = ?,last_name = ?,email_id = ?,date_updated = NOW() WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssi',$fname_up,$lname_up,$email_up,$_SESSION["uid"]);
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

function admUpdateUsrPassword(){}
function admUpdateUsrProfilePic(){}
function admDeleteUsrProfilePic(){}

function deleteUser($conn,$uid) {
  $sql = "DELETE FROM users WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i',$uid);
  if ($stmt->execute()) {
    header("Location: ../dashboard-users.php?error=userDeleteSuccess");
  } else {
    header("Location: ../dashboard-users.php?error=sqlerror");
  }
}

function disableUser($conn,$uid) {
  $is_banned = 'Y';
  $sql = "UPDATE users SET is_banned = ? WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si',$is_banned,$uid);
  if ($stmt->execute()) {
    //header("Location: ../show-user.php?id=" . $uid);
    header("Location: ../show-user.php?error=userBanSuccess");
  } else {
    header("Location: ../show-user.php?error=sqlerror");
  }
}

function enableUser($conn,$uid) {
  $is_banned = 'N';
  $sql = "UPDATE users SET is_banned = ? WHERE user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si',$is_banned,$uid);
  if ($stmt->execute()) {
    //header("Location: ../show-user.php?id=" . $uid);
    header("Location: ../show-user.php?error=userUnbanSuccess");
  } else {
    header("Location: ../show-user.php?error=sqlerror");
  }
}
?>