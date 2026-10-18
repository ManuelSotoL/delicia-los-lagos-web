<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect('login.php');
}

$pdo = db_connect();
$orders = $pdo->query('SELECT o.*, p.name AS product_name FROM orders o LEFT JOIN products p ON p.id = o.product_id ORDER BY o.id DESC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_update'])) {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = trim((string) ($_POST['status'] ?? ''));
    $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id')->execute([':status' => $status, ':id' => $orderId]);
    redirect('pedidos.php');
}

$pageTitle = 'Pedidos | ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container admin-page">
        <div class="admin-toolbar">
            <h1>Pedidos</h1>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Fecha entrega</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= (int) $order['id']; ?></td>
                            <td><?= e($order['customer_name']); ?><br><small><?= e($order['customer_phone']); ?></small></td>
                            <td><?= e($order['product_name']); ?></td>
                            <td><?= e($order['delivery_date']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                                    <input type="hidden" name="status_update" value="1">
                                    <input type="hidden" name="order_id" value="<?= (int) $order['id']; ?>">
                                    <select name="status">
                                        <?php foreach (['Nuevo','Contactado','Confirmado','En preparación','Listo','Entregado','Cancelado'] as $state): ?>
                                            <option value="<?= e($state); ?>" <?= $order['status'] === $state ? 'selected' : ''; ?>><?= e($state); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-small">Guardar</button>
                                </form>
                            </td>
                            <td><?= e(format_currency((float) $order['estimated_price'])); ?></td>
                            <td><a href="pedido.php?id=<?= (int) $order['id']; ?>" class="btn btn-small btn-secondary">Ver</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
