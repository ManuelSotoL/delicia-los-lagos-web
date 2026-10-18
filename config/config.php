<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'gc_maxlifetime' => 1800,
    ]);
}

date_default_timezone_set('America/Santiago');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443 ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'www.deliciasloslagos.cl';
$siteUrl = $scheme . '://' . $host;

$config = [
    'site_name' => 'Delicias los Lagos',
    'site_url' => $siteUrl,
    'whatsapp_number' => '56900000000',
    'currency' => 'CLP',
    'min_days_anticipation' => 2,
    'delivery_hours' => [
        '10:00 - 12:00',
        '12:00 - 14:00',
        '14:00 - 16:00',
        '16:00 - 18:00',
    ],
    'admin_email' => 'ventas@deliciasloslagos.cl',
    'site_description' => 'Tortas personalizadas para cumpleaños, bautizos, matrimonios y eventos especiales.',
];

foreach ($config as $key => $value) {
    define('CONFIG_' . strtoupper($key), $value);
}

define('SITE_NAME', $config['site_name']);
define('SITE_URL', $config['site_url']);
define('WHATSAPP_NUMBER', preg_replace('/[^0-9]/', '', $config['whatsapp_number']));
define('CURRENCY', $config['currency']);
define('MIN_DAYS_ANTICIPATION', (int) $config['min_days_anticipation']);
define('DELIVERY_HOURS', json_encode($config['delivery_hours']));
define('ADMIN_EMAIL', $config['admin_email']);
define('SITE_DESCRIPTION', $config['site_description']);
