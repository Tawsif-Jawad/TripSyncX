<?php
include "config.php";

echo "<h2>Create User_Profile_Ticket Table</h2>";

try {
    $create_table = "CREATE TABLE IF NOT EXISTS User_Profile_Ticket (
        pnr INT AUTO_INCREMENT PRIMARY KEY,
        Seat VARCHAR(255) NOT NULL,
        Time VARCHAR(50) NOT NULL,
        Date DATE NOT NULL,
        `From` VARCHAR(100) NOT NULL,
        `To` VARCHAR(100) NOT NULL,
        `Bus Type` VARCHAR(50) NOT NULL,
        Price DECIMAL(10,2) NOT NULL,
        `Passenger Name` VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL,
        `Mobile Number` VARCHAR(20) NOT NULL,
        Age VARCHAR(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table) === TRUE) {
        echo "<p style='color: green;'> User_Profile_Ticket table created successfully!</p>";
        
        $structure = $conn->query("DESCRIBE User_Profile_Ticket");
        echo "<h3>Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background-color: #f2f2f2;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $structure->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p style='color: red;'> Error creating table: " . $conn->error . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'> Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>

<div style="margin-top: 20px;">
    <a href="seatPreview.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Test Seat Booking
    </a>
    <a href="check_database.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">
        Check Database
    </a>
</div>