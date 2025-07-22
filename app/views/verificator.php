<?php
require_once '../database/connect.php';

// Включение отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = $_GET['token'] ?? null;
$debug = isset($_GET['debug']);

if (!$token) {
    header("Location: index.php?page=home");
    exit;
}

if ($debug) {
    echo "<!-- DEBUG MODE ACTIVATED -->";
    error_log("[Verificator] Debug mode. Token: " . substr($token, 0, 5) . "...");
}
?>

<div class="verification-container" style="text-align: center; margin: 50px 0;">
    <div class="loader" style="width: 60px; height: 60px; border: 5px solid #f3f3f3; border-top: 5px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
    <p style="margin-top: 20px;">Подтверждение email...</p>
</div>

<script>
// Отладка в консоли
console.log("[Verificator] Starting verification process");
console.log("[Verificator] Token:", "<?= substr($token, 0, 5) ?>...");



function startVerification() {
    console.log("[Verificator] Starting verification with token:", "<?= substr($token, 0, 5) ?>...");
    
    const script = document.createElement('script');
    script.src = `/folder/public/js/emailVerifyToken.js?token=<?= $token ?><?= $debug ? '&debug=1' : '' ?>`;
    
    script.onload = function() {
        console.log("[Verificator] Script loaded successfully");
    };
    
    script.onerror = function() {
        console.error("[Verificator] Failed to load script");
        alert("Ошибка загрузки системы подтверждения");
    };
    
    document.head.appendChild(script);
}

startVerification();


// Стиль для анимации
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>