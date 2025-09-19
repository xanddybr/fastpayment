<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');


session_start();
$data = json_decode(file_get_contents("php://input"), true);

$code = rand(100000, 999999);
$_SESSION['verify_code'] = $code;
$_SESSION['client_data'] = $data;

// Envio de email simples (use PHPMailer em produção)
$to = $data['email'];
$subject = "Código de verificação - FastPayment";
$message = "Seu código de verificação é: $code";
$headers = "From: no-reply@fastpayment.com";

mail($to, $subject, $message, $headers);

echo json_encode(["success" => true]);
