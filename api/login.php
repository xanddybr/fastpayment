<?php
require '../db/conn.php';

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$senha = $data->senha;

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($senha, $user['senha'])) {
  echo json_encode(["success" => true, "nome" => $user['nome']]);
} else {
  echo json_encode(["success" => false]);
}
