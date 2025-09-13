<?php
// Include database configuration
include 'config.php';

// Initialize variables for ticket data
$ticketData = [];
$totalPrice = 0;
$ticketCount = 0;
$errors = [];

// Fetch ticket data from user_profile_ticket table
try {
    // Get ticket ID from URL parameter or session (you may need to adjust this based on your flow)
    $ticketId = isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : null;
    
    if ($ticketId) {
        // Fetch specific ticket by PNR
        $stmt = $conn->prepare("SELECT * FROM User_Profile_Ticket WHERE pnr = ?");
        $stmt->bind_param("i", $ticketId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $ticketData = [$result->fetch_assoc()];
            $ticketCount = 1;
        }
    } else {
        // If no specific ticket ID, fetch recent unpaid tickets (you may want to add a payment_status column)
        $sql = "SELECT * FROM User_Profile_Ticket ORDER BY created_at DESC LIMIT 5";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ticketData[] = $row;
            }
            $ticketCount = count($ticketData);
        }
    }
    
    // Calculate total price
    foreach ($ticketData as $ticket) {
        $price = $ticket['Price'] ?? $ticket['price'] ?? 0;
        $totalPrice += (float)$price;
    }
    
} catch (Exception $e) {
    $errors[] = "Error fetching ticket data: " . $e->getMessage();
}

// --- Simple PHP Validation ---
$company = $tax = $address = $method = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["company"])) {
        $company = htmlspecialchars($_POST["company"]);
    }
    if (!empty($_POST["tax"])) {
        $tax = htmlspecialchars($_POST["tax"]);
    }
    if (!empty($_POST["address"])) {
        $address = htmlspecialchars($_POST["address"]);
    }

    if (empty($_POST["method"])) {
        $errors[] = "Please select a payment method.";
    } else {
        $method = $_POST["method"];
    }

    if (empty($errors)) {
        // Process payment successfully
        $finalAmount = $totalPrice * 1.08; // Including 8% VAT
        
        // Here you could update the ticket status in the database or create a payment record
        // For example: UPDATE User_Profile_Ticket SET payment_status = 'paid' WHERE pnr IN (...)
        
        echo "<div style='color:green; padding: 10px; background: #f0f8f0; border: 1px solid green; margin: 10px 0;'>";
        echo "<h3>✅ Payment Successful!</h3>";
        echo "<p><strong>Payment Method:</strong> $method</p>";
        echo "<p><strong>Amount Paid:</strong> " . number_format($finalAmount) . " BDT (including 8% VAT)</p>";
        
        if (!empty($ticketData)) {
            echo "<p><strong>Tickets Paid For:</strong></p>";
            echo "<ul>";
            foreach ($ticketData as $ticket) {
                $pnr = $ticket['pnr'] ?? 'N/A';
                $from = $ticket['From'] ?? $ticket['from'] ?? 'N/A';
                $to = $ticket['To'] ?? $ticket['to'] ?? 'N/A';
                $seat = $ticket['Seat'] ?? $ticket['seat'] ?? 'N/A';
                echo "<li>PNR: $pnr | Route: $from to $to | Seat: $seat</li>";
            }
            echo "</ul>";
        }
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Section</title>
    <link rel="stylesheet" href="../CSS/payment.css">
</head>
<body>
    <div class="navigationbar">
        <a href="HomePage.php">
            <img class="logo" src="../IMAGE/IMG_5766.PNG"/>
        </a>
        <div class="nav-buttons">
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
    </div>
<form method="POST" action="">
    <div class="container">
        <!-- Left Side -->
        <div class="box">
            <h2>Invoicing (Optional)</h2>
            <label> Name</label>
            <input type="text" name="company" placeholder=" name">

            <label> Phone Number</label>
            <input type="text" name="phone" placeholder=" phone number">

            <label>Email</label>
            <input type="email" name="email" placeholder="Email">

            <h2>Payment method</h2>
            <div class="methods">
                <label><input type="radio" name="method" value="Wallet"> My Wallet</label>
                <label><input type="radio" name="method" value="Card"> Visa/MasterCard/JCB</label>
                <label><input type="radio" name="method" value="Bank"> Bank Transfer</label>
            </div>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $e) {
                    echo "<p class='error'>$e</p>";
                }
            }
            ?>
            <button type="submit">Pay Now</button>
        </div>

        <!-- Right Side -->
        <div class="box">
            <h2>Your Order</h2>
            <?php if (!empty($ticketData)): ?>
                <p><b><?php echo $ticketCount; ?>x Ticket<?php echo $ticketCount > 1 ? 's' : ''; ?></b> – <?php echo number_format($totalPrice); ?> BDT</p>

                <div>
                    <?php foreach ($ticketData as $ticket): 
                        $seat = $ticket['Seat'] ?? $ticket['seat'] ?? 'N/A';
                    ?>
                        <span class="ticket-box" style="background:lightgreen;"><?php echo htmlspecialchars($seat); ?></span>
                    <?php endforeach; ?>
                </div>

                <h3>Ticket Details:</h3>
                <?php foreach ($ticketData as $index => $ticket): 
                    $from = $ticket['From'] ?? $ticket['from'] ?? 'N/A';
                    $to = $ticket['To'] ?? $ticket['to'] ?? 'N/A';
                    $date = $ticket['Date'] ?? $ticket['date'] ?? 'N/A';
                    $time = $ticket['Time'] ?? $ticket['time'] ?? 'N/A';
                    $busType = $ticket['Bus Type'] ?? $ticket['bus_type'] ?? 'N/A';
                    $price = $ticket['Price'] ?? $ticket['price'] ?? 0;
                ?>
                    <div style="margin-bottom: 10px; padding: 5px; border: 1px solid #ddd;">
                        <strong>Ticket <?php echo $index + 1; ?>:</strong><br>
                        Route: <?php echo htmlspecialchars($from . ' to ' . $to); ?><br>
                        Date: <?php echo htmlspecialchars($date); ?><br>
                        Time: <?php echo htmlspecialchars($time); ?><br>
                        Bus Type: <?php echo htmlspecialchars($busType); ?><br>
                        Price: <?php echo number_format($price); ?> BDT
                    </div>
                <?php endforeach; ?>

                <h3>Ticket Value: <?php echo number_format($totalPrice); ?> BDT</h3>
            <?php else: ?>
                <p><b>No tickets found</b></p>
                <p>Please go back and select your tickets first.</p>
            <?php endif; ?>
            
            <label>Promotion Code</label>
            <input type="text" placeholder="Enter code">
            <label>Member Code</label>
            <input type="text" placeholder="Enter code">

            <h2>Final Payable: <?php echo number_format($totalPrice * 1.08); ?> BDT</h2>
            <p>Including 8% VAT</p>
        </div>
    </div>
</form>
</body>
</html>
