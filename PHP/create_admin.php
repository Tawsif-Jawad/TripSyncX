<?php
include "config.php";

echo "<h2>Create Default Admin User</h2>";

$adminName = "admin";
$adminPhone = "00000000000";
$adminPassword = "adminadmin";
$adminEmail = "admin@tripsynx.com";
$adminRole = "Admin";

try {
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE name = ? AND phone = ?");
    $checkStmt->bind_param("ss", $adminName, $adminPhone);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<p style='color: orange;'> Admin user already exists!</p>";
        $existingAdmin = $result->fetch_assoc();
        echo "<p>Existing Admin ID: " . $existingAdmin['id'] . "</p>";
    } else {
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
        
        $insertStmt = $conn->prepare("INSERT INTO users (role, name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssss", $adminRole, $adminName, $adminEmail, $adminPhone, $hashedPassword);
        
        if ($insertStmt->execute()) {
            $adminId = $conn->insert_id;
            echo "<p style='color: green;'>Admin user created successfully!</p>";
            echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h3>Admin Login Credentials:</h3>";
            echo "<p><strong>Username:</strong> admin</p>";
            echo "<p><strong>Phone Number:</strong> 00000000000</p>";
            echo "<p><strong>Password:</strong> adminadmin</p>";
            echo "<p><strong>Role:</strong> Admin</p>";
            echo "<p><strong>Database ID:</strong> $adminId</p>";
            echo "</div>";
            echo "<p style='color: blue;'>You can now login with these credentials!</p>";
        } else {
            echo "<p style='color: red;'>Failed to create admin user: " . $insertStmt->error . "</p>";
        }
        $insertStmt->close();
    }
    $checkStmt->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'> Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>

<div style="margin-top: 20px;">
    <a href="login.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Go to Login Page
    </a>
    <a href="check_database.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">
        Check Database
    </a>
</div>