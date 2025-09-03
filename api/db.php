
<?php

$host = "localhost";
$db = "u617177303_fastpay";
$user = "u617177303_root"; // ou outro usuário
$pass = "Mistura#1";     // ou a senha correta

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "DB Connection failed: " . $e->getMessage()]);
    exit;
}

?>
