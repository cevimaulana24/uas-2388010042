<?php

function required_env(string $key): string
{
    $value = getenv($key);

    if ($value === false || trim($value) === '') {
        throw new RuntimeException("Environment variable {$key} belum dikonfigurasi.");
    }

    return trim($value);
}

return [
    'db' => [
        'host' => required_env('DB_HOST'),
        'port' => (int) required_env('DB_PORT'),
        'name' => required_env('DB_NAME'),
        'user' => required_env('DB_USER'),
        'password' => required_env('DB_PASSWORD'),
    ],
];
