<?php
include "config.php";

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM schedule WHERE id=$id");
    header("Location: agentdashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="../CSS/agentdashboard.css">
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
    <a href="agentdashboard.php" class="sidebar-link">Dashboard</a>
    <a href="agentschedule.php" class="sidebar-link">Schedule</a>
    
    <div class="sidebar-section" style="margin-top:590px;">
      <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
    </div>
  </section>

<div class="content">
    <h1>Agent Dashboard</h1>
    <p>Welcome to the Agent Dashboard</p>

</div>

<div class="table-content">
        <div class="User-section">
            <div class="User-header">
                <h3>User Details</h3>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Bus ID</th>
                        <th>From</th>
                        <th>to</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Seat</th>
                        <th>Fare</th>
                        <th>check</th>
                    </tr>
                </thead>

                <tbody>
                  <?php
                  $conn = mysqli_connect(hostname: "localhost",username: "root", password: "", database: "tripsynx");

                  $query = "SELECT * FROM schedule";
                  $query_run = mysqli_query(mysql: $conn, query: $query);

                  if(mysqli_num_rows(result: $query_run) > 0) {
                    foreach($query_run as $row) {
                      ?>
                      <tr>
          
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['from']; ?></td>
                        <td><?= $row['to']; ?></td>
                        <td><?= $row['time']; ?></td>
                        <td><?= $row['type']; ?></td>
                        <td><?= $row['seat']; ?></td>
                        <td><?= $row['fare']; ?></td>
                        <td><?= $row['check']; ?></td>
                        <td><a href="agentedit.php?id=<?= $row['id']; ?>" class="edit-btn">Edit  |</a>
                        <a href="agentdashboard.php?delete=<?= $row['id']; ?>" class="edit-btn" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
</td>
                      </tr>
                      <?php
                    }
                  } 
                  else 
                  {
                    ?>
                    <tr>
                      <td colspan="6">No Record Found</td>
                      <?php
                  }
                  ?>
                
            </table>
       </div>
</div>
</div>

  
</body>
</html>