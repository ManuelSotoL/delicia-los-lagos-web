<?php
require_once __DIR__ . '/includes/header.php';

$orderId = isset($_GET['pedido']) ? (int) $_GET['pedido'] : 0;
$waUrl = $_GET['wa'] ?? '';
$pageTitle = 'Gracias por tu pedido | ' . SITE_NAME;
$metaDescription = 'Tu pedido fue recibido y será confirmado por WhatsApp.';
?>

<section class="section">
    <div class="container thank-you-box">
        <span class="eyebrow">Pedido registrado</span>
        <h1>¡Gracias por tu pedido!</h1>
        <p>Tu solicitud fue recibida correctamente y quedó guardada en nuestro sistema.</p>
        <?php if ($orderId > 0): ?>
            <p class="order-reference">Pedido #<?= str_pad((string) $orderId, 6, '0', STR_PAD_LEFT); ?></p>
        <?php endif; ?>
        <div class="thank-you-actions">
            <a href="index.php" class="btn btn-secondary">Volver al inicio</a>
            <?php if (!empty($waUrl)): ?>
                <a href="<?= e($waUrl); ?>" target="_blank" rel="noopener" class="btn btn-primary">Continuar por WhatsApp</a>
            <?php else: ?>
                <a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>" target="_blank" rel="noopener" class="btn btn-primary">Enviar por WhatsApp</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    <?php if (!empty($waUrl)): ?>
        window.addEventListener('load', function () {
            window.open('<?= addslashes($waUrl); ?>', '_blank');
        });
    <?php endif; ?>
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
