<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['table']) || !isset($data['values']) || !is_array($data['values'])) {
    echo json_encode(["error" => "Missing required fields. Expecting 'table' and 'values'."]);
    exit;
}

$table = $data['table'];
$values = $data['values'];

try {
    // Monta os campos e placeholders
    $fields = array_keys($values);
    $placeholders = array_map(fn($field) => ':' . $field, $fields);

    $sql = "INSERT INTO `$table` (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $placeholders) . ")";
    $stmt = $pdo->prepare($sql);

    foreach ($values as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Registro criado com sucesso!", "id" => $pdo->lastInsertId()]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
