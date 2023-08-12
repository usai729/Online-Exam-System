<?php 
  session_start();

  if (isset($_SESSION['student'])) {
    header("Location: ./home.html");
  }

  if (isset($_GET['login_failed']) && $_GET['login_failed'] == true) {
    $failure_msg = "Failed to Login, Wrong ID or Password";
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />

    <link
      href="https://fonts.googleapis.com/css?family=Roboto"
      rel="stylesheet"
    />

    <title>Login - Student</title>

    <link rel="stylesheet" href="../assets/css/login.css">

    <script src="../assets/javascript/login.js"></script>
  </head>

  <body>
    <div class="container">
      <h2>Student Login</h2>
      <form action="../backend/login.php" method="post" id="form">
        <div id="form-group">
          <label for="userid">Student ID:</label>
          <input type="text" id="userid" name="userid" />
        </div>
        <div id="form-group">
          <label for="password">Password:</label>
          <input
            type="password"
            id="password"
            name="password"
          />
        </div>
        <input type="submit" value="Login" />
        <?php echo isset($failure_msg) ? $failure_msg : ""; ?>
      </form>
      <a href="./login_admin.php">Admin Login</a>
    </div>
    <div class="footer">Developed By - <b>U Sai Nath Rao</b></div>
  </body>
</html>
