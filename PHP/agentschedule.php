<!DOCTYPE html>
<html>
<head>
  <title>Add Agent</title>
  <link rel="stylesheet" href="../CSS/agentschedule.css">
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
    
    <div class="sidebar-section" style="margin-top:570px;">
      <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
    </div>
  </section>

  <div class="container">
    <h2>Add Schedule</h2>

<?php
include "config.php";

$success = $error = "";
$successMsg = "";

$id = $from = $to = $time = $type = $seat = $fare = $check = "";
$idErr = $fromErr = $toErr = $timeErr = $seatErr = $fareErr = $checkErr = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = test_input($_POST["id"]);
    $from = test_input($_POST["from"]);
    $to = test_input($_POST["to"]);
    $time = test_input($_POST["time"]);
    $type = test_input($_POST["type"]);
    $seat = test_input($_POST["seat"]);
    $fare = test_input($_POST["fare"]);
    $check = test_input($_POST["check"]);

    if (empty($id)) $idErr = "Bus ID is required";
    if (empty($from)) $fromErr = "From location is required";
    if (empty($to)) $toErr = "To location is required";
    if (empty($time)) $timeErr = "Time is required";
    if (empty($seat)) $seatErr = "Seat is required";
    if (empty($fare)) $fareErr = "Fare is required";
    if (empty($check)) $checkErr = "Check status is required";

    if (empty($idErr) && empty($fromErr) && empty($toErr) && empty($timeErr) && empty($seatErr) && empty($fareErr) && empty($checkErr)) {
        $sql = "INSERT INTO schedule (`id`, `from`, `to`, `time`, `type`, `seat`, `fare`, `check`)
                VALUES ('$id', '$from', '$to', '$time', '$type', '$seat', '$fare', '$check')";
        
        if ($conn->query($sql) === TRUE) {
            $successMsg = "Schedule added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

    <form method="post" action="">
      <input type="text" name="id" placeholder="Enter Bus Id" value="<?php echo $id; ?>" required><br>
      <input type="text" name="from" placeholder="From" value="<?php echo $from; ?>" required><br>
      <input type="text" name="to" placeholder="To" value="<?php echo $to; ?>" required><br>
      <input type="time" name="time" value="<?php echo $time; ?>" required><br>
      <input type="text" name="type" placeholder="Type" value="<?php echo $type; ?>" required><br>
      <input type="text" name="seat" placeholder="Seat" value="<?php echo $seat; ?>" required><br>
      <input type="text" name="fare" placeholder="Fare" value="<?php echo $fare; ?>" required><br>
      <input type="text" name="check" placeholder="Check" value="<?php echo $check; ?>" required><br>
      <input type="submit" value="Add Schedule">
    </form>

<?php
  if (!empty($successMsg)) {
    echo "<p class='success'>$successMsg</p>";
  } elseif (!empty($error)) {
    echo "<p class='error'>$error</p>";
  }
?>

</body>
</html>
