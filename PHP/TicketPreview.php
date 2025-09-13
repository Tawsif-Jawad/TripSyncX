<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ticket Preview</title>
    <link rel="stylesheet" href="../CSS/TicketPreview.css" />
</head>
<body>
  <script src="../JS/TicketPreview.js"></script>

<?php
// Include shared DB config (provides $conn)
include __DIR__ . "/../PHP/config.php";
// Start session for search persistence
session_start();

// Get search criteria from session, POST, or URL parameters
$search_departure = '';
$search_destination = '';
$search_date = '';
$search_ac_type = '';

// Check if form was submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_departure = $_POST['departure'] ?? '';
    $search_destination = $_POST['destination'] ?? '';
    $search_date = $_POST['departure_date'] ?? '';
    $search_ac_type = $_POST['ac'] ?? '';
    
    // Update session with new search data
    $_SESSION['search_data'] = [
        'departure' => $search_departure,
        'destination' => $search_destination,
        'departure_date' => $search_date,
        'ac_type' => $search_ac_type
    ];
} elseif (isset($_SESSION['search_data'])) {
    $search_departure = $_SESSION['search_data']['departure'];
    $search_destination = $_SESSION['search_data']['destination'];
    $search_date = $_SESSION['search_data']['departure_date'];
    $search_ac_type = $_SESSION['search_data']['ac_type'] ?? '';
} else {
    // Fallback to GET parameters if session data not available
    $search_departure = $_GET['departure'] ?? '';
    $search_destination = $_GET['destination'] ?? '';
    $search_date = $_GET['departure_date'] ?? '';
    $search_ac_type = $_GET['ac'] ?? '';
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
        <button class="Profile" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/Dashboard.php'">
            Profile
        </button>
        <button class="Tickets Operation" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/HomePage.php#search-form'">
            Tickets Operation
        </button>
        <button class="Home" onclick="window.location.href='http://localhost/Web-Tech/TripSyncX/PHP/HomePage.php'">
            Home
        </button>
    </div>

    <div class="ticket-preview-header">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <table>
            <tr>
                <th>
                    <select id="departure" name="departure" required>
                        <option value="">From</option>
                        <option value="Dhaka" <?php echo ($search_departure == 'Dhaka') ? 'selected' : ''; ?>>Dhaka</option>
                        <option value="Chittagong" <?php echo ($search_departure == 'Chittagong') ? 'selected' : ''; ?>>Chittagong</option>
                        <option value="Khulna" <?php echo ($search_departure == 'Khulna') ? 'selected' : ''; ?>>Khulna</option>
                        <option value="Rajshahi" <?php echo ($search_departure == 'Rajshahi') ? 'selected' : ''; ?>>Rajshahi</option>
                        <option value="Sylhet" <?php echo ($search_departure == 'Sylhet') ? 'selected' : ''; ?>>Sylhet</option>
                        <option value="Barisal" <?php echo ($search_departure == 'Barisal') ? 'selected' : ''; ?>>Barisal</option>
                        <option value="Rangpur" <?php echo ($search_departure == 'Rangpur') ? 'selected' : ''; ?>>Rangpur</option>
                        <option value="Mymensingh" <?php echo ($search_departure == 'Mymensingh') ? 'selected' : ''; ?>>Mymensingh</option>
                        <option value="Cox's Bazar" <?php echo ($search_departure == "Cox's Bazar") ? 'selected' : ''; ?>>Cox's Bazar</option>
                        <option value="Gazipur" <?php echo ($search_departure == 'Gazipur') ? 'selected' : ''; ?>>Gazipur</option>
                        <option value="Kishoreganj" <?php echo ($search_departure == 'Kishoreganj') ? 'selected' : ''; ?>>Kishoreganj</option>
                        <option value="Munshiganj" <?php echo ($search_departure == 'Munshiganj') ? 'selected' : ''; ?>>Munshiganj</option>
                        <option value="Narayanganj" <?php echo ($search_departure == 'Narayanganj') ? 'selected' : ''; ?>>Narayanganj</option>
                        <option value="Kuakata" <?php echo ($search_departure == 'Kuakata') ? 'selected' : ''; ?>>Kuakata</option>
                        <option value="Rangamati" <?php echo ($search_departure == 'Rangamati') ? 'selected' : ''; ?>>Rangamati</option>
                        <option value="Khagrachari" <?php echo ($search_departure == 'Khagrachari') ? 'selected' : ''; ?>>Khagrachari</option>
                        <option value="Sunamganj" <?php echo ($search_departure == 'Sunamganj') ? 'selected' : ''; ?>>Sunamganj</option>
                        <option value="Birganj" <?php echo ($search_departure == 'Birganj') ? 'selected' : ''; ?>>Birganj</option>
                        </select>
                </th>
                <th>
            <select id="destination" name="destination" required>
              <option value="">To</option>
              <option value="Dhaka" <?php echo ($search_destination == 'Dhaka') ? 'selected' : ''; ?>>Dhaka</option>
              <option value="Chittagong" <?php echo ($search_destination == 'Chittagong') ? 'selected' : ''; ?>>Chittagong</option>
              <option value="Khulna" <?php echo ($search_destination == 'Khulna') ? 'selected' : ''; ?>>Khulna</option>
              <option value="Rajshahi" <?php echo ($search_destination == 'Rajshahi') ? 'selected' : ''; ?>>Rajshahi</option>
              <option value="Sylhet" <?php echo ($search_destination == 'Sylhet') ? 'selected' : ''; ?>>Sylhet</option>
              <option value="Barisal" <?php echo ($search_destination == 'Barisal') ? 'selected' : ''; ?>>Barisal</option>
              <option value="Rangpur" <?php echo ($search_destination == 'Rangpur') ? 'selected' : ''; ?>>Rangpur</option>
              <option value="Mymensingh" <?php echo ($search_destination == 'Mymensingh') ? 'selected' : ''; ?>>Mymensingh</option>
              <option value="Cox's Bazar" <?php echo ($search_destination == "Cox's Bazar") ? 'selected' : ''; ?>>Cox's Bazar</option>
              <option value="Gazipur" <?php echo ($search_destination == 'Gazipur') ? 'selected' : ''; ?>>Gazipur</option>
              <option value="Kishoreganj" <?php echo ($search_destination == 'Kishoreganj') ? 'selected' : ''; ?>>Kishoreganj</option>
              <option value="Munshiganj" <?php echo ($search_destination == 'Munshiganj') ? 'selected' : ''; ?>>Munshiganj</option>
              <option value="Narayanganj" <?php echo ($search_destination == 'Narayanganj') ? 'selected' : ''; ?>>Narayanganj</option>
              <option value="Kuakata" <?php echo ($search_destination == 'Kuakata') ? 'selected' : ''; ?>>Kuakata</option>
              <option value="Rangamati" <?php echo ($search_destination == 'Rangamati') ? 'selected' : ''; ?>>Rangamati</option>
              <option value="Khagrachari" <?php echo ($search_destination == 'Khagrachari') ? 'selected' : ''; ?>>Khagrachari</option>
              <option value="Sunamganj" <?php echo ($search_destination == 'Sunamganj') ? 'selected' : ''; ?>>Sunamganj</option>
              <option value="Birganj" <?php echo ($search_destination == 'Birganj') ? 'selected' : ''; ?>>Birganj</option>
            </select>
                </th>
                <th>
                    <label for="departure_date">Departure Date:</label>
                    <input type="date" id="departure_date" name="departure_date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($search_date); ?>" />
                </th>
                <th>
                    <label for="ac">AC:</label>
                    <input type="radio" id="ac" name="ac" value="AC" <?php echo ($search_ac_type == 'AC') ? 'checked' : ''; ?> />
                </th>
                <th>
                    <label for="non-ac">Non-AC:</label>
                    <input type="radio" id="non-ac" name="ac" value="Non-AC" <?php echo ($search_ac_type == 'Non-AC') ? 'checked' : ''; ?> />
                </th>
                <th>
                    <input type="submit" id="search-btn2" value="Search" />
                </th>
            </tr>
        </table>
        </form>
        </div>
    <div class="ticket-preview-container">
            <div class="ticket-preview-sort">
                <label for="filterOptions">Filter Option:</label><br>
                <button class="sort-btn" onclick="sortTickets()">Sort by Price</button>
                <button class="sort-btn" onclick="sortTicketsByTime()">Sort by Time</button>
            </div>
                <div class="ticket-preview-table">
        <table id="ticketsTable">
          <tr>
            <th>Time</th>
            <th>Date</th>
            <th>From</th>
            <th>To</th>
            <th>Bus Type</th>
            <th>Price</th>
          </tr>
          <?php
          // Build SQL query with filters based on search criteria
          $sql = "SELECT * FROM schedule WHERE 1=1";
          $params = [];
          $types = "";
          
          // Add departure filter if provided
          if (!empty($search_departure)) {
              $sql .= " AND `from` = ?";
              $params[] = $search_departure;
              $types .= "s";
          }
          
          // Add destination filter if provided  
          if (!empty($search_destination)) {
              $sql .= " AND `to` = ?";
              $params[] = $search_destination;
              $types .= "s";
          }
          
          // Add AC/Non-AC filter if provided
          if (!empty($search_ac_type)) {
              $sql .= " AND `type` = ?";
              $params[] = $search_ac_type;
              $types .= "s";
          }
          
          $sql .= " ORDER BY time ASC";
          
          // Display search criteria
          if (!empty($search_departure) || !empty($search_destination) || !empty($search_ac_type) || !empty($search_date)) {
              echo '<tr><td colspan="6" style="background-color: #e8f5e8; text-align: center; padding: 10px; font-weight: bold;">';
              echo 'Showing results for: ';
              if (!empty($search_departure)) echo 'From: ' . htmlspecialchars($search_departure) . ' ';
              if (!empty($search_destination)) echo 'To: ' . htmlspecialchars($search_destination) . ' ';
              if (!empty($search_date)) echo 'Date: ' . htmlspecialchars($search_date) . ' ';
              if (!empty($search_ac_type)) echo 'Type: ' . htmlspecialchars($search_ac_type);
              echo '</td></tr>';
          }
          
          // Execute query with parameters if any
          if (!empty($params)) {
              $stmt = $conn->prepare($sql);
              $stmt->bind_param($types, ...$params);
              $stmt->execute();
              $result = $stmt->get_result();
          } else {
              $result = $conn->query($sql);
          }
          
          if ($result->num_rows > 0) {
              $counter = 1;
              while($row = $result->fetch_assoc()) {
                  $displayDate = !empty($search_date) ? htmlspecialchars($search_date) : date('Y-m-d');
                  echo '<tr '
                      . 'class="ticket-row" '
                      . 'data-ticket-id="' . $counter . '" '
                      . 'data-time="' . htmlspecialchars($row['time']) . '" '
                      . 'data-date="' . $displayDate . '" '
                      . 'data-from="' . htmlspecialchars($row['from']) . '" '
                      . 'data-to="' . htmlspecialchars($row['to']) . '" '
                      . 'data-type="' . htmlspecialchars($row['type']) . '" '
                      . 'data-fare="' . htmlspecialchars($row['fare']) . '">';
                  echo '<td>' . htmlspecialchars($row['time']) . '</td>';
                  echo '<td>' . $displayDate . '</td>';
                  echo '<td>' . htmlspecialchars($row['from']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['to']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['type']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['fare']) . ' BDT</td>';
                  echo '</tr>';
                  $counter++;
              }
          } else {
              echo '<tr><td colspan="6" style="text-align: center; padding: 20px; color: #666;">No schedules available for the selected route</td></tr>';
          }
          
          // Close statement if it was used
          if (isset($stmt)) {
              $stmt->close();
          }
          
          // Close connection
          $conn->close();
          ?>
        </table>
        <div id="seatPreview"></div>
            </div>
    </div>
    <script src="TicketPreview.js"></script>
</body>
</html>