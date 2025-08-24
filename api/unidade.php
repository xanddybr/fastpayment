<?php
require_once 'db.php'; // conexão PDO

header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT id_units, units FROM units");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
