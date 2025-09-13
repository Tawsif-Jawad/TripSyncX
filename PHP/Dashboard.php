<?php
include "../PHP/config.php";


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete']; 
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: Dashboard.php");
    exit();
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="../CSS/Dashboard.css">
</head>
<body>
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
    <h1>Dashboard</h1>
    <p>Welcome to the Admin Dashboard</p>
</div>

<div class="table-content">
    <div class="User-section">
        <div class="User-header">
            <h3>User Details</h3>
        </div>

        <table>
            <thead>
                <tr>
                  
                    <th>Role</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
              <?php
              $query = "SELECT * FROM users";
              $query_run = mysqli_query($conn, $query);

              if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $row) {
                  ?>
                  <tr>
                    
                    <td><?= $row['role']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['phone']; ?></td>
                    <td><a href="Dashboard.php?delete=<?= $row['id']; ?>" class="edit-btn">Delete</a></td>
                  </tr>
                  <?php
                }
              } else {
                ?>
                <tr>
                  <td colspan="4">No Record Found</td>
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
