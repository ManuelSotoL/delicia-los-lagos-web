<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect('login.php');
}

$pageTitle = 'Opciones | ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container admin-page">
        <div class="admin-toolbar">
            <h1>Opciones</h1>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </div>
        <p>Desde aquí puede expandirse la administración de tamaños, rellenos, coberturas y decoraciones.</p>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
