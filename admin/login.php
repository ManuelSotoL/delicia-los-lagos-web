<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $_SESSION['admin_error'] = 'Token CSRF inválido.';
    } else {
        $username = sanitize_string($_POST['username'] ?? '', 80);
        $password = (string) ($_POST['password'] ?? '');

        $attempts = (int) ($_SESSION['admin_login_attempts'] ?? 0);
        if ($attempts >= 5) {
            $_SESSION['admin_error'] = 'Demasiados intentos. Intenta más tarde.';
        } else {
            try {
                $pdo = db_connect();
                $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE username = :username AND is_active = 1 LIMIT 1');
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_user'] = $user['username'];
                    $_SESSION['admin_full_name'] = $user['full_name'];
                    $_SESSION['admin_login_attempts'] = 0;
                    redirect('dashboard.php');
                }

                $_SESSION['admin_login_attempts'] = $attempts + 1;
                $_SESSION['admin_error'] = 'Credenciales inválidas.';
            } catch (Throwable $e) {
                $_SESSION['admin_error'] = 'No se pudo validar el acceso.';
            }
        }
    }
}

$pageTitle = 'Acceso administrativo | ' . SITE_NAME;
$metaDescription = 'Login del panel administrativo de Delicias los Lagos.';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container login-box">
        <div class="login-card">
            <span class="eyebrow">Admin</span>
            <h1>Iniciar sesión</h1>

            <?php if (!empty($_SESSION['admin_error'])): ?>
                <div class="alert alert-error"><?= e($_SESSION['admin_error']); ?></div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>

            <form method="post" action="login.php">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input id="username" name="username" type="text" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Ingresar</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
