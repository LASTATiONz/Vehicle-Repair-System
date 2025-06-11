<?php
function write_log($message) {
    date_default_timezone_set('Asia/Bangkok');

    // Absolute log folder path
    $log_dir = realpath(__DIR__ . '/../logs');

    // If the folder doesn't exist, try to create it
    if (!$log_dir) {
        $log_dir = __DIR__ . '/../logs';
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true); // create logs folder with write permissions
        }
    }

    // Set log file path
    $log_file = $log_dir . '/activity_log_' . date('Y-m-d') . '.log';

    // Format message
    $time = date('[Y-m-d H:i:s]');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN_IP';
    $full_message = "$time [$ip] $message" . PHP_EOL;

    // Write to log file
    file_put_contents($log_file, $full_message, FILE_APPEND);
}
