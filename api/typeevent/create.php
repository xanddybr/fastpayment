<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once("../db.php");

$data = json_decode(file_get_contents("php://input"), true);

// Verifica se o campo tpEvent foi enviado
if (!isset($data["tpEvent"]) || empty(trim($data["tpEvent"]))) {
    echo json_encode(["error" => "Missing or invalid 'tpEvent'."]);
    exit;
}

$tpEvent = trim($data["tpEvent"]);

try {
    $stmt = $pdo->prepare("INSERT INTO TypeEvent (tpEvent) VALUES (:tpEvent)");
    $stmt->bindParam(":tpEvent", $tpEvent);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "TypeEvent created successfully."
        ]);
    } else {
        echo json_encode(["error" => "Failed to insert type event."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
