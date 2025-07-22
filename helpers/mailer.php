<?php


require_once '../database/connect.php';

function sendVerificationEmail($toEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        // Настройки SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.yandex.ru'; // ← замени на свой SMTP сервер
        $mail->SMTPAuth = true;
        $mail->Username = 'stepanpaw@yandex.ru'; // ← твоя почта
        $mail->Password = 'lqcziaognmyzqpwd';           // ← пароль или App Password
        $mail->SMTPSecure = 'ssl';                   // или 'ssl'
        $mail->Port = 465;                           // порт для tls = 587, для ssl = 465

        // От кого и кому
        $mail->setFrom('your_email@example.com', 'MotoProfi');
        $mail->addAddress($toEmail);

        // Контент
        $mail->isHTML(true);
        $mail->Subject = 'Подтверждение электронной почты';
        $mail->Body    = "Чтобы подтвердить свою почту, перейдите по ссылке: <a href='https://yourdomain.ru/verify.php?token=$token'>Подтвердить</a>";
        $mail->AltBody = "Чтобы подтвердить свою почту, откройте ссылку: https://yourdomain.ru/verify.php?token=$token";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Ошибка отправки письма: {$mail->ErrorInfo}");
        return false;
    }
}
?>