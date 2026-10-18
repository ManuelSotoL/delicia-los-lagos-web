<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? SITE_NAME;
$metaDescription = $metaDescription ?? SITE_DESCRIPTION;

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.gstatic.com; img-src 'self' data: https:; font-src 'self' https://fonts.gstatic.com; connect-src 'self'; frame-ancestors 'self'; object-src 'none'; base-uri 'self'; form-action 'self' https://wa.me");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($metaDescription); ?>">
    <meta property="og:title" content="<?= e($pageTitle); ?>">
    <meta property="og:description" content="<?= e($metaDescription); ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= e('/assets/img/logo.png'); ?>">
    <title><?= e($pageTitle); ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= e('/favicon.svg'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e('/assets/css/style.css'); ?>">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a href="index.php" class="brand" aria-label="Ir a inicio">
            <img src="assets/img/logo.svg" alt="Logo Delicias los Lagos" class="brand-logo">
            <div>
                <span class="brand-name">Delicias los Lagos</span>
                <small class="brand-tag">Pastelería artesanal</small>
            </div>
        </a>

        <nav class="main-nav" aria-label="Menú principal">
            <a href="index.php">Inicio</a>
            <a href="pedido.php">Personalizar</a>
            <a href="contacto.php">Contacto</a>
            <a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>" target="_blank" rel="noopener" class="nav-cta">Pedir por WhatsApp</a>
        </nav>
    </div>
</header>

<main class="site-main">
