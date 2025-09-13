<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="../CSS/EmergencyAlert.css">
</head>
<body>
  <?php
  include "../PHP/config.php";
  $success= $error = "";

  $message = "";
  $messageErr = "";

 
  if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
    } else {
        $message = test_input($_POST["message"]);
    }

    if (empty($messageErr)) {
        $sql = "INSERT INTO emergency (message) VALUES ('$message')";
        if ($conn->query($sql) === TRUE) {
            $success = "Alert sent successfully";
            header("Location: EmergencyAlert.php?success=1");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
  }

  if (isset($_GET['delete'])) {
      $id = intval($_GET['delete']);
      $del_sql = "DELETE FROM emergency WHERE id=$id";
      mysqli_query($conn, $del_sql);
      header("Location: EmergencyAlert.php?deleted=1");
      exit();
  }

  function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      return $data;
  }
  ?>
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
  <section id="sidebar">
    <a href="../PHP/Dashboard.php" class="sidebar-link">Dashboard</a>
    <a href="../PHP/Manage.php" class="sidebar-link">Manage</a>
    <a href="../PHP/EmergencyAlert.php" class="sidebar-link">Emergency Alert</a>
    <a href="../PHP/ViewReports.php" class="sidebar-link">View Reports</a>
    <a href="#" class="sidebar-link">Revenue & Financial Tracking</a>
    <a href="../PHP/Discount.php" class="sidebar-link">Discount</a>
    <div class="sidebar-section" style="margin-top:350px;">
      <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
    </div>
  </section>
 
<div class="content">
    <h1>Emergency Alert!!</h1>
    <form method="POST" action="">
        <input type="text" name="message" class="alert-input" placeholder="Enter your emergency message" required>
        <button type="submit" class="alert-btn">Send Alert</button>
    </form>
</div>

<div class ="table-content">
    <div class="User-section">
        <div class="User-header">
            <h3>Alert History</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
             <?php
             $query = "SELECT * FROM emergency";
             $query_run = mysqli_query($conn, $query);
              if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $row) {
                  ?>
                  <tr>
                    <td><?= $row['message']; ?></td>
                    <td><a href="EmergencyAlert.php?delete=<?= $row['id']; ?>" class="edit-btn">Delete</a></td>
                  </tr>
                  <?php
                }
              } else {
                ?>
                <tr>
                  <td colspan="2">No Record Found</td>
                </tr>
                <?php
              }
              ?>
            </tbody>
        </table>
    </div>
</div>
  
</body>
</html>
