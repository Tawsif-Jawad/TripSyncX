<?php
session_start();
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['continue_booking'])) {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

try {
    $required = ['time','date','from','to','bus_type','price','passenger_name','email','phone','age','selected_seats'];
    $missing = [];
    foreach ($required as $f) { if (empty($_POST[$f])) $missing[] = $f; }
    if ($missing) {
        throw new Exception('Missing fields: ' . implode(', ', $missing));
    }

    $selected_seats = $_POST['selected_seats'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $from_location = $_POST['from'];
    $to_location = $_POST['to'];
    $bus_type = $_POST['bus_type'];
    $raw_price = $_POST['price'];
    $passenger_name = $_POST['passenger_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];

    if (!is_numeric($raw_price) || $raw_price <= 0) {
        throw new Exception('Invalid base fare');
    }

    $seat_array = array_filter(explode(',', $selected_seats));
    $total_price = count($seat_array) * floatval($raw_price);

    $tblCheck = $conn->query("SHOW TABLES LIKE 'User_Profile_Ticket'");
    if (!$tblCheck || $tblCheck->num_rows === 0) {
        throw new Exception("Table User_Profile_Ticket missing");
    }

    $columnsRes = $conn->query("SHOW COLUMNS FROM `User_Profile_Ticket`");
    if (!$columnsRes) throw new Exception('Cannot read columns: ' . $conn->error);
    $cols = [];
    while ($c = $columnsRes->fetch_assoc()) { $cols[] = $c['Field']; }
    $columnsRes->free();

    // Determine naming convention
    $hasSpacePassenger = in_array('Passenger Name', $cols);
    $hasSnakePassenger = in_array('passenger_name', $cols);

    if (!$hasSpacePassenger && !$hasSnakePassenger) {
        throw new Exception('Passenger name column not found. Columns: ' . implode(', ', $cols));
    }

    if ($hasSpacePassenger) {
        $sql = "INSERT INTO `User_Profile_Ticket` (`Seat`,`Time`,`Date`,`From`,`To`,`Bus Type`,`Price`,`Passenger Name`,`Email`,`Mobile Number`,`Age`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    } else {
        $sql = "INSERT INTO `User_Profile_Ticket` (`seat`,`time`,`date`,`from`,`to`,`bus_type`,`price`,`passenger_name`,`email`,`mobile_number`,`age`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
    $stmt->bind_param('ssssssdssss', $selected_seats, $time, $date, $from_location, $to_location, $bus_type, $total_price, $passenger_name, $email, $phone, $age);

    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    $booking_id = $stmt->insert_id;
    $stmt->close();

    echo "<script>alert('Booking successful! ID: $booking_id'); window.location.href='payment.php?ticket_id=$booking_id';</script>";
    exit;

} catch (Exception $e) {
    echo "<div style='background:#fee;border:1px solid #c00;padding:10px;color:#900;font-family:monospace;'>Booking Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<p><a href='javascript:history.back()'>Go Back</a></p>";
}
