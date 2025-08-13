<?php
require_once 'jwt_helper.php';

header("Content-Type: application/json");

$headers = getallheaders();
if (!isset($headers["Authorization"])) {
    echo json_encode(["success" => false, "error" => "Token não informado"]);
    exit;
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);
$key = '$2y$12$VJN04dskjhGGP5iijys0jObLjVV4zv/howw.z/gcZMfp7zeEcvA16';

try {
    $payload = JWT::decode($token, $key, ['HS256']);
    echo json_encode(["success" => true, "data" => $payload]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}



