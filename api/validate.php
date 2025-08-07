<?php
require_once 'jwt_helper.php'; // ou ajuste o caminho conforme a pasta

$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["error" => "Token not provided"]);
    exit;
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

try {
    $decoded = JWT::decode($token, 'chave_secreta', ['HS256']);
    echo json_encode(["success" => true, "data" => $decoded]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token", "details" => $e->getMessage()]);
}
