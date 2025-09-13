<?php
include "config.php";

$successMsg = $errorMsg = "";
$id = $from = $to = $time = $type = $seat = $fare = $check = "";

// Get schedule ID from URL
if (isset($_GET['id'])) {
    $schedule_id = $_GET['id'];

    // Fetch existing data
    $sql = "SELECT * FROM schedule WHERE id = '$schedule_id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $from = $row['from'];
        $to = $row['to'];
        $time = $row['time'];
        $type = $row['type'];
        $seat = $row['seat'];
        $fare = $row['fare'];
        $check = $row['check'];
    } else {
        $errorMsg = "Schedule not found.";
    }
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $from = $_POST["from"];
    $to = $_POST["to"];
    $time = $_POST["time"];
    $type = $_POST["type"];
    $seat = $_POST["seat"];
    $fare = $_POST["fare"];
    $check = $_POST["check"];

    $sql_update = "UPDATE schedule SET 
        `from` = '$from', 
        `to` = '$to', 
        `time` = '$time', 
        `type` = '$type', 
        `seat` = '$seat', 
        `fare` = '$fare', 
        `check` = '$check' 
        WHERE id = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        $successMsg = "Schedule updated successfully!";
    } else {
        $errorMsg = "Error updating schedule: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="../CSS/agentedit.css">
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
    <div class="sidebar-section" style="margin-top:580px;">
        <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
    </div>
</section>

<div class="container">
    <h2>Edit Schedule</h2>

    <?php
    if (!empty($successMsg)) {
        echo "<p class='success'>$successMsg</p>";
    }
    if (!empty($errorMsg)) {
        echo "<p class='error'>$errorMsg</p>";
    }
    ?>

    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="text" name="from" placeholder="From" value="<?php echo $from; ?>" required><br>
        <input type="text" name="to" placeholder="To" value="<?php echo $to; ?>" required><br>
        <input type="time" name="time" value="<?php echo substr($time, 0, 5); ?>" required><br>
        <input type="text" name="type" placeholder="Type" value="<?php echo $type; ?>" required><br>
        <input type="text" name="seat" placeholder="Seat" value="<?php echo $seat; ?>" required><br>
        <input type="text" name="fare" placeholder="Fare" value="<?php echo $fare; ?>" required><br>
        <input type="text" name="check" placeholder="Check" value="<?php echo $check; ?>" required><br>
        <input type="submit" value="Update Schedule">
    </form>
</div>
</body>
</html>
