<?php
require_once '../database/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user']['user_id']) || !isset($_SESSION['order_success'])) {
    exit;
}

$orderData = $_SESSION['order_success'];
unset($_SESSION['order_success']);

if (time() - ($orderData['timestamp'] ?? 0) > 300) {
    exit;
}


$stmt = $pdo->prepare("SELECT user_name, user_phone FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user']['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userName = $user['user_name'] ?? '';
$userPhone = $user['user_phone'] ?? '';
?>

<section id="orderPopup" class="popup-overlay flex jc-c ai-c active" role="dialog" aria-modal="true">
    <div class="popup-content active">
        <div class="popup-header flex ai-c jc-sb">
            <p class="ff-um dg3-text ta-l popup-title">
                <?= htmlspecialchars($userName) ?>, ваш заказ оформлен!
            </p>
            <button class="popup-x flex ai-c jc-c" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p class="ff-ur dg3-text ta-l popup-description">
                Спасибо за покупку! Мы свяжемся с вами по номеру <?= htmlspecialchars($userPhone) ?> для уточнения деталей вашего заказа.
            </p>
        </div>

        <div class="popup-footer flex jc-sb">
            <button class="ff-usb popup-btn bg-ab w-text popup-primary" onclick="window.location.href='index.php?page=profile'">
                Мои заказы
            </button>
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">
                Закрыть
            </button>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('orderPopup');
    if (!popup) return;

    const closeModal = () => {
        popup.classList.remove('active');
        setTimeout(() => popup.remove(), 300);


        fetch(`/database/mark-order-viewed.php?order_id=<?= (int)$orderData['order_id'] ?>`)
            .catch(err => console.error('Error marking order:', err));
    };


    popup.querySelectorAll('.popup-x, .popup-secondary').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    popup.addEventListener('click', (e) => {
        if (e.target === popup) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
});
</script>
