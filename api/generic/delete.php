<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");

require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['table']) || !isset($data['id_field']) || !isset($data['id_value'])) {
    echo json_encode(["error" => "Missing required fields. Expecting 'table', 'id_field' and 'id_value'."]);
    exit;
}

$table = $data['table'];
$id_field = $data['id_field'];
$id_value = $data['id_value'];

try {
    $sql = "DELETE FROM `$table` WHERE `$id_field` = :id_value";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id_value", $id_value);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Deletado com sucesso!"]);

} catch (PDOException $e) {

    if ($e->getCode() == "23000") {
        echo json_encode([
            "success" => false,
            "error" => "Não é possível excluir, por que existem registros vinculados essa informação!" ]);
            return;
         }

    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
