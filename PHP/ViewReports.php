
<!DOCTYPE html>
<html>
<head>
  <title>Manage</title>
  <link rel="stylesheet" href="../CSS/ViewReports.css">
</head>
<body>
 <?php
session_start();
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $response = ['success' => false, 'message' => ''];
    
    try {
        $pnr = $_POST['pnr'] ?? '';
        
        if (empty($pnr)) {
            $response['message'] = 'Invalid PNR provided';
        } else {
            $stmt = $conn->prepare("DELETE FROM User_Profile_Ticket WHERE pnr = ?");
            if (!$stmt) {
                $response['message'] = 'Database prepare error: ' . $conn->error;
            } else {
                $stmt->bind_param('i', $pnr);
                
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = 'Booking cancelled successfully';
                    } else {
                        $response['message'] = 'Booking not found or already cancelled';
                    }
                } else {
                    $response['message'] = 'Database execution error: ' . $stmt->error;
                }
                $stmt->close();
            }
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
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
    <a href="#" class="sidebar-link">FeedBack</a>
    <a href="../PHP/Discount.php" class="sidebar-link">Discount</a>
    <div class="sidebar-section" style="margin-top:350px;">
      <a href="../PHP/Login.php" class="sidebar-link">Logout</a>
    </div>
  </section>

           <div class="ticket-preview-table">
        <table id="ticketsTable">
          <tr>
            <th>PNR</th>
            <th>Time</th>
            <th>Date</th>
            <th>Route</th>
            <th>Bus Type</th>
            <th>Price</th>
          </tr>
          <?php
          try {
              $sql = "SELECT * FROM User_Profile_Ticket ORDER BY pnr DESC";
              $result = $conn->query($sql);
              
              if ($result && $result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      $pnr = $row['pnr'] ?? '';
                      $time = $row['time'] ?? $row['Time'] ?? '';
                      $date = $row['date'] ?? $row['Date'] ?? '';
                      $from = $row['from'] ?? $row['From'] ?? '';
                      $to = $row['to'] ?? $row['To'] ?? '';
                      $bus_type = $row['bus_type'] ?? $row['Bus Type'] ?? '';
                      $price = $row['price'] ?? $row['Price'] ?? '';
                      $passenger_name = $row['name'] ?? $row['passenger_name'] ?? $row['Passenger Name'] ?? '';
                      
                      $route = $from . " to " . $to;
                      
                      echo '<tr class="ticket-row" data-ticket-id="' . htmlspecialchars($pnr) . '">';
                      echo '<td id="pnr-' . htmlspecialchars($pnr) . '">' . htmlspecialchars($pnr) . '</td>';
                      echo '<td id="time-' . htmlspecialchars($pnr) . '">' . htmlspecialchars($time) . '</td>';
                      echo '<td id="date-' . htmlspecialchars($pnr) . '">' . htmlspecialchars($date) . '</td>';
                      echo '<td id="route-' . htmlspecialchars($pnr) . '">' . htmlspecialchars($route) . '</td>';
                      echo '<td id="bus-type-' . htmlspecialchars($pnr) . '">' . htmlspecialchars($bus_type) . '</td>';
                      echo '<td id="price-' . htmlspecialchars($pnr) . '">' . htmlspecialchars($price) . ' BDT</td>';
                      
                      echo '</tr>';
                  }
              } else {
                  echo '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #666;">No bookings found</td></tr>';
              }
          } catch (Exception $e) {
              echo '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #c00;">Error loading bookings: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
          }
          
          $conn->close();
          ?>
        </table>
        
    </div>
  
</body>
</html><!DOCTYPE html>