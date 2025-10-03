<?php
require __DIR__ . '/../vendor/autoload.php';

MercadoPago\SDK::setAccessToken(getenv('MERCADOPAGO_ACCESS_TOKEN'));

// Captura dados enviados pelo JS
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['title']) || !isset($input['price']) || !isset($input['quantity']) || !isset($input['order_number'])) {
    echo json_encode(["success" => false, "message" => "Dados inválidos"]);
    exit;
}

$title = $input['title'];
$price = $input['price'];
$quantity = $input['quantity'];
$order_number = $input['order_number'];

$preference = new MercadoPago\Preference();
$item = new MercadoPago\Item();

$item->title = $title;
$item->quantity = (int)$quantity;
$item->unit_price = (float)$price;
$item->currency_id = "BRL";

$preference->items = [$item];
$preference->external_reference = $order_number;
$preference->back_urls = [
    "success" => "https://misturadeluz.com/success.php",
    "failure" => "https://misturadeluz.com/failure.php",
    "pending" => "https://misturadeluz.com/pending.php"
];
$preference->auto_return = "approved";

$preference->save();

echo json_encode([
    "success" => true,
    "checkout_url" => $preference->init_point,
    "order_number" => $order_number
]);
