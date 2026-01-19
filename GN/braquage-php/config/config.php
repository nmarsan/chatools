<?php
// Configuration
define('BASE_PATH', __DIR__ . '/..');
define('DATA_PATH', BASE_PATH . '/data');
define('SESSIONS_PATH', DATA_PATH . '/sessions');
define('SCENES_FILE', DATA_PATH . '/scenes.php');

// Create directories if they don't exist
if (!file_exists(SESSIONS_PATH)) {
    mkdir(SESSIONS_PATH, 0755, true);
}

// Load scenes data
require_once SCENES_FILE;
?>

