<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once '../db.php';

// Lê dados enviados no corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["table"]) || !isset($data["values"])) {
    echo json_encode(["error" => "Formato inválido"]);
    exit;
}

$table = preg_replace("/[^a-zA-Z0-9_]/", "", $data["table"]); // sanitização
$values = $data["values"];

try {
    $fields = implode(",", array_keys($values));
    $placeholders = implode(",", array_fill(0, count($values), "?"));
    $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($values));

    echo json_encode(["success" => true, "message" => "Registro adicionado com sucesso!"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error API: " . $e->getMessage()]);
}

