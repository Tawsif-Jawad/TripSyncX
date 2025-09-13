<?php
$errors = [];
$departure = '';
$destination = '';
$departure_date = '';
$return_date = '';

$valid_locations = [
    'Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 'Barisal', 
    'Rangpur', 'Mymensingh', "Cox's Bazar", 'Gazipur', 'Kishoreganj', 
    'Munshiganj', 'Narayanganj', 'Kuakata', 'Rangamati', 'Khagrachari', 
    'Sunamganj', 'Birganj'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departure = trim($_POST['departure'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $departure_date = trim($_POST['departure_date'] ?? '');
    $return_date = trim($_POST['return_date'] ?? '');
    
    $today = date('Y-m-d');
    
    if (empty($departure)) {
        $errors[] = "Please select a departure location.";
    } elseif (!in_array($departure, $valid_locations)) {
        $errors[] = "Invalid departure location selected.";
    }
}


    
    if (empty($destination)) {
        $errors[] = "Please select a destination location.";
    } elseif (!in_array($destination, $valid_locations)) {
        $errors[] = "Invalid destination location selected.";
    }
    
    if (!empty($departure) && !empty($destination) && $departure === $destination) {
        $errors[] = "Departure and destination locations cannot be the same.";
    }
    
    if (empty($departure_date)) {
        $errors[] = "Please select a departure date.";
    } else {
        $departure_timestamp = strtotime($departure_date);
        if ($departure_timestamp === false) {
            $errors[] = "Invalid departure date format.";
        } else {
            if ($departure_date < $today) {
                $errors[] = "Departure date cannot be in the past. Please select today's date or a future date.";
            }
        }
    }
    
    if (!empty($return_date)) {
        $return_timestamp = strtotime($return_date);
        if ($return_timestamp === false) {
            $errors[] = "Invalid return date format.";
        } else {
            if ($return_date < $today) {
                $errors[] = "Return date cannot be in the past. Please select today's date or a future date.";
            }
            
            if (!empty($departure_date) && $return_date < $departure_date) {
                $errors[] = "Return date cannot be before the departure date.";
            }
        }
    }
    
    if (empty($errors)) {
        session_start();
        $_SESSION['search_data'] = [
            'departure' => $departure,
            'destination' => $destination,
            'departure_date' => $departure_date,
            'return_date' => $return_date
        ];
        
        header('Location: TicketPreview.php');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>TripSyncX</title>
    <link rel="stylesheet" href="../CSS/HomePage.css" />
    <style>
 .error-messages {
    color: rgb(234, 245, 239);
    padding: 0;
    margin: 10px 0;
    font-size: 30px;
    background-color: rgb(160, 175, 168);
    border-radius: 5px;
}
.error-messages li {
            margin: 5px 0;
    }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <script src="../JS/HomePage.js"></script>

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

    <div class="banner-section">
        <img class="banner" src="../IMAGE/Banner-1.jpg" alt="Banner Image" />
    </div>

    <div class="welcome-section">
        <h1>Welcome to TripSyncX</h1>
        <p>Your Ultimate Travel Companion(আপনার চূড়ান্ত ভ্রমণ সঙ্গী)</p>
    </div>

    

    <div class="search-form" id="search-form">
        <?php if (!empty($errors)): ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="ticket-search">
                <div class="search-labels">
                    <label for="departure">Departure Location:</label>
                    <label for="destination">Destination Location:</label>
                    <label for="departure_date">Departure Date:</label>
                    <label for="return_date">Return Date:</label>
                </div>
                <div class="search-inputs">
                    <select id="departure" name="departure" required>
                        <option value="">From</option>
                        <?php foreach ($valid_locations as $location): ?>
                            <option value="<?php echo htmlspecialchars($location); ?>" 
                                    <?php echo ($departure === $location) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($location); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select id="destination" name="destination" required>
                        <option value="">To</option>
                        <?php foreach ($valid_locations as $location): ?>
                            <option value="<?php echo htmlspecialchars($location); ?>" 
                                    <?php echo ($destination === $location) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($location); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="date" 
                           id="departure_date" 
                           name="departure_date" 
                           value="<?php echo htmlspecialchars($departure_date); ?>"
                           min="<?php echo date('Y-m-d'); ?>" 
                           required />
                    
                    <input type="date" 
                           id="return_date" 
                           name="return_date" 
                           value="<?php echo htmlspecialchars($return_date); ?>"
                           min="<?php echo date('Y-m-d'); ?>" />
                </div>
            </div>
            <input type="submit" id="search-btn1" value="Search" />
        </form>
    </div>

    <div id="about-us">
        <h2>About Us</h2>
        <p>
            Welcome to TripSyncX, your ultimate travel companion. We are dedicated
            to making your travel experience seamless and enjoyable.
        </p>
        <p>
            At TripSyncX, we understand the excitement and anticipation that
            comes with planning a trip. Whether you're a seasoned traveler or
            embarking on your first adventure, our platform is designed to cater
            to all your travel needs.
        </p>
        <p>
            Our mission is to provide you with a user-friendly and efficient way
            to search for and book transportation options. With TripSyncX, you
            can easily find the best routes, compare prices, and make informed
            decisions about your travel plans.
        </p>
        <p>
            Thank you for choosing TripSyncX as your travel partner. We look
            forward to helping you create unforgettable memories on your
            journeys.
        </p>
        <p>
            Contact us at <a class="support-link" href="mailto:support@tripsyncx.com">support@tripsyncx.com</a> for any inquiries or assistance.
        </p>
    </div>

</body>
</html><?php
if (!empty($departure) && !empty($destination) && $departure === $destination) {
    $errors[] = "Departure and destination locations cannot be the same.";
}