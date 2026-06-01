<?php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$errors = [];
$title = '';
$description = '';
$status = 'todo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? 'todo');

    $errors = validate_task($title, $description, $status);

    if (count($errors) === 0) {
        $stmt = db()->prepare(
            "INSERT INTO tasks (title, description, status, created_by)
             VALUES (:title, :description, :status, :created_by)"
        );
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'created_by' => current_user()['id'],
        ]);

        set_flash('success', 'Task berhasil ditambahkan.');
        redirect('dashboard.php');
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Task</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">CRUD</p>
                    <h1>Tambah Task</h1>
                </div>
                <a href="dashboard.php" class="button button-secondary">Kembali</a>
            </div>

            <?php foreach ($errors as $error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <form method="post" class="form">
                <label for="title">Judul</label>
                <input id="title" name="title" type="text" value="<?= e($title) ?>" maxlength="120" required>

                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="5" maxlength="1000" required><?= e($description) ?></textarea>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <?php foreach (task_statuses() as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="button button-primary">Simpan</button>
            </form>
        </section>
    </main>
</body>
</html>
