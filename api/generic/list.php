<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once '../db.php';

$table = $_GET['table'] ?? null;

if (!$table) {
    echo json_encode(["error" => "Missing table name. Use ?table=YourTable"]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM `$table`");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
