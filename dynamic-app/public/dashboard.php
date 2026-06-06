<?php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$pdo = db();
$message = get_flash('success');
$error = get_flash('error');

$stmt = $pdo->query(
    "SELECT id, title, description, status, created_at, updated_at
     FROM tasks
     ORDER BY created_at DESC"
);
$tasks = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UAS Dynamic App</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="topbar">
        <div>
            <p class="eyebrow">UAS Administrasi Server | claude</p>
            <h1>Dashboard Admin</h1>
        </div>
        <nav class="nav-actions">
            <span>Halo, <?= e(current_user()['full_name']) ?></span>
            <a href="logout.php" class="button button-secondary">Logout</a>
        </nav>
    </header>

    <main class="container">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2>Data Tasks</h2>
                    <p>Data tersimpan di MariaDB dan dikelola dengan CRUD PHP native.</p>
                </div>
                <a href="create.php" class="button button-primary">Tambah Task</a>
            </div>

            <?php if (count($tasks) === 0): ?>
                <div class="empty-state">
                    <h3>Belum ada data</h3>
                    <p>Tambahkan task pertama untuk demo CRUD.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= e($task['title']) ?></td>
                                    <td><?= e($task['description']) ?></td>
                                    <td><span class="badge"><?= e(format_status($task['status'])) ?></span></td>
                                    <td><?= e(format_date($task['created_at'])) ?></td>
                                    <td class="actions">
                                        <a href="edit.php?id=<?= (int) $task['id'] ?>">Edit</a>
                                        <a href="delete.php?id=<?= (int) $task['id'] ?>" class="danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
