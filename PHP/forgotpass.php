<?php
$email = $password = $confirm = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $errors[] = "Email is required.";
    } else {
        $email = htmlspecialchars($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    if (empty($_POST["password"])) {
        $errors[] = "Password is required.";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }
    }

    if (empty($_POST["confirm"])) {
        $errors[] = "Please confirm your password.";
    } else {
        $confirm = $_POST["confirm"];
        if ($password !== $confirm) {
            $errors[] = "Passwords do not match.";
        }
    }

    if (empty($errors)) {
        echo "<p style='color:green;'>Password reset successful </p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../CSS/forgotpass.css">
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
    <div class="box">
        <h2>Reset Password</h2>
        <p>See your growth and get consulting support</p>

        <form method="POST" action="">
            <label>Email *</label>
            <input type="email" name="email" placeholder="Enter your email" value="<?php echo $email; ?>">

            <label>Password *</label>
            <input type="password" name="password" placeholder="New password">

            <label>Confirm password *</label>
            <input type="password" name="confirm" placeholder="Confirm password">

            <?php
            if (!empty($errors)) {
                foreach ($errors as $e) {
                    echo "<p class='error'>$e</p>";
                }
            }
            ?>

            <button type="submit">Reset password</button>
        </form>
    </div>
</body>
</html>