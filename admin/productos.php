<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect('login.php');
}

$pdo = db_connect();
$errors = [];
$success = '';
$editing = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token CSRF inválido.';
    } else {
        $action = $_POST['action'] ?? 'save';

        if ($action === 'delete') {
            $id = sanitize_int($_POST['id'] ?? null);
            if ($id <= 0) {
                $errors[] = 'Producto no válido.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id LIMIT 1');
                $stmt->execute([':id' => $id]);
                $success = 'Producto eliminado correctamente.';
            }
        } else {
            $id = sanitize_int($_POST['id'] ?? null);
            $name = sanitize_string($_POST['name'] ?? '', 120);
            $description = sanitize_string($_POST['description'] ?? '', 500);
            $basePrice = sanitize_decimal($_POST['base_price'] ?? '0');
            $active = isset($_POST['active']) ? 1 : 0;

            if ($name === '') {
                $errors[] = 'El nombre del producto es obligatorio.';
            }

            if ($basePrice === '0.00' && empty($_POST['base_price'])) {
                $errors[] = 'El precio base es obligatorio.';
            }

            $imagePath = $_POST['existing_image'] ?? null;
            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                try {
                    $imagePath = handle_product_image_upload($_FILES['image']);
                } catch (Throwable $e) {
                    $errors[] = $e->getMessage();
                }
            }

            if (empty($errors)) {
                if ($id > 0) {
                    $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, image = :image, base_price = :base_price, active = :active, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
                    $stmt->execute([
                        ':id' => $id,
                        ':name' => $name,
                        ':description' => $description,
                        ':image' => $imagePath,
                        ':base_price' => $basePrice,
                        ':active' => $active,
                    ]);
                    $success = 'Producto actualizado correctamente.';
                } else {
                    $stmt = $pdo->prepare('INSERT INTO products (name, description, image, base_price, active) VALUES (:name, :description, :image, :base_price, :active)');
                    $stmt->execute([
                        ':name' => $name,
                        ':description' => $description,
                        ':image' => $imagePath,
                        ':base_price' => $basePrice,
                        ':active' => $active,
                    ]);
                    $success = 'Producto agregado correctamente.';
                }
            }
        }
    }
}

if (isset($_GET['edit']) && !isset($_POST['action'])) {
    $editId = sanitize_int($_GET['edit'] ?? null);
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $editId]);
    $editing = $stmt->fetch();
}

$products = $pdo->query('SELECT * FROM products ORDER BY created_at DESC')->fetchAll();

$pageTitle = 'Productos | ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container admin-page">
        <div class="admin-toolbar">
            <h1>Catálogo de productos</h1>
            <div class="admin-actions">
                <a href="dashboard.php" class="btn btn-secondary">Volver</a>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?php foreach ($errors as $error): ?><div><?= e($error); ?></div><?php endforeach; ?></div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?= e($success); ?></div>
        <?php endif; ?>

        <div class="order-layout">
            <div class="form-panel">
                <h2><?= $editing ? 'Editar producto' : 'Agregar producto'; ?></h2>
                <form method="post" action="productos.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                    <input type="hidden" name="id" value="<?= e((string) ($editing['id'] ?? 0)); ?>">
                    <input type="hidden" name="action" value="save">
                    <?php if (!empty($editing['image'])): ?>
                        <input type="hidden" name="existing_image" value="<?= e($editing['image']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input id="name" name="name" type="text" maxlength="120" value="<?= e((string) ($editing['name'] ?? '')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea id="description" name="description" rows="4" maxlength="500"><?= e((string) ($editing['description'] ?? '')); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="base_price">Precio base</label>
                            <input id="base_price" name="base_price" type="number" min="0" step="0.01" value="<?= e((string) ($editing['base_price'] ?? '0')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen</label>
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>

                    <div class="check-row">
                        <input type="checkbox" id="active" name="active" <?= !empty($editing) && (int) ($editing['active'] ?? 0) === 1 ? 'checked' : 'checked'; ?>>
                        <label for="active">Producto activo</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full"><?= $editing ? 'Guardar cambios' : 'Agregar producto'; ?></button>
                </form>
            </div>

            <div class="summary-box">
                <h2>Listado</h2>
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= e((string) ($product['name'] ?? '')); ?></td>
                                <td><?= e(format_currency((float) ($product['base_price'] ?? 0))); ?></td>
                                <td><?= (int) ($product['active'] ?? 0) === 1 ? 'Activo' : 'Inactivo'; ?></td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="productos.php?edit=<?= (int) ($product['id'] ?? 0); ?>" class="btn btn-secondary btn-small">Editar</a>
                                        <form method="post" action="productos.php" onsubmit="return confirm('¿Eliminar este producto?');" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) ($product['id'] ?? 0); ?>">
                                            <button type="submit" class="btn btn-primary btn-small">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
