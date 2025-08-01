<?php
header("Content-Type: application/json");
include_once("../db.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["full_name"], $data["email"], $data["password"], $data["type_person"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields."]);
    exit;
}

$full_name = $data["full_name"];
$email = $data["email"];
$password = password_hash($data["password"], PASSWORD_DEFAULT);
$type_person = $data["type_person"];
$status = "active";

try {
    $stmt = $pdo->prepare("INSERT INTO Person (full_name, email, password, type_person, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $password, $type_person, $status]);

    echo json_encode(["success" => true, "id_person" => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
