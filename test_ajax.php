<?php
// Simple test to check if AJAX is working
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode([
        'success' => true,
        'message' => 'AJAX endpoint is reachable',
        'post_data' => $_POST,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests allowed',
        'method' => $_SERVER['REQUEST_METHOD']
    ]);
}
?> 