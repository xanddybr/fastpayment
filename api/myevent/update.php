<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['id_myevent']) || empty($data['myevent']) || empty($data['price'])) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE MyEvent SET myevent = :myevent, price = :price WHERE id_myevent = :id_myevent");
    $stmt->execute([
        ':myevent' => $data['myevent'],
        ':price' => $data['price'],
        ':id_myevent' => $data['id_myevent']
    ]);
    echo json_encode(['success' => true, 'message' => 'Event updated successfully.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
