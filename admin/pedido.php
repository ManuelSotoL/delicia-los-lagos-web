<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect('login.php');
}

$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pdo = db_connect();
$order = $pdo->prepare('SELECT o.*, p.name AS product_name, s.name AS size_name, f.name AS filling_name, t.name AS topping_name, d.name AS decoration_name FROM orders o LEFT JOIN products p ON p.id = o.product_id LEFT JOIN sizes s ON s.id = o.size_id LEFT JOIN fillings f ON f.id = o.filling_id LEFT JOIN toppings t ON t.id = o.topping_id LEFT JOIN decorations d ON d.id = o.decoration_id WHERE o.id = :id LIMIT 1');
$order->execute([':id' => $orderId]);
$order = $order->fetch();

$pageTitle = 'Detalle de pedido | ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container admin-page">
        <div class="admin-toolbar">
            <h1>Detalle del pedido</h1>
            <a href="pedidos.php" class="btn btn-secondary">Volver</a>
        </div>

        <?php if (!$order): ?>
            <p>Pedido no encontrado.</p>
        <?php else: ?>
            <div class="order-detail-box">
                <h3>Pedido #<?= (int) $order['id']; ?></h3>
                <ul class="plain-list">
                    <li><strong>Cliente:</strong> <?= e($order['customer_name']); ?></li>
                    <li><strong>Teléfono:</strong> <?= e($order['customer_phone']); ?></li>
                    <li><strong>Producto:</strong> <?= e($order['product_name']); ?></li>
                    <li><strong>Tamaño:</strong> <?= e($order['size_name']); ?></li>
                    <li><strong>Relleno:</strong> <?= e($order['filling_name']); ?></li>
                    <li><strong>Cobertura:</strong> <?= e($order['topping_name']); ?></li>
                    <li><strong>Decoración:</strong> <?= e($order['decoration_name']); ?></li>
                    <li><strong>Texto:</strong> <?= e($order['custom_text'] ?: 'Sin texto'); ?></li>
                    <li><strong>Fecha entrega:</strong> <?= e($order['delivery_date']); ?> <?= e($order['delivery_time']); ?></li>
                    <li><strong>Observaciones:</strong> <?= e($order['observations'] ?: 'Sin observaciones'); ?></li>
                    <li><strong>Precio estimado:</strong> <?= e(format_currency((float) $order['estimated_price'])); ?></li>
                    <li><strong>Estado:</strong> <?= e($order['status']); ?></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
