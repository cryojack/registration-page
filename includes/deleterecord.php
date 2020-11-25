<?php
if (isset($_POST['deleteRecord'])) {
  include "dbconn.php";

  $sql = "DELETE FROM contacts WHERE id = ?";

  if (mysqli_query($connectDB,$sql)) {
    header("Location: ../showrecords.php?action=done");
    exit();
  }
}
else {
  header("Location: ../index.php?error=noaccess");
  exit();
}

?>
