<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('pedido.php');
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    throw new RuntimeException('Token CSRF inválido.');
}

$productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$sizeId = isset($_POST['size_id']) ? (int) $_POST['size_id'] : 0;
$fillingId = isset($_POST['filling_id']) ? (int) $_POST['filling_id'] : 0;
$toppingId = isset($_POST['topping_id']) ? (int) $_POST['topping_id'] : 0;
$decorationId = isset($_POST['decoration_id']) ? (int) $_POST['decoration_id'] : 0;
$customerName = trim((string) ($_POST['customer_name'] ?? ''));
$customerPhone = trim((string) ($_POST['customer_phone'] ?? ''));
$customText = trim((string) ($_POST['custom_text'] ?? ''));
$deliveryDate = trim((string) ($_POST['delivery_date'] ?? ''));
$deliveryTime = trim((string) ($_POST['delivery_time'] ?? ''));
$observations = trim((string) ($_POST['observations'] ?? ''));

if ($productId <= 0 || $sizeId <= 0 || $fillingId <= 0 || $toppingId <= 0 || $decorationId <= 0) {
    redirect('pedido.php');
}

if ($customerName === '' || $customerPhone === '' || $deliveryDate === '' || $deliveryTime === '') {
    redirect('pedido.php');
}

if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $customerName)) {
    redirect('pedido.php');
}

if (!preg_match('/^[0-9+\s()-]{8,20}$/', $customerPhone)) {
    redirect('pedido.php');
}

$selectedDate = DateTimeImmutable::createFromFormat('Y-m-d', $deliveryDate);
if (!$selectedDate) {
    redirect('pedido.php');
}

$minDate = new DateTimeImmutable('+' . MIN_DAYS_ANTICIPATION . ' days');
if ($selectedDate < $minDate) {
    redirect('pedido.php');
}

$product = get_product_by_id($productId);
$size = get_option_by_id('sizes', $sizeId);
$filling = get_option_by_id('fillings', $fillingId);
$topping = get_option_by_id('toppings', $toppingId);
$decoration = get_option_by_id('decorations', $decorationId);

if (!$product || !$size || !$filling || !$topping || !$decoration) {
    redirect('pedido.php');
}

$estimatedPrice = calculate_estimated_price($productId, $sizeId, $fillingId, $toppingId, $decorationId);

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare('INSERT INTO orders (customer_name, customer_phone, product_id, size_id, filling_id, topping_id, decoration_id, custom_text, delivery_date, delivery_time, observations, estimated_price, status, created_at) VALUES (:customer_name, :customer_phone, :product_id, :size_id, :filling_id, :topping_id, :decoration_id, :custom_text, :delivery_date, :delivery_time, :observations, :estimated_price, :status, NOW())');
    $stmt->execute([
        ':customer_name' => $customerName,
        ':customer_phone' => $customerPhone,
        ':product_id' => $productId,
        ':size_id' => $sizeId,
        ':filling_id' => $fillingId,
        ':topping_id' => $toppingId,
        ':decoration_id' => $decorationId,
        ':custom_text' => $customText,
        ':delivery_date' => $deliveryDate,
        ':delivery_time' => $deliveryTime,
        ':observations' => $observations,
        ':estimated_price' => $estimatedPrice,
        ':status' => 'Nuevo',
    ]);

    $orderId = (int) $pdo->lastInsertId();
    $orderNumber = str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);

    $message = build_whatsapp_message([
        'product_name' => $product['name'],
        'size_name' => $size['name'],
        'filling_name' => $filling['name'],
        'topping_name' => $topping['name'],
        'decoration_name' => $decoration['name'],
        'custom_text' => $customText !== '' ? $customText : 'Sin texto',
        'delivery_date' => $deliveryDate,
        'delivery_time' => $deliveryTime,
        'customer_name' => $customerName,
        'customer_phone' => $customerPhone,
        'observations' => $observations !== '' ? $observations : 'Sin observaciones.',
        'estimated_price' => $estimatedPrice,
        'order_number' => $orderNumber,
    ]);

    $whatsAppLink = 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message);
    redirect('gracias.php?pedido=' . $orderId . '&wa=' . rawurlencode($whatsAppLink));
} catch (Throwable $e) {
    throw $e;
}
