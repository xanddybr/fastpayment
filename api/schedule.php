<?php
require_once 'db.php'; 

header("Content-Type: application/json");

try {
    $sql = "SELECT s.id_schedule, s.date, s.time, e.myevent AS myevent, t.tpevent AS tpevent, e.price AS price, s.vacancies AS vacancies FROM schedule s JOIN myevent e ON e.id_myevent = s.id_myevent JOIN typeevent t ON t.id_tpEvent = s.id_tpEvent ORDER BY s.date, s.time;";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
