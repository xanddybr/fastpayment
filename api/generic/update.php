<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");

require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['table']) || !isset($data['id_field']) || !isset($data['id_value']) || !isset($data['values'])) {
    echo json_encode(["error" => "Missing required fields. Expecting 'table', 'id_field', 'id_value', and 'values'."]);
    exit;
}

$table = $data['table'];
$id_field = $data['id_field'];
$id_value = $data['id_value'];
$values = $data['values'];

try {
    $fields = array_keys($values);
    $assignments = array_map(fn($field) => "$field = :$field", $fields);

    $sql = "UPDATE `$table` SET " . implode(", ", $assignments) . " WHERE `$id_field` = :id_value";
    $stmt = $pdo->prepare($sql);

    foreach ($values as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(":id_value", $id_value);

    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Registro atualizado com sucesso!"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
