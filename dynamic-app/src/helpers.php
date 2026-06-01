<?php

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function set_flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $message;
}

function task_statuses(): array
{
    return [
        'todo' => 'Belum Dikerjakan',
        'progress' => 'Sedang Dikerjakan',
        'done' => 'Selesai',
    ];
}

function validate_task(string $title, string $description, string $status): array
{
    $errors = [];

    if ($title === '') {
        $errors[] = 'Judul wajib diisi.';
    } elseif (strlen($title) > 120) {
        $errors[] = 'Judul maksimal 120 karakter.';
    }

    if ($description === '') {
        $errors[] = 'Deskripsi wajib diisi.';
    } elseif (strlen($description) > 1000) {
        $errors[] = 'Deskripsi maksimal 1000 karakter.';
    }

    if (!array_key_exists($status, task_statuses())) {
        $errors[] = 'Status tidak valid.';
    }

    return $errors;
}

function format_status(string $status): string
{
    return task_statuses()[$status] ?? $status;
}

function format_date(?string $date): string
{
    if (!$date) {
        return '-';
    }

    return date('d M Y H:i', strtotime($date));
}
