<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['myevent']) || empty($data['price'])) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO MyEvent (myevent, price) VALUES (:myevent, :price)");
    $stmt->execute([
        ':myevent' => $data['myevent'],
        ':price' => $data['price']
    ]);
    echo json_encode(['success' => true, 'message' => 'Event created successfully.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
