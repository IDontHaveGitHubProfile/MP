<?php
date_default_timezone_set('Europe/Moscow');
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../database/connect.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Метод не разрешён']);
    exit;
}

try {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subscribeNews = isset($_POST['subscribe_news']) ? (int)$_POST['subscribe_news'] : 0;

    if (empty($email)) {
        throw new Exception('Email не может быть пустым', 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Неверный формат email', 400);
    }

    if (!isset($_SESSION['user']['user_id'])) {
        throw new Exception('Пользователь не авторизован', 401);
    }

    $userId = $_SESSION['user']['user_id'];
    $expires = date('Y-m-d H:i:s', time() + 600);
    $emailToken = bin2hex(random_bytes(32));
    $newsToken = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare("
        UPDATE users 
        SET 
            user_email = :email,
            email_verification_token = :email_token,
            email_verification_token_expires = :expires,
            is_subscribed_to_news = :subscribe_news,
            news_subscription_token = :news_token,
            news_subscription_updated_at = NOW(),
            user_email_verified = 0
        WHERE user_id = :user_id
    ");

    $stmt->execute([
        ':email' => $email,
        ':email_token' => $emailToken,
        ':expires' => $expires,
        ':subscribe_news' => $subscribeNews,
        ':news_token' => $newsToken,
        ':user_id' => $userId
    ]);

    $mail = new PHPMailer(true); // делал через яндекс почту
    try {
        $mail->isSMTP();
        $mail->Host = 'ваш smtp'; // smtp.yandex.ru
        $mail->SMTPAuth = true;
        $mail->Username = 'ваш адрес почты'; //@yandex.ru (1)*
        $mail->Password = 'и пароль приложения'; // https://id.yandex.ru/security/app-passwords -> Почта и вставляем тот, который даст яндекс
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // порт использует TLS
        $mail->CharSet = 'UTF-8';

        $verificationLink = "http://localhost/folder/public/index.php?page=verificator&token=$emailToken";
        $unsubscribeLink = "http://localhost/folder/public/index.php?page=unsubscribe&token=$newsToken";
        
        $mail->setFrom('снова ваша почта здесь', 'Мото-Профи'); // * сюда надо второй раз написать почту из 1
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Подтверждение email';
        
$mail->Body = "
    <div style=\"font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;\">
        <div style=\"max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);\">
            <div style=\"padding: 25px 30px; background-color: #222831;\">
                <h2 style=\"color: #ffffff; margin: 0; font-weight: normal;\">Подтверждение электронной почты</h2>
            </div>
            <div style=\"padding: 30px;\">
                <p style=\"font-size: 16px; color: #333333; margin-bottom: 20px;\">
                    Здравствуйте! Чтобы подтвердить адрес <strong>$email</strong>, нажмите на кнопку ниже:
                </p>
                <p style=\"text-align: center;\">
                    <a href=\"$verificationLink\" style=\"
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #007BFF;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 5px;
                        font-size: 16px;
                    \">Подтвердить email</a>
                </p>
                <p style=\"font-size: 14px; color: #888888; text-align: center; margin: 20px 0 0;\">
                    Или скопируйте ссылку: <br>
                    <a href=\"$verificationLink\" style=\"color: #007BFF; word-break: break-all;\">$verificationLink</a>
                </p>
                <hr style=\"margin: 30px 0; border: none; border-top: 1px solid #eeeeee;\">
                <p style=\"font-size: 14px; color: #555555;\">
                    Ссылка активна в течение <strong>10 минут</strong>.
                </p>
                <p style=\"font-size: 14px; color: #555555;\">
                    Статус подписки: <strong>" . ($subscribeNews ? 'Подписаны' : 'Не подписаны') . "</strong><br>
                    <a href=\"$unsubscribeLink\" style=\"color: #007BFF;\">Отписаться от рассылки</a>
                </p>
            </div>
            <div style=\"padding: 15px 30px; background-color: #f9f9f9; font-size: 12px; color: #999999; text-align: center;\">
                © " . date('Y') . " Мото-Профи. Все права защищены.
            </div>
        </div>
    </div>
";


        $mail->send();

        echo json_encode([
            'status' => 'success',
            'message' => 'Письмо отправлено',
            'data' => [
                'email' => $email,
                'expires' => $expires
            ]
        ]);

    } catch (Exception $e) {
        $pdo->prepare("
            UPDATE users 
            SET 
                email_verification_token = NULL,
                email_verification_token_expires = NULL,
                news_subscription_token = NULL
            WHERE user_id = ?
        ")->execute([$userId]);
        
        throw new Exception('Ошибка отправки письма: ' . $e->getMessage(), 500);
    }

} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Ошибка базы данных',
        'clear_lock' => true
    ]);
} catch (Exception $e) {
    error_log('Exception: ' . $e->getMessage());
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'clear_lock' => true
    ]);
}