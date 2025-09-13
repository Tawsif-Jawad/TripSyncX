<!DOCTYPE html>
<html>
  <head>
    <title>Add Agent</title>
    <link rel="stylesheet" href="../CSS/agentManage.css" />
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
      <a href="manage.css" class="sidebar-link">Manage</a>
      <a href="../PHP/EmergencyAlert.php" class="sidebar-link"
        >Emergency Alert</a
      >
      <a href="#" class="sidebar-link">View Reports</a>
      <a href="#" class="sidebar-link">Revenue & Financial Tracking</a>
      <a href="#" class="sidebar-link">Discount</a>
      <div class="sidebar-section" style="margin-top: 330px">
        <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
      </div>
    </section>

    <div class="container">
      <h2>Add Agent</h2>

      <form method="post" action="">
        <input
          type="text"
          name="name"
          placeholder="Enter your name"
          value="<?php echo $name; ?>"
        />
        <span class="error"> <?php echo $nameErr; ?></span>
        <br />

        <input
          type="email"
          name="email"
          placeholder="Enter your email"
          value="<?php echo $email; ?>"
        />
        <span class="error"> <?php echo $emailErr; ?></span>
        <br />

        <input
          type="text"
          name="phone"
          placeholder="Enter your phone number"
          value="<?php echo $phone; ?>"
        />
        <span class="error"> <?php echo $phoneErr; ?></span>
        <br />

        <input
          type="password"
          name="password"
          placeholder="Enter your password"
          value=""
        />
        <span class="error"> <?php echo $passwordErr; ?></span>
        <br />

        <input type="submit" value="Add Agent" />
      </form>
    </div>
  </body>
</html>
