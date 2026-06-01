<?php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('error', 'ID task tidak valid.');
    redirect('dashboard.php');
}

$pdo = db();
$stmt = $pdo->prepare("SELECT id, title, description, status FROM tasks WHERE id = :id");
$stmt->execute(['id' => $id]);
$task = $stmt->fetch();

if (!$task) {
    set_flash('error', 'Task tidak ditemukan.');
    redirect('dashboard.php');
}

$errors = [];
$title = $task['title'];
$description = $task['description'];
$status = $task['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? 'todo');

    $errors = validate_task($title, $description, $status);

    if (count($errors) === 0) {
        $update = $pdo->prepare(
            "UPDATE tasks
             SET title = :title, description = :description, status = :status
             WHERE id = :id"
        );
        $update->execute([
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'id' => $id,
        ]);

        set_flash('success', 'Task berhasil diperbarui.');
        redirect('dashboard.php');
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">CRUD</p>
                    <h1>Edit Task</h1>
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

                <button type="submit" class="button button-primary">Update</button>
            </form>
        </section>
    </main>
</body>
</html>
