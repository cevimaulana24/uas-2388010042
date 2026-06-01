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
$stmt = $pdo->prepare("SELECT id, title FROM tasks WHERE id = :id");
$stmt->execute(['id' => $id]);
$task = $stmt->fetch();

if (!$task) {
    set_flash('error', 'Task tidak ditemukan.');
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $delete->execute(['id' => $id]);

    set_flash('success', 'Task berhasil dihapus.');
    redirect('dashboard.php');
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Task</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <p class="eyebrow">Konfirmasi</p>
            <h1>Hapus Task</h1>
            <p>Apakah kamu yakin ingin menghapus task berikut?</p>
            <div class="delete-box"><?= e($task['title']) ?></div>

            <form method="post" class="button-row">
                <button type="submit" class="button button-danger">Ya, Hapus</button>
                <a href="dashboard.php" class="button button-secondary">Batal</a>
            </form>
        </section>
    </main>
</body>
</html>
