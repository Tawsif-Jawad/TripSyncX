<?php
          include "config.php";
          
          $error = "";
          $success = false;
          $userRole = "";
          $userName = "";
          $username = isset($_POST['username']) ? $_POST['username'] : "";
          $mobileNo = isset($_POST['mobileNo']) ? $_POST['mobileNo'] : "";
          $password = isset($_POST['password']) ? $_POST['password'] : "";
          
          // Check for session timeout messages
          if (isset($_GET['error'])) {
              if ($_GET['error'] === 'session_expired') {
                  $error = isset($_GET['message']) ? $_GET['message'] : "Your session has expired. Please login again.";
              } elseif ($_GET['error'] === 'not_logged_in') {
                  $error = "Please login to access this page.";
              }
          }
          
          // Check for logout success message
          if (isset($_GET['message'])) {
              $success_message = $_GET['message'];
          }
          
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
              if (!empty($username) && !empty($mobileNo) && !empty($password)) {
                  
                  // Check for default admin credentials first
                  if ($username === "admin" && $mobileNo === "00000000000" && $password === "adminadmin") {
                      $success = true;
                      $userRole = "Admin";
                      $userName = "admin";
                      
                      // Start session and store admin info
                      session_start();
                      $_SESSION['user_id'] = 0; // Default admin ID
                      $_SESSION['user_name'] = "admin";
                      $_SESSION['user_role'] = "Admin";
                      $_SESSION['user_email'] = "admin@tripsynx.com";
                      $_SESSION['login_time'] = time(); // Store login timestamp
                      $_SESSION['session_timeout'] = 300; // 5 minutes in seconds
                  } else {
                      // Query database for other users
                      try {
                          $stmt = $conn->prepare("SELECT id, role, name, email, phone, password FROM users WHERE name = ? AND phone = ?");
                          $stmt->bind_param("ss", $username, $mobileNo);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          
                          if ($result->num_rows === 1) {
                              $user = $result->fetch_assoc();
                              
                              // Verify password (check both hashed and plain text for backward compatibility)
                              if (password_verify($password, $user['password']) || $password === $user['password']) {
                                  $success = true;
                                  $userRole = $user['role'];
                                  $userName = $user['name'];
                                  
                                  // Start session and store user info
                                  session_start();
                                  $_SESSION['user_id'] = $user['id'];
                                  $_SESSION['user_name'] = $user['name'];
                                  $_SESSION['user_role'] = $user['role'];
                                  $_SESSION['user_email'] = $user['email'];
                                  $_SESSION['login_time'] = time(); // Store login timestamp
                                  $_SESSION['session_timeout'] = 300; // 5 minutes in seconds
                              } else {
                                  $error = "Invalid password!";
                              }
                          } else {
                              $error = "User not found!";
                          }
                          $stmt->close();
                      } catch (Exception $e) {
                          $error = "Database error: " . $e->getMessage();
                      }
                  }
              } else {
                  $error = "Please fill in all fields!";
              }
          }
        ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css" />
  </head>
  <body>
    <script src="../JS/login.js"></script>
     <div class="navigationbar">
        <a href="HomePage.php">
            <img class="logo" src="../IMAGE/IMG_5766.PNG"/>
        </a>
        <button class="FeedBack" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/FeedBack.php'">
            FeedBack
        </button>
        <button class="Contact Us" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/HomePage.php#about-us'">
            Contact Us
        </button>
        <button class="login" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/login.php'">
            Login
        </button>
        <button class="Profile" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/Login.php'">
            Profile
        </button>
        <button class="Tickets Operation" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/HomePage.php#search-form'">
            Tickets Operation
        </button>
        <button class="Home" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/HomePage.php'">
            Home
        </button>
    </div>
    
    <div class="main-content">
      <div class="login-form">
        <form method="post" onsubmit="return validateLoginForm()">
          <h1>Login</h1>
          <div class="input-container">
            <div class="input-labels">
              <label for="username">Username:</label>
              <label for="mobileNo">Mobile Number:</label>
              <label for="password">Password:</label>
            </div>
            <div class="input-fields">
              <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" />
              <input type="number" id="mobileNo" name="mobileNo" value="<?php echo htmlspecialchars($mobileNo); ?>" />
              <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" />
            </div>
            <br />
          </div>
        </br>
          <?php if ($error != "") { echo "<div style='color:rgb(255, 136, 0); text-align:center; width:100%; margin:10px 0;'>$error</div>"; } ?>
          <?php if (isset($success_message) && $success_message != "") { echo "<div style='color:green; text-align:center; width:100%; margin:10px 0;'>$success_message</div>"; } ?>
          <div class="login-btn">
            <input type="submit" value="Login" />
          </div>
          <div class="registration-section">
            <label for="registrationmessage">Don't have an account?</label>
            <button
              class="register"
              type="button"
              onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/Registration.php'"
            >Register</button>
          </div>
          <div class="forget-section">
            <button
              class="forget"
              type="button"
              onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/forgotpass.php'"
            >Forget Password?</button>
          </div>

          <?php if ($success): ?>
            <div class="success-message" style="text-align:center; margin-top:20px; color:green; font-size:12px;">
              Login successful! Welcome <?php echo htmlspecialchars($userName); ?> (<?php echo htmlspecialchars($userRole); ?>)
              <br>Redirecting...
            </div>
            <script>
              setTimeout(function() {
                <?php if ($userRole === 'Admin'): ?>
                  window.location.href = 'http://localhost/Web-Tech/TripSyncX/PHP/Dashboard.php';
                <?php elseif ($userRole === 'Agent'): ?>
                  window.location.href = 'http://localhost/Web-Tech/TripSyncX/PHP/agentdashboard.php';
                <?php else: ?>
                  window.location.href = 'http://localhost/Web-Tech/TripSyncX/PHP/UserDashboard.php';
                <?php endif; ?>
              }, 2000);
            </script>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </body>
</html>