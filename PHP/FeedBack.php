<!DOCTYPE html>
<html lang="en">
<head>
    <title>FeedBack</title>
    <link rel="stylesheet" href="../CSS/FeedBack.css" />
</head>
<body>
  <script src="../JS/FeedBack.js"></script>
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
    <div class="feedback-container">
    <h1>FeedBack</h1>
    
         <form onsubmit="return validateFeedbackForm()">
           <div class="feedback-form">
              <div class="form-label">
                  <label for="name">Name:</label>
                  <label for="email">Email:</label>
                  <label for="messageforuser">To:</label>
                  <label for="message">Message:</label>
              </div>
              <div class="form-input">
                  <input type="text" id="name" name="name" required>
                  <input type="email" id="email" name="email" required>
                  <select id="messageforuser" name="messageforuser" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="Admin">Admin</option>
                    <option value="Agent">Agent</option>
                  </select>
                  <textarea id="message" name="message" rows="4" required></textarea>
              </div>
            </div>
            <div class="form-btn">
                <input type="submit" value="Submit">
            </div>
       </div>

    </div>
    </form>
    </div>
   
</body>
</html>

</body>
</html>

</html>