<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_person'])) {
    echo json_encode(['error' => 'Missing id_person']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM person WHERE id_person = :id_person");
    $stmt->execute([':id_person' => $data['id_person']]);

    echo json_encode(['message' => 'Person deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
