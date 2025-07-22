<?php
session_start();

$host = 'localhost';   
$dbname = 'motoprofi';       
$username = 'root';       
$password = '';           

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
        PDO::ATTR_EMULATE_PREPARES => false,                
    ]);
} catch (PDOException $e) {
    error_log("Ошибка подключения: " . $e->getMessage());


    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных. Попробуйте позже.']);
    exit;
}
