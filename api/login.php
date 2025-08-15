
<?php

require_once 'db.php';
require_once 'jwt.php';

// Body JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['password'])) {
    echo json_encode(['error' => 'Email and password required']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$query = "SELECT * FROM person WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['error' => 'Senha inválida']);
    exit;
}

if (!$user['email'] || !password_verify($password, $user['password'])) {
    echo json_encode(['error' => 'Usuário ou senha estão inválidos']);
    exit;
}

 // Gerar token
    $payload = [
        'id' => $user['id_person'],
        'email' => $user['email'],
        'id_typeperson' => $user['id_typeperson'],
        'exp' => time() + 360000// 1 hora
    ];
    $token = generateJWT($payload); // função no jwt.php
    echo json_encode(['token' => $token]);


