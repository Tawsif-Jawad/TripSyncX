<!DOCTYPE html>
<html>
<head>
  <title>Add Discount</title>
  <link rel="stylesheet" href="../CSS/Discount.css">
</head>
<body>

<?php
include "../PHP/config.php";

$successMsg = $errorMsg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $dname   = trim($_POST["dname"]);
  $damount = trim($_POST["damount"]);
  $ddate   = trim($_POST["ddate"]);
  $drange  = trim($_POST["drange"]);
  $code    = trim($_POST["code"]);

  if (!empty($dname) && !empty($damount) && !empty($ddate) && !empty($drange) && !empty($code)) {
    $sql = "INSERT INTO discount (name, amount, valid_date, date_range, code) 
        VALUES ('$dname', '$damount', '$ddate', '$drange', '$code')";
    if ($conn->query($sql) === TRUE) {
      $successMsg = "Discount created successfully! Code: <b>$code</b>";
    } else {
      $errorMsg = " Error: " . $conn->error;
    }
  } else {
    $errorMsg = " All fields are required.";
  }
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
    <a href="../PHP/Discount.php" class="sidebar-link active">Discount</a>
    <div class="sidebar-section" style="margin-top: 350px;">
      <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
    </div>
  </section>

  <div class="discount-bg">
    <div class="discount-container">
      <h1>Add Discount</h1>
      <p class="subtitle">Create a discount for customer</p>

 
      <?php if (!empty($successMsg)) { echo "<p style='color:green;'>$successMsg</p>"; } ?>
      <?php if (!empty($errorMsg)) { echo "<p style='color:red;'>$errorMsg</p>"; } ?>

      <div class="discount-flex">
        <form class="discount-form" method="POST" action="">
          <label for="dname"><b>Discount name</b></label>
          <input type="text" id="dname" name="dname" placeholder="Offer name" required>

          <label for="damount"><b>Discount amount</b></label>
          <div class="discount-row">
            <input type="number" id="damount" name="damount" placeholder="Give discount amount" required>
            <input type="date" id="ddate" name="ddate" required>
          </div>

          <label for="drange"><b>Valid date range</b></label>
          <input type="text" id="drange" name="drange" placeholder="Enter date range" required>

          <label for="code"><b>Generated Code</b></label>
          <input type="text" id="code" name="code" readonly style="background:#f1f1f1;">

          <button type="button" class="create-btn" onclick="generateCode()">Generate Code</button>
          <button type="submit" class="create-btn">Create Discount</button>
        </form>

        <div class="discount-image">
          <img src="../Image/image of a bus and a.png" alt="Discount" style="width:100%; border-radius:12px;">
        </div>
      </div>
    </div>
  </div>

<script src="../js/Discount.js"></script>

</body>
</html>
