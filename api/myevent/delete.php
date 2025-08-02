<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['id_myevent'])) {
    echo json_encode(['error' => 'Missing event ID.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM MyEvent WHERE id_myevent = :id_myevent");
    $stmt->execute([':id_myevent' => $data['id_myevent']]);
    echo json_encode(['success' => true, 'message' => 'Event deleted successfully.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
