<?php
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
  $sql = "SELECT * FROM users WHERE login_name = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s',$lgname);
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
  $sql = "SELECT * FROM users WHERE email_id = ?;";
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
    $sql = "SELECT * FROM users WHERE user_id = ?;";
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
    echo "<h2 style='text-align:center'>Thank you for Registering!</h2></br>";
    echo "<p style='text-align:center'>Click <a href='../login.php'>here</a> to go to the login page.</p>";
  }
  else {
    echo "<h2 style='text-align:center'>Sorry, cannot process your request at the moment, please try again later!</h2></br>";
    echo "<p style='text-align:center'>Click <a href='../index.php'>here</a> to return to the main page.</p></br>";
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

function loginUser($conn,$lgname,$pass) {
  $sql = "SELECT * FROM users WHERE login_name = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s',$lgname);
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
      $_SESSION["date"] = $row["date_created"];
      header("Location: ../welcome.php");
    }
    else {
      header("Location: ../login.php?error=incorrectPassword");
    }
  }
  else {
    header("Location: ../login.php?error=sqlerror");
  }
}

?>
