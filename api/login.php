<?php

include_once 'db.php';
include_once 'jwt.php'; // Esse é um arquivo com as funções de geração de JWT

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['error' => 'Email and password are required']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Consulta no banco
$stmt = $pdo->prepare("SELECT * FROM person WHERE email = ? and password = ?");
$stmt->execute([$email,$password]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $password) {
    // Gerar token
    $payload = [
        'id' => $user['id_person'],
        'email' => $user['email'],
        'type_person' => $user['type_person'],
        'exp' => time() + (60 * 60) // 1 hora
    ];

    $token = generateJWT($payload); // função no jwt.php

    echo json_encode(['token' => $token]);
} else {
    echo json_encode(['error' => 'Invalid credentials']);
}
