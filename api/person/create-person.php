<?php
header('Content-Type: application/json');

require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os campos obrigatórios foram enviados
if (
    empty($data['full_name']) ||
    empty($data['email']) ||
    empty($data['password']) ||
    empty($data['status']) ||
    empty($data['id_typePerson'])
) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO Person (
            full_name,
            email,
            password,
            status,
            id_typePerson,
            created_at
        ) VALUES (
            :full_name,
            :email,
            :password,
            :status,
            :id_typePerson,
            NOW()
        )
    ");

    $stmt->execute([
        ':full_name'    => $data['full_name'],
        ':email'        => $data['email'],
        ':password'     => password_hash($data['password'], PASSWORD_DEFAULT),
        ':status'       => $data['status'],
        ':id_typePerson'  => $data['id_typePerson']
    ]);

    echo json_encode(['success' => true, 'message' => 'Person created successfully.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
