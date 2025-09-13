<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "tripsynx";
$socket = "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock";

try {
	$conn = new mysqli($host, $user, $pass, $dbname, 3306, $socket);
	$conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
	die("<strong>DB Connection Error:</strong> " . $e->getMessage());
}

if ($conn->connect_error) {
	die("Connection Fail " . $conn->connect_error);
}
?>