<?php
require_once __DIR__ . '/includes/header.php';
$pageTitle = 'Contacto | ' . SITE_NAME;
$metaDescription = 'Contacta a Delicias los Lagos para consultas, pedidos y cotizaciones de tortas personalizadas.';
?>

<section class="section">
    <div class="container contact-grid">
        <div>
            <span class="eyebrow">Contacto</span>
            <h1>Hablemos de tu próxima torta</h1>
            <p>Si necesitas asesoría o tienes dudas sobre sabores, entregas o decoración, te responderemos con gusto.</p>
            <ul class="plain-list">
                <li>WhatsApp: <a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>" target="_blank" rel="noopener">+56 <?= e(WHATSAPP_NUMBER); ?></a></li>
                <li>Email: <a href="mailto:<?= e(ADMIN_EMAIL); ?>"><?= e(ADMIN_EMAIL); ?></a></li>
                <li>Horario: Martes a Domingo, 14:00 a 21:00 hrs.</li>
            </ul>
        </div>
        <div class="contact-card">
            <a href="pedido.php" class="btn btn-primary btn-block">Personalizar torta</a>
            <a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-block">Pedir por WhatsApp</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
