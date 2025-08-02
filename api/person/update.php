<?php
header('Content-Type: application/json');
require_once '../db.php';

// Lê os dados JSON enviados pelo fetch
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_person'])) {
    echo json_encode(['error' => 'Missing id_person']);
    exit;
}


try {
    $sql = "UPDATE person SET full_name = :full_name, email = :email, password = :password, status = :status, type_person = :type_person, created_at = :created_at WHERE id_person = :id_person";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $data['full_name'],
        ':email' => $data['email'],
        ':password' => password_hash($data["password"], PASSWORD_DEFAULT),
        ':status' => $data['status'],
        ':type_person' => $data['type_person'],
        ':created_at' => $data['created_at'],
        ':id_person' => $data['id_person'],
    ]);

    echo json_encode(['message' => 'Person updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
