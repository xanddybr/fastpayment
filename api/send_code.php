<?php
require __DIR__ . '/../vendor/autoload.php';
require  'config.php';

use SendGrid\Mail\Mail;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE); 

// Captura os dados do frontend
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['email']) || !isset($input['firstName']) || !isset($input['lastName']) || !isset($input['id_schedule'])) {
    echo json_encode(["success" => false, "message" => "Dados incompletos"]);
    exit;
}

$email = $input['email'];
$firstName = $input['firstName'];
$lastName = $input['lastName'];
$id_schedule = $input['id_schedule'];

// Gera código de 4 dígitos
$code = rand(1000, 9999);

// ⚡ Salvar esse código em sessão ou banco para validar depois
session_start();
$_SESSION['verify_code'] = $code;
$_SESSION['user_email'] = $email;

// Monta email
$mail = new Mail();
$mail->setFrom("seuemail@seudominio.com", "Mistura de Luz");
$mail->setSubject("Código de verificação - Mistura de Luz");
$mail->addTo($email, "$firstName $lastName");
$mail->addContent(
    "text/plain",
    "Olá $firstName, seu código de verificação é: $code\n\nEvento ID: $id_schedule"
);

// Envia via SendGrid
$sendgrid = new \SendGrid(SENDGRID_API_KEY, [
    'verify_ssl' => false  // disable SSL check (only for testing!)
]);


try {
    $response = $sendgrid->send($mail);
    echo json_encode(["success" => true, "message" => "Email enviado com sucesso"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erro ao enviar email: " . $e->getMessage()]);
}
