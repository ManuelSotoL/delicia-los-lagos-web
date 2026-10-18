<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        throw new RuntimeException('Token CSRF inválido.');
    }

    $whatsapp = trim((string) ($_POST['whatsapp_number'] ?? ''));
    $days = (int) ($_POST['min_days_anticipation'] ?? MIN_DAYS_ANTICIPATION);
    $hours = $_POST['delivery_hours'] ?? [];

    save_setting('whatsapp_number', $whatsapp);
    save_setting('min_days_anticipation', (string) $days);
    save_setting('delivery_hours', json_encode(array_values($hours)));
    $_SESSION['admin_success'] = 'Configuración actualizada.';
    redirect('configuracion.php');
}

$pageTitle = 'Configuración | ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container admin-page">
        <div class="admin-toolbar">
            <h1>Configuración</h1>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </div>

        <?php if (!empty($_SESSION['admin_success'])): ?>
            <div class="alert alert-success"><?= e($_SESSION['admin_success']); ?></div>
            <?php unset($_SESSION['admin_success']); ?>
        <?php endif; ?>

        <form method="post" class="form-panel admin-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <div class="form-group">
                <label for="whatsapp_number">Número de WhatsApp</label>
                <input id="whatsapp_number" name="whatsapp_number" type="text" value="<?= e(get_setting('whatsapp_number', WHATSAPP_NUMBER)); ?>">
            </div>
            <div class="form-group">
                <label for="min_days_anticipation">Días mínimos de anticipación</label>
                <input id="min_days_anticipation" name="min_days_anticipation" type="number" min="1" value="<?= e((string) get_min_days_anticipation()); ?>">
            </div>
            <div class="form-group">
                <label>Horarios de entrega</label>
                <div class="checkbox-list">
                    <?php foreach (['10:00 - 12:00','12:00 - 14:00','14:00 - 16:00','16:00 - 18:00'] as $slot): ?>
                        <?php $checked = in_array($slot, get_delivery_hours(), true) ? 'checked' : ''; ?>
                        <label class="check-row"><input type="checkbox" name="delivery_hours[]" value="<?= e($slot); ?>" <?= $checked; ?>> <?= e($slot); ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
