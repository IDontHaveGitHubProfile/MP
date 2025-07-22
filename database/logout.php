<?php
header('Content-Type: application/json'); 

require_once '../database/connect.php';

try {

    if (isset($_SESSION['user']['user_id'])) {
        $userId = $_SESSION['user']['user_id'];
        $pdo->prepare('DELETE FROM persistent_logins WHERE user_id = ?')
           ->execute([$userId]);
    }

    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();

    setcookie(
        'remember_token',
        '',
        time() - 3600,
        '/',
        '',          
        false,       
        true         
    );

    echo json_encode(['success' => true]);
    exit;

} catch (PDOException $e) {
    error_log("Logout DB Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
} catch (Exception $e) {
    error_log("Logout Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Logout failed']);
}
?>