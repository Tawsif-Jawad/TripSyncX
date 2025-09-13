<?php
session_start();
require_once __DIR__ . '/config.php';
$fragment = isset($_GET['fragment']) && $_GET['fragment'] === '1';

$schedule_time = $_GET['time'] ?? '';
$schedule_date = $_GET['date'] ?? '';
$schedule_from = $_GET['from'] ?? '';
$schedule_to = $_GET['to'] ?? '';
$schedule_type = $_GET['type'] ?? '';
$schedule_fare = $_GET['fare'] ?? '';
$schedule_Paasengername = $_GET['name'] ?? '';

if (!empty($schedule_time) && !empty($schedule_date) && !empty($schedule_from) && !empty($schedule_to) && !empty($schedule_type) && !empty($schedule_fare)) {
    $_SESSION['last_schedule'] = [
        'time' => $schedule_time,
        'date' => $schedule_date,
        'from' => $schedule_from,
        'to' => $schedule_to,
        'type' => $schedule_type,
        'fare' => $schedule_fare,
        'name' => $schedule_Paasengername
    ];
}

$success = $error = "";
$passenger_name = $email = $phone = $age = $selected_seats = "";
$nameErr = $emailErr = $phoneErr = $ageErr = $seatsErr = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue_booking'])) {
    
    $restoreFields = ['time','date','from','to','bus_type','price'];
    if (isset($_SESSION['last_schedule'])) {
        foreach ($restoreFields as $rf) {
            if (empty($_POST[$rf])) {
                if ($rf === 'bus_type' && isset($_SESSION['last_schedule']['type'])) {
                    $_POST['bus_type'] = $_SESSION['last_schedule']['type'];
                } elseif ($rf === 'price' && isset($_SESSION['last_schedule']['fare'])) {
                    $_POST['price'] = $_SESSION['last_schedule']['fare'];
                } elseif (isset($_SESSION['last_schedule'][$rf])) {
                    $_POST[$rf] = $_SESSION['last_schedule'][$rf];
                }
            }
        }
    }
    
    if (empty($_POST['passenger_name'])) {
        $altKeys = ['Name','Passenger Name','passengerName','customer_name'];
        foreach ($altKeys as $ak) {
            if (!empty($_POST[$ak])) {
                $_POST['passenger_name'] = $_POST[$ak];
                break;
            }
        }
    }

    if (empty($_POST["passenger_name"])) {
        $nameErr = "Passenger name is required";
    } else {
        $passenger_name = test_input($_POST["passenger_name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $passenger_name)) {
            $nameErr = "Only letters and spaces allowed in name";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
            $phoneErr = "Phone number must be 10 to 15 digits";
        }
    }

    if (empty($_POST["age"])) {
        $ageErr = "Age is required";
    } else {
        $age = test_input($_POST["age"]);
    }

    if (empty($_POST["selected_seats"])) {
        $seatsErr = "Please select at least one seat";
    } else {
        $selected_seats = trim($_POST["selected_seats"]);
    }

    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($ageErr) && empty($seatsErr)) {
        try {
            $time = $_POST['time'];
            $date = $_POST['date'];
            $from_location = $_POST['from'];
            $to_location = $_POST['to'];
            $bus_type = $_POST['bus_type'];
            $raw_price = $_POST['price'];
            
            if (!is_numeric($raw_price) || $raw_price <= 0) {
                $error = "Invalid fare amount";
            } else {
                $seat_list = array_filter(array_map('trim', explode(',', $selected_seats)));
                if (!$seat_list) {
                    $error = "No valid seats selected";
                } else {
                                $total_price = count($seat_list) * floatval($raw_price);

                    $tblCheck = $conn->query("SHOW TABLES LIKE 'User_Profile_Ticket'");
                    if (!$tblCheck || $tblCheck->num_rows === 0) {
                        $error = "Ticket table missing in database";
                    } else {
                        $columnsRes = $conn->query("SHOW COLUMNS FROM `User_Profile_Ticket`");
                        if (!$columnsRes) {
                            $error = "Cannot read table columns: ".$conn->error;
                        } else {
                            $cols = [];
                            while($c = $columnsRes->fetch_assoc()) {
                                $cols[] = $c['Field'];
                            }
                            $columnsRes->free();
                            
                            $hasSpaced = in_array('Passenger Name', $cols);
                            $hasSnake = in_array('passenger_name', $cols);
                            $hasName = in_array('name', $cols);
                            
                            if (!$hasSpaced && !$hasSnake && !$hasName) {
                                $error = "Passenger name column missing. Available columns: ".implode(', ', $cols);
                            } else {
                                if ($hasSpaced) {
                                    $seatCheckSql = "SELECT 1 FROM `User_Profile_Ticket` WHERE `Time`=? AND `Date`=? AND `From`=? AND `To`=? AND `Bus Type`=? AND (".
                                      implode(' OR ', array_map(fn($i)=>"FIND_IN_SET(?, `Seat`)", array_keys($seat_list))).") LIMIT 1";
                                } else {
                                    $seatCheckSql = "SELECT 1 FROM `User_Profile_Ticket` WHERE `time`=? AND `date`=? AND `from`=? AND `to`=? AND `bus_type`=? AND (".
                                      implode(' OR ', array_map(fn($i)=>"FIND_IN_SET(?, `seat`)", array_keys($seat_list))).") LIMIT 1";
                                }
                                
                                $seatChk = $conn->prepare($seatCheckSql);
                                if (!$seatChk) {
                                    $error = "Database prepare error: ".$conn->error;
                                } else {
                                    $types = str_repeat('s', 5 + count($seat_list));
                                    $bindVals = array_merge([$time,$date,$from_location,$to_location,$bus_type], $seat_list);
                                    $seatChk->bind_param($types, ...$bindVals);
                                    $seatChk->execute();
                                    $seatChk->store_result();
                                    
                                    if ($seatChk->num_rows > 0) {
                                        $error = "One or more selected seats are already booked for this schedule";
                                        $seatChk->close();
                                    } else {
                                        $seatChk->close();
                                        
                                        if ($hasSpaced) {
                                            $insert = "INSERT INTO `User_Profile_Ticket` (`Seat`,`Time`,`Date`,`From`,`To`,`Bus Type`,`Price`,`Passenger Name`,`Email`,`Mobile Number`,`Age`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                                        } elseif ($hasName) {
                                            $insert = "INSERT INTO `User_Profile_Ticket` (`seat`,`time`,`date`,`from`,`to`,`bus_type`,`price`,`name`,`email`,`mobile_number`,`age`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                                        } else {
                                            $insert = "INSERT INTO `User_Profile_Ticket` (`seat`,`time`,`date`,`from`,`to`,`bus_type`,`price`,`passenger_name`,`email`,`mobile_number`,`age`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                                        }
                                        
                                        $stmt = $conn->prepare($insert);
                                        if (!$stmt) {
                                            $error = "Database prepare failed: ".$conn->error;
                                        } else {
                                            $stmt->bind_param('ssssssdssss', $selected_seats, $time, $date, $from_location, $to_location, $bus_type, $total_price, $passenger_name, $email, $phone, $age);
                                            
                                            if ($stmt->execute()) {
                                                $booking_id = $stmt->insert_id;
                                                $stmt->close();
                                                $success = "Booking successful! Your booking ID is: $booking_id";
                                                $successMsg = "Registration successful!";
                                                
                                                echo "<script>alert('Booking successful! Booking ID: $booking_id'); window.location.href='payment.php?ticket_id=$booking_id';</script>";
                                                exit();
                                            } else {
                                                $error = "Database execution failed: ".$stmt->error;
                                                $stmt->close();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $error = "Booking error: ".$ex->getMessage();
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<?php if (!$fragment): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Seat Selection</title>
    <link rel="stylesheet" href="../CSS/seatPreview.css" />
    <script src="../JS/seatPreview.js"></script>
</head>
<body>
<?php endif; ?>
<link rel="stylesheet" href="../CSS/seatPreview.css" />
<script src="../JS/seatPreview.js"></script>
<div class="container">
        <div class="seat-map-section">
            <div class="seat-map-container">
                <div class="deck-label">Lower Deck</div>
                <div class="aircraft-front">Front</div>
                <div class="schedule-summary" style="background:#f5f9f7;border:1px solid #2b4244;padding:10px;margin-bottom:10px;border-radius:6px;font-size:14px;line-height:1.4;">
                    <strong>Selected Schedule:</strong><br>
                    Time: <?php echo htmlspecialchars($schedule_time); ?> | Date: <?php echo htmlspecialchars($schedule_date); ?><br>
                    From: <?php echo htmlspecialchars($schedule_from); ?> → To: <?php echo htmlspecialchars($schedule_to); ?><br>
                    Type: <?php echo htmlspecialchars($schedule_type); ?> | Fare (per seat): <span id="singleFareDisplay"><?php echo htmlspecialchars($schedule_fare); ?></span> BDT<br>
                    Seats Selected: <span id="seatCount">0</span> | Total: <span id="totalFare">0</span> BDT
                </div>
                
                <table class="seat-table">
                    <tr>
                        <td><div class="seat available">A1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat available">A2</div></td>
                        <td><div class="seat available">A3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat available">B1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat available">B2</div></td>
                        <td><div class="seat unavailable">B3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat unavailable">C1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat unavailable">C2</div></td>
                        <td><div class="seat unavailable">C3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat unavailable">D1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat unavailable">D2</div></td>
                        <td><div class="seat unavailable">D3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat unavailable">E1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat unavailable">E2</div></td>
                        <td><div class="seat unavailable">E3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat unavailable">F1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat unavailable">F2</div></td>
                        <td><div class="seat unavailable">F3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat unavailable">G1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat unavailable">G2</div></td>
                        <td><div class="seat unavailable">G3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat available">H1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat available">H2</div></td>
                        <td><div class="seat available">H3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat available">I1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat available">I2</div></td>
                        <td><div class="seat unavailable">I3</div></td>
                    </tr>
                    
                    <tr>
                         
                        <td><div class="seat available">J1</div></td>
                        <td class="passage"></td>
                        <td><div class="seat available">J2</div></td>
                        <td><div class="seat available">J3</div></td>
                    </tr>
                </table>
                
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-seat available">A1</div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat unavailable"></div>
                        <span>Unavailable</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat selected"></div>
                        <span>Selected</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="passenger-section">
            <div class="passenger-details">
                <h2>Passenger Details</h2>
                <p class="verification-note">(Mobile verification required for new passenger)</p>
                
                <form method="POST" action="seatPreview.php" id="bookingForm">
                    <?php if (!empty($error)): ?>
                        <div style='background:#fee;border:1px solid #c00;padding:8px;color:#900;font-family:monospace;margin-bottom:10px;'>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" id="selected_seats_input" name="selected_seats" value="<?php echo htmlspecialchars($selected_seats); ?>">
                    <input type="hidden" id="time_input" name="time" value="<?php echo htmlspecialchars($schedule_time); ?>">
                    <input type="hidden" id="date_input" name="date" value="<?php echo htmlspecialchars($schedule_date); ?>">
                    <input type="hidden" id="from_input" name="from" value="<?php echo htmlspecialchars($schedule_from); ?>">
                    <input type="hidden" id="to_input" name="to" value="<?php echo htmlspecialchars($schedule_to); ?>">
                    <input type="hidden" id="bus_type_input" name="bus_type" value="<?php echo htmlspecialchars($schedule_type); ?>">
                    <input type="hidden" id="price_input" name="price" value="<?php echo htmlspecialchars($schedule_fare); ?>">
                    
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-field">
                                <input type="text" name="passenger_name" placeholder="Name" value="<?php echo htmlspecialchars($passenger_name); ?>" required>
                                <span class="error" style="color:red;font-size:12px;"><?php echo $nameErr; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-field">
                                <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" required>
                                <span class="error" style="color:red;font-size:12px;"><?php echo $emailErr; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-field">
                                <input type="tel" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone); ?>" required>
                                <span class="error" style="color:red;font-size:12px;"><?php echo $phoneErr; ?></span>
                            </div>
                            <div class="form-field small">
                                <select name="age" required>
                                    <option value="">Age</option>
                                    <option value="18-25" <?php echo ($age == '18-25') ? 'selected' : ''; ?>>18-25</option>
                                    <option value="26-35" <?php echo ($age == '26-35') ? 'selected' : ''; ?>>26-35</option>
                                    <option value="36-45" <?php echo ($age == '36-45') ? 'selected' : ''; ?>>36-45</option>
                                    <option value="46+" <?php echo ($age == '46+') ? 'selected' : ''; ?>>46+</option>
                                </select>
                                <span class="error" style="color:red;font-size:12px;"><?php echo $ageErr; ?></span>
                            </div>
                        </div>
                        <span class="error" style="color:red;font-size:12px;"><?php echo $seatsErr; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-field">
                                <input type="text" name="additional_info" placeholder="Additional Information">
                            </div>
                        </div>
                    </div>
                
                    <div class="action-buttons">
                        <button type="button" class="btn btn-cancel" onclick="closeSeatPreview()">Cancel</button>
                        <button type="submit" name="continue_booking" class="btn btn-continue">Continue</button>
                    </div>
                </form>
                
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($ageErr) && empty($seatsErr) && !empty($successMsg)) {
                    echo "<p class='success' style='color:green;font-weight:bold;'>$successMsg</p>";
                }
                ?>
            </div>
        </div>
    </div>
<?php if (!$fragment): ?>
<script>
function populateTicketData(ticketData) {
    document.getElementById('time_input').value = ticketData.time || '';
    document.getElementById('date_input').value = ticketData.date || '';
    document.getElementById('from_input').value = ticketData.from || '';
    document.getElementById('to_input').value = ticketData.to || '';
    document.getElementById('bus_type_input').value = ticketData.bus_type || '';
    document.getElementById('price_input').value = ticketData.price || '';
}

function updateSelectedSeats() {
    const selectedSeats = document.querySelectorAll('.seat.selected');
    const seatNumbers = Array.from(selectedSeats).map(seat => seat.textContent);
    document.getElementById('selected_seats_input').value = seatNumbers.join(',');
    
    const singlePrice = parseFloat(document.getElementById('price_input').value) || 0;
    const totalPrice = seatNumbers.length * singlePrice;

    document.getElementById('seatCount').textContent = seatNumbers.length;
    document.getElementById('totalFare').textContent = totalPrice.toFixed(2);
}

function validateBooking() {
    const selectedSeats = document.getElementById('selected_seats_input').value;
    const passengerName = document.querySelector('input[name="passenger_name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const age = document.querySelector('select[name="age"]').value;
    
    if (!selectedSeats) {
        alert('Please select at least one seat.');
        return false;
    }
    
    if (!passengerName || !email || !phone || !age) {
        alert('Please fill in all required fields.');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }
    
    const phoneRegex = /^[0-9]{10,15}$/;
    if (!phoneRegex.test(phone.replace(/\D/g, ''))) {
        alert('Please enter a valid phone number (10-15 digits).');
        return false;
    }
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const seats = document.querySelectorAll('.seat.available');
    
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
            } else {
                this.classList.add('selected');
            }
            updateSelectedSeats();
        });
    });
    
   
    const fare = document.getElementById('price_input').value;
    if (fare) {
        document.getElementById('singleFareDisplay').textContent = fare;
    }
    updateSelectedSeats();
});

function closeSeatPreview() {
    if (confirm('Are you sure you want to cancel? All entered data will be lost.')) {
        window.history.back();
    }
}
</script>
<?php endif; ?>
<?php if (!$fragment): ?>
</body>
</html>
<?php endif; ?>
