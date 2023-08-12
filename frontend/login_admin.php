<?php 
  session_start();

  if (isset($_SESSION['admin'])) {
    header("Location: ./admin.html");
  }

  if (isset($_GET['login_failed']) && $_GET['login_failed'] == true) {
    $failure_msg = "Failed to Login, Wrong ID or Password";
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />

    <title>Login - Admin</title>

    <link
      href="https://fonts.googleapis.com/css?family=Roboto"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../assets/css/login.css">
  </head>

  <body>
    <div class="container">
      <h2>Admin Login</h2>
      <form action="../backend/login.php" method="post">
        <div id="form-group">
          <label for="userid">Admin ID:</label>
          <input
            type="text"
            id="userid"
            name="userid"
            required
          />
        </div>
        <div id="form-group">
          <label for="password">Password:</label>
          <input
            type="password"
            id="password"
            name="password"
            required
          />
        </div>
        <input type="submit" value="Login" />
        <?php echo isset($failure_msg) ? $failure_msg : ""; ?>
      </form>
      <a href="./login_student.php">Student Login</a>
    </div>
    <div class="footer">Developed By - <b>U Sai Nath Rao</b></div>
  </body>
</html>
