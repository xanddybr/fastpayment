<?php
// Database connection settings
$host = '127.0.0.1';
$dbname = 'u617177303_ojmEi';
$username = 'u617177303_6SGgU';
$password = 'Mistura#1';

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$table = htmlspecialchars($_POST['option']);
 
// Query wp_users table
$sql = "SELECT * FROM ". $table;
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($users);

// Close connection
$conn->close();

?>
