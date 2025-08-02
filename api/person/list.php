<?php
header('Content-Type: application/json');
require_once '../db.php';

try {
    $stmt = $pdo->query("SELECT * FROM Person");
    $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($persons);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}