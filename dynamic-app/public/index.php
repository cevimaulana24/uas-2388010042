<?php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/helpers.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

redirect('login.php');
