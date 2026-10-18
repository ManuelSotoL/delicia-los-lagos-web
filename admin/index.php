<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['admin_logged_in'])) {
    redirect('dashboard.php');
}

redirect('login.php');
