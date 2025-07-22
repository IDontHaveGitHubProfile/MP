<?php
require_once __DIR__ . '/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        session_start();
        unset($_SESSION['show_email_success']);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Flag cleared successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error clearing flag: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}