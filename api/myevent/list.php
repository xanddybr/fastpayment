<?php
header('Content-Type: application/json');
require_once '../db.php';

try {
    $stmt = $pdo->query("SELECT * FROM MyEvent ORDER BY id_myevent DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
