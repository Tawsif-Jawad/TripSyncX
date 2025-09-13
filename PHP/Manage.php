
<!DOCTYPE html>
<html>
<head>
  <title>Manage</title>
  <link rel="stylesheet" href="../CSS/Manage.css">
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
  <div class="header">
  <h1>Manage</h1>
  </div>
        <div class="content">
            <a class="addAgent" href="../PHP/AddAgent.php">Add Agent</a>
            <a class="addCustomer" href="../PHP/AddCustomer.php">Add Customer</a>
        </div>
  
</body>
</html>