<?php
require_once __DIR__ . '/includes/header.php';

$products = get_product_catalog();
$sizes = get_size_options();
$fillings = get_filling_options();
$toppings = get_topping_options();
$decorations = get_decoration_options();

$selectedProductId = isset($_GET['product']) ? (int) $_GET['product'] : (int) ($products[0]['id'] ?? 0);
$defaultProduct = get_product_by_id($selectedProductId) ?: $products[0] ?? null;
$defaultBasePrice = (float) ($defaultProduct['base_price'] ?? 0);
$defaultSizeId = (int) ($sizes[0]['id'] ?? 0);
$defaultFillingId = (int) ($fillings[0]['id'] ?? 0);
$defaultToppingId = (int) ($toppings[0]['id'] ?? 0);
$defaultDecorationId = (int) ($decorations[0]['id'] ?? 0);

$pageTitle = 'Personaliza tu torta | ' . SITE_NAME;
$metaDescription = 'Selecciona tu torta, tamaño, relleno, cobertura y decoración para crear tu pedido personalizado.';
?>

<section class="section section-tight">
    <div class="container">
        <div class="section-header section-header-left">
            <span class="eyebrow">Pedido</span>
            <h1>Personaliza tu torta</h1>
        </div>

        <form method="post" action="guardar_pedido.php" class="order-form" id="order-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">

            <div class="order-layout">
                <div class="form-panel">
                    <div class="form-group">
                        <label for="product_id">Torta</label>
                        <select id="product_id" name="product_id" required>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= (int) $product['id']; ?>" data-base-price="<?= (float) ($product['base_price'] ?? 0); ?>" <?= ((int) ($product['id'] ?? 0) === $selectedProductId) ? 'selected' : ''; ?>>
                                    <?= e($product['name'] ?? 'Torta'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="size_id">Tamaño</label>
                            <select id="size_id" name="size_id" required>
                                <?php foreach ($sizes as $size): ?>
                                    <option value="<?= (int) $size['id']; ?>" data-price="<?= (float) ($size['price_modifier'] ?? 0); ?>" <?= ((int) $size['id'] === $defaultSizeId) ? 'selected' : ''; ?>>
                                        <?= e($size['name'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="filling_id">Relleno</label>
                            <select id="filling_id" name="filling_id" required>
                                <?php foreach ($fillings as $filling): ?>
                                    <option value="<?= (int) $filling['id']; ?>" data-price="<?= (float) ($filling['price_modifier'] ?? 0); ?>" <?= ((int) $filling['id'] === $defaultFillingId) ? 'selected' : ''; ?>>
                                        <?= e($filling['name'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="topping_id">Cobertura</label>
                            <select id="topping_id" name="topping_id" required>
                                <?php foreach ($toppings as $topping): ?>
                                    <option value="<?= (int) $topping['id']; ?>" data-price="<?= (float) ($topping['price_modifier'] ?? 0); ?>" <?= ((int) $topping['id'] === $defaultToppingId) ? 'selected' : ''; ?>>
                                        <?= e($topping['name'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="decoration_id">Decoración</label>
                            <select id="decoration_id" name="decoration_id" required>
                                <?php foreach ($decorations as $decoration): ?>
                                    <option value="<?= (int) $decoration['id']; ?>" data-price="<?= (float) ($decoration['price_modifier'] ?? 0); ?>" <?= ((int) $decoration['id'] === $defaultDecorationId) ? 'selected' : ''; ?>>
                                        <?= e($decoration['name'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="custom_text">Texto que quieres escribir en la torta</label>
                        <input id="custom_text" name="custom_text" type="text" maxlength="80" placeholder="Ej.: Feliz cumpleaños María" value="">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="delivery_date">Fecha de entrega</label>
                            <input id="delivery_date" name="delivery_date" type="date" required min="<?= e(date('Y-m-d', strtotime('+' . MIN_DAYS_ANTICIPATION . ' days'))); ?>">
                        </div>

                        <div class="form-group">
                            <label for="delivery_time">Horario preferido</label>
                            <select id="delivery_time" name="delivery_time" required>
                                <?php foreach (json_decode(DELIVERY_HOURS, true) as $slot): ?>
                                    <option value="<?= e($slot); ?>"><?= e($slot); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observations">Observaciones</label>
                        <textarea id="observations" name="observations" rows="4" placeholder="Queremos que la torta sea sin nueces y con colores pastel..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_name">Nombre</label>
                            <input id="customer_name" name="customer_name" type="text" required maxlength="80" placeholder="Juan Pérez">
                        </div>

                        <div class="form-group">
                            <label for="customer_phone">Teléfono</label>
                            <input id="customer_phone" name="customer_phone" type="tel" required maxlength="20" inputmode="tel" placeholder="9XXXXXXXX">
                        </div>
                    </div>
                </div>

                <aside class="summary-panel">
                    <div class="summary-box">
                        <h3>Resumen</h3>
                        <ul class="summary-list">
                            <li><span>Torta</span><strong id="summary-product">-</strong></li>
                            <li><span>Tamaño</span><strong id="summary-size">-</strong></li>
                            <li><span>Relleno</span><strong id="summary-filling">-</strong></li>
                            <li><span>Cobertura</span><strong id="summary-topping">-</strong></li>
                            <li><span>Decoración</span><strong id="summary-decoration">-</strong></li>
                            <li><span>Texto</span><strong id="summary-text">-</strong></li>
                            <li><span>Fecha</span><strong id="summary-date">-</strong></li>
                        </ul>
                        <div class="price-box">
                            <span>Precio estimado</span>
                            <strong id="estimated-price"><?= e(format_currency($defaultBasePrice)); ?></strong>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full">Enviar por WhatsApp</button>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
