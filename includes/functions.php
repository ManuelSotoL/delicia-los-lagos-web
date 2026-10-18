<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function sanitize_string(?string $value, int $maxLength = 255): string
{
    $value = trim((string) $value);
    $value = preg_replace('/\s+/', ' ', $value);
    $value = mb_substr($value, 0, $maxLength, 'UTF-8');
    return $value;
}

function sanitize_decimal(?string $value): string
{
    $value = preg_replace('/[^0-9,\.]/', '', (string) $value);
    $value = str_replace(',', '.', $value);
    $value = (float) $value;
    return number_format($value, 2, '.', '');
}

function sanitize_int(?string $value): int
{
    return max(0, (int) filter_var($value, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]));
}

function format_currency(float $amount): string
{
    return '$' . number_format((float) $amount, 0, ',', '.');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function get_setting(string $key, $default = null)
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch();
        if ($row) {
            return $row['setting_value'];
        }
    } catch (Throwable $e) {
        return $default;
    }

    return $default;
}

function save_setting(string $key, string $value): bool
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT id FROM settings WHERE setting_key = :key LIMIT 1');
        $stmt->execute([':key' => $key]);

        if ($stmt->fetch()) {
            $update = $pdo->prepare('UPDATE settings SET setting_value = :value WHERE setting_key = :key');
            $update->execute([':value' => $value, ':key' => $key]);
            return true;
        }

        $insert = $pdo->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)');
        $insert->execute([':key' => $key, ':value' => $value]);
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

function get_delivery_hours(): array
{
    $stored = get_setting('delivery_hours', json_encode(DELIVERY_HOURS));
    $decoded = json_decode((string) $stored, true);

    if (is_array($decoded) && !empty($decoded)) {
        return array_values($decoded);
    }

    return json_decode(DELIVERY_HOURS, true);
}

function get_min_days_anticipation(): int
{
    $value = (int) get_setting('min_days_anticipation', MIN_DAYS_ANTICIPATION);
    return max(1, $value);
}

function get_product_catalog(): array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->query('SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC');
        $products = $stmt->fetchAll();
        if (!empty($products)) {
            return $products;
        }
    } catch (Throwable $e) {
        // fallback
    }

    return [
        [
            'id' => 1,
            'name' => 'Torta Chocolate',
            'description' => 'Bizcocho húmedo con ganache y cobertura suave.',
            'image' => 'assets/img/torta-chocolate.svg',
            'base_price' => 20000,
        ],
        [
            'id' => 2,
            'name' => 'Torta Vainilla',
            'description' => 'Clásica torta de vainilla con relleno de frutilla.',
            'image' => 'assets/img/torta-vainilla.svg',
            'base_price' => 18000,
        ],
        [
            'id' => 3,
            'name' => 'Torta Red Velvet',
            'description' => 'Suave, elegante y con un toque premium.',
            'image' => 'assets/img/torta-redvelvet.svg',
            'base_price' => 24000,
        ],
    ];
}

function get_size_options(): array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->query('SELECT * FROM sizes WHERE active = 1 ORDER BY id ASC');
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    } catch (Throwable $e) {
        // fallback
    }

    return [
        ['id' => 1, 'name' => '10 personas', 'price_modifier' => 0],
        ['id' => 2, 'name' => '15 personas', 'price_modifier' => 5000],
        ['id' => 3, 'name' => '20 personas', 'price_modifier' => 10000],
        ['id' => 4, 'name' => '30 personas', 'price_modifier' => 15000],
        ['id' => 5, 'name' => '40 personas', 'price_modifier' => 20000],
        ['id' => 6, 'name' => '50 personas', 'price_modifier' => 25000],
    ];
}

function get_filling_options(): array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->query('SELECT * FROM fillings WHERE active = 1 ORDER BY id ASC');
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    } catch (Throwable $e) {
        // fallback
    }

    return [
        ['id' => 1, 'name' => 'Manjar', 'price_modifier' => 3000],
        ['id' => 2, 'name' => 'Crema', 'price_modifier' => 2000],
        ['id' => 3, 'name' => 'Frutilla', 'price_modifier' => 2500],
        ['id' => 4, 'name' => 'Mousse de chocolate', 'price_modifier' => 3500],
        ['id' => 5, 'name' => 'Crema de limón', 'price_modifier' => 2800],
        ['id' => 6, 'name' => 'Frambuesa', 'price_modifier' => 3000],
    ];
}

function get_topping_options(): array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->query('SELECT * FROM toppings WHERE active = 1 ORDER BY id ASC');
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    } catch (Throwable $e) {
        // fallback
    }

    return [
        ['id' => 1, 'name' => 'Chantilly', 'price_modifier' => 2000],
        ['id' => 2, 'name' => 'Ganache', 'price_modifier' => 2500],
        ['id' => 3, 'name' => 'Buttercream', 'price_modifier' => 2200],
        ['id' => 4, 'name' => 'Fondant', 'price_modifier' => 4000],
    ];
}

function get_decoration_options(): array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->query('SELECT * FROM decorations WHERE active = 1 ORDER BY id ASC');
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    } catch (Throwable $e) {
        // fallback
    }

    return [
        ['id' => 1, 'name' => 'Cumpleaños', 'price_modifier' => 4000],
        ['id' => 2, 'name' => 'Infantil', 'price_modifier' => 5000],
        ['id' => 3, 'name' => 'Bautizo', 'price_modifier' => 4500],
        ['id' => 4, 'name' => 'Matrimonio', 'price_modifier' => 7000],
        ['id' => 5, 'name' => 'Aniversario', 'price_modifier' => 5500],
        ['id' => 6, 'name' => 'Personalizada', 'price_modifier' => 8000],
    ];
}

function get_product_by_id(int $id): ?array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();
        if ($product) {
            return $product;
        }
    } catch (Throwable $e) {
        // fallback
    }

    foreach (get_product_catalog() as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }

    return null;
}

function get_option_by_id(string $table, int $id): ?array
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT * FROM ' . $table . ' WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

function calculate_estimated_price(int $productId, int $sizeId, int $fillingId, int $toppingId, int $decorationId): float
{
    $product = get_product_by_id($productId);
    $size = get_option_by_id('sizes', $sizeId);
    $filling = get_option_by_id('fillings', $fillingId);
    $topping = get_option_by_id('toppings', $toppingId);
    $decoration = get_option_by_id('decorations', $decorationId);

    $base = (float) ($product['base_price'] ?? 0);
    $sizeMod = (float) ($size['price_modifier'] ?? 0);
    $fillingMod = (float) ($filling['price_modifier'] ?? 0);
    $toppingMod = (float) ($topping['price_modifier'] ?? 0);
    $decorationMod = (float) ($decoration['price_modifier'] ?? 0);

    return $base + $sizeMod + $fillingMod + $toppingMod + $decorationMod;
}

function get_product_image(string $path): string
{
    if (!empty($path) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($path, '/'))) {
        return $path;
    }

    return 'assets/img/torta-default.svg';
}

function build_whatsapp_message(array $order): string
{
    $productName = $order['product_name'] ?? 'Torta';
    $sizeName = $order['size_name'] ?? '-';
    $fillingName = $order['filling_name'] ?? '-';
    $toppingName = $order['topping_name'] ?? '-';
    $decorationName = $order['decoration_name'] ?? '-';
    $deliveryDate = $order['delivery_date'] ?? '-';
    $deliveryTime = $order['delivery_time'] ?? '-';
    $customerName = $order['customer_name'] ?? '-';
    $customerPhone = $order['customer_phone'] ?? '-';
    $observations = $order['observations'] ?? 'Sin observaciones.';
    $customText = $order['custom_text'] ?? '-';
    $estimatedPrice = format_currency((float) ($order['estimated_price'] ?? 0));
    $orderNumber = !empty($order['order_number']) ? $order['order_number'] : '000000';

    $message = "Hola, quiero solicitar una torta.\n\n";
    $message .= "PEDIDO #{$orderNumber}\n\n";
    $message .= "🎂 TORTA\n{$productName}\n\n";
    $message .= "👥 TAMAÑO\n{$sizeName}\n\n";
    $message .= "🍓 RELLENO\n{$fillingName}\n\n";
    $message .= "🍫 COBERTURA\n{$toppingName}\n\n";
    $message .= "🎨 DECORACIÓN\n{$decorationName}\n\n";
    $message .= "✍️ TEXTO\n{$customText}\n\n";
    $message .= "📅 FECHA\n{$deliveryDate}\n\n";
    $message .= "⏰ HORARIO\n{$deliveryTime}\n\n";
    $message .= "💰 PRECIO ESTIMADO\n{$estimatedPrice}\n\n";
    $message .= "👤 CLIENTE\n{$customerName}\n\n";
    $message .= "📱 TELÉFONO\n{$customerPhone}\n\n";
    $message .= "📝 OBSERVACIONES\n{$observations}\n\n";
    $message .= "Por favor confirmar disponibilidad y precio final.";

    return $message;
}

function handle_product_image_upload(array $file): ?string
{
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK || empty($file['name'])) {
        return null;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $info = pathinfo($file['name']);
    $ext = strtolower($info['extension'] ?? '');

    if (!in_array($ext, $allowed, true)) {
        throw new InvalidArgumentException('Formato de imagen no válido. Usa JPG, JPEG, PNG o WEBP.');
    }

    if ($file['size'] > 2000000) {
        throw new InvalidArgumentException('La imagen supera 2 MB.');
    }

    $targetDir = __DIR__ . '/../uploads/products';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    $fileName = uniqid('prod_', true) . '.' . $ext;
    $targetPath = $targetDir . '/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new RuntimeException('No se pudo guardar la imagen del producto.');
    }

    return 'uploads/products/' . $fileName;
}
