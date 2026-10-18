<?php
require_once __DIR__ . '/includes/header.php';

$products = get_product_catalog();
$pageTitle = 'Tortas personalizadas | ' . SITE_NAME;
$metaDescription = 'Descubre tortas personalizadas para cumpleaños, matrimonios y eventos especiales en Delicias los Lagos.';
?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">Pastelería artesanal</span>
            <h1>Tortas hechas para celebrar momentos inolvidables.</h1>
            <p>Diseña tu torta ideal con sabores, rellenos, cobertura y detalles personalizados. Envíalo por WhatsApp y te confirmamos disponibilidad.</p>
            <div class="hero-actions">
                <a href="pedido.php" class="btn btn-primary">Personalizar torta</a>
                <a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>" target="_blank" rel="noopener" class="btn btn-secondary">Pedir por WhatsApp</a>
            </div>
        </div>
        <div class="hero-card">
            <img src="assets/img/hero-cake.svg" alt="Torta artesanal" class="hero-image">
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Nuestra especialidad</span>
            <h2>Tortas destacadas</h2>
        </div>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <img src="<?= e(get_product_image($product['image'] ?? '')); ?>" alt="<?= e($product['name'] ?? 'Torta'); ?>" class="product-image">
                    <div class="product-body">
                        <h3><?= e($product['name'] ?? 'Torta'); ?></h3>
                        <p><?= e($product['description'] ?? ''); ?></p>
                        <div class="product-meta">
                            <span class="price">Desde <?= e(format_currency((float) ($product['base_price'] ?? 0))); ?></span>
                            <span>10 - 50 personas</span>
                        </div>
                        <a href="pedido.php?product=<?= (int) ($product['id'] ?? 0); ?>" class="btn btn-primary btn-block">Personalizar</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Pedidos por mayor</span>
            <h2>Soluciones para eventos, empresas y celebraciones grandes</h2>
        </div>

        <div class="bulk-order-grid">
            <div class="bulk-order-copy">
                <p>Atendemos pedidos por mayor para reuniones familiares, eventos corporativos, cumpleaños masivos y celebraciones con más de 30 personas.</p>
                <ul class="bulk-list">
                    <li>Variedad de sabores y tamaños para diferentes presupuestos.</li>
                    <li>Asesoría personalizada para elegir la torta ideal.</li>
                    <li>Entregas coordinadas y atención rápida por WhatsApp.</li>
                </ul>
            </div>
            <div class="bulk-order-card">
                <h3>Solicita tu cotización</h3>
                <p>Envíanos la cantidad, evento y fecha estimada para recibir una propuesta.</p>
                <a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>?text=Hola%2C%20quiero%20cotizar%20un%20pedido%20por%20mayor" target="_blank" rel="noopener" class="btn btn-primary btn-block">Cotizar por WhatsApp</a>
            </div>
        </div>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Opiniones en Google</span>
            <h2>Lo que dicen nuestras clientes</h2>
        </div>

        <div class="google-review-summary">
            <div class="google-badge">
                <div class="google-logo" aria-label="Google">G</div>
                <div>
                    <div class="google-stars">★★★★★</div>
                    <strong>4.9/5</strong>
                    <p>Más de 120 reseñas con clientes satisfechos.</p>
                </div>
            </div>

            <div class="review-grid">
                <article class="review-card">
                    <div class="review-header">
                        <span class="review-name">María P.</span>
                        <span class="review-stars">★★★★★</span>
                    </div>
                    <p>“La torta quedó hermosa y súper rica. La atención fue excelente y la entrega fue puntual.”</p>
                </article>

                <article class="review-card">
                    <div class="review-header">
                        <span class="review-name">Cristian R.</span>
                        <span class="review-stars">★★★★★</span>
                    </div>
                    <p>“Pedimos una torta para un cumpleaños y todo salió perfecto. La decoración superó nuestras expectativas.”</p>
                </article>

                <article class="review-card">
                    <div class="review-header">
                        <span class="review-name">Sofía C.</span>
                        <span class="review-stars">★★★★★</span>
                    </div>
                    <p>“Muy buena calidad, sabores impecables y un servicio amable. Definitivamente la volveremos a elegir.”</p>
                </article>
            </div>
        </div>

        <div class="google-cta-wrap">
            <a href="https://www.google.com/searchviewer/10?svid=CAwSHRIbCgNwdnESFENnMHZaeTh4TVhKcmFEZHRhMjVvGAo" target="_blank" rel="noopener" class="btn btn-primary">Ver reseñas en Google</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container feature-grid">
        <div class="feature-item">
            <h3>Personalización</h3>
            <p>Elegí torta, tamaño, rellenos, cobertura y texto para cada ocasión.</p>
        </div>
        <div class="feature-item">
            <h3>Entrega</h3>
            <p>Confirmamos fecha y horario para que recibas tu pedido en el momento ideal.</p>
        </div>
        <div class="feature-item">
            <h3>Atención rápida</h3>
            <p>Todo se gestiona por WhatsApp y tu pedido queda guardado en el sistema.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
