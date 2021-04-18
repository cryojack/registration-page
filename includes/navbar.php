<?php
session_start();
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-3">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand mr-3"><?php if (!isset($_SESSION["IS_LOGGED_IN"])) { echo "Sample Login Page"; } else if (isset($_SESSION["IS_LOGGED_IN"])) { echo "Hi " . "<b>" .$_SESSION["lgname"] . "</b>"; } ?></a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        <?php
        if (!isset($_SESSION["IS_LOGGED_IN"])) {
          echo "<div class='navbar-nav ml-auto'>";
          echo "<a href='index.php' class='nav-item nav-link'>Register</a>";
          echo "<a href='login.php' class='nav-item nav-link'>Login</a>";
          echo "</div>";
        }

        if (isset($_SESSION["IS_LOGGED_IN"]) && ($_SESSION["lgname"] !== "ADMIN")) {
          echo "<div class='navbar-nav ml-auto'>";
          echo "<a href='profile.php' class='nav-item nav-link'>Profile</a>";
          echo "<a href='inbox.php' class='nav-item nav-link'>Inbox</a>";
          echo "<a href='gallery.php' class='nav-item nav-link'>Gallery</a>";
          echo "<a href='includes/logout.php' class='nav-item nav-link'>Logout</a>";
          echo "</div>";
        }
        
        if (isset($_SESSION["IS_LOGGED_IN"]) && ($_SESSION["lgname"] === "ADMIN")) {
          echo "<div class='navbar-nav ml-auto'>";
          echo "<a href='dashboard.php' class='nav-item nav-link'>Dashboard</a>";
          echo "<a href='profile.php' class='nav-item nav-link'>Profile</a>";
          echo "<a href='inbox.php' class='nav-item nav-link'>Inbox</a>";
          echo "<a href='gallery.php' class='nav-item nav-link'>Gallery</a>";
          echo "<a href='includes/logout.php' class='nav-item nav-link'>Logout</a>";
          echo "</div>";
        }
        ?>
        </div>
    </div>
</nav>
