<?php
header("Content-Type: application/json");
require_once '../db.php';

try {
    $stmt = $pdo->prepare("
        SELECT 
            id_schedule AS IdSch,
            date AS Date,
            time AS Hora,
            myevent AS Evento,
            tpEvent AS Tipo,
            price AS Preco,
            vacancies AS Vagas
        FROM 
            schedule s
        INNER JOIN 
            myevent m ON s.id_myevent = m.id_myevent
        INNER JOIN 
            typeevent t ON s.id_tpEvent = t.id_tpEvent
        ORDER BY s.date DESC
    ");
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "Database error: " . $e->getMessage()
    ]);
}
