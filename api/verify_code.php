<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

session_start();
$data = json_decode(file_get_contents("php://input"), true);

if($data['code'] == $_SESSION['verify_code']){
    require './db.php'; // conexão PDO

    $client = $_SESSION['client_data'];
    $fullName = $client['firstName']." ".$client['lastName'];
    $email = $client['email'];
    $phone = $client['phone'];
    $id_schedule = $client['id_schedule'];

    // Inserir em person
    $stmt = $pdo->prepare("INSERT INTO person (full_name, email, password, id_typeperson, created_at) VALUES (?,?,?,?,NOW())");
    $stmt->execute([$fullName, $email, password_hash($client['phone'], PASSWORD_DEFAULT), 'client']);
    $id_person = $pdo->lastInsertId();

    // Inserir em person_details
    $stmt = $pdo->prepare("INSERT INTO person_details (id_person, phone) VALUES (?,?)");
    $stmt->execute([$id_person, $phone]);

    // Inserir em historic
    $stmt = $pdo->prepare("INSERT INTO historic (id_person, id_schedule, status_subscribe, created_at) VALUES (?,?,?,NOW())");
    $stmt->execute([$id_person, $id_schedule, 'pending_payment']);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
