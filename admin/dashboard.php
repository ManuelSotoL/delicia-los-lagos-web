<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect('login.php');
}

$pdo = db_connect();
$ordersCount = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$productsCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$pendingCount = (int) $pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('Nuevo', 'Contactado')")->fetchColumn();

$pageTitle = 'Dashboard | ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container admin-page">
        <div class="admin-toolbar">
            <h1>Dashboard</h1>
            <div class="admin-actions">
                <a href="pedidos.php" class="btn btn-secondary">Ver pedidos</a>
                <a href="productos.php" class="btn btn-secondary">Productos</a>
                <a href="configuracion.php" class="btn btn-secondary">Configuración</a>
                <a href="logout.php" class="btn btn-primary">Cerrar sesión</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span>Total pedidos</span>
                <strong><?= $ordersCount; ?></strong>
            </div>
            <div class="stat-card">
                <span>Productos</span>
                <strong><?= $productsCount; ?></strong>
            </div>
            <div class="stat-card">
                <span>Pendientes</span>
                <strong><?= $pendingCount; ?></strong>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
