<?php
require_once '../database/connect.php';
$user_name = $_SESSION['user']['user_name'] ?? '';
$user_surname = $_SESSION['user']['user_surname'] ?? '';
$user_email = ($_SESSION['user']['user_email_verified'] ?? 0) == 1 ? ($_SESSION['user']['user_email'] ?? '') : '';
?>

<section id="contactsPopup" 
         class="popup-overlay flex jc-c ai-c"
         role="dialog"
         aria-modal="true"
         aria-labelledby="contactsTitle"
         aria-describedby="contactsDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="contactsTitle" class="ff-um dg3-text ta-l popup-title">Напишите нам</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <form id="contactForm" class="popup-body flex fd-c" method="POST">
            <p id="contactsDesc" class="ff-ur dg3-text ta-l popup-description">
                Не стесняйтесь обращаться к нам! Оставьте своё сообщение, и мы обязательно ответим вам в ближайшее время.
            </p>
            <p class="ff-ur dg3-text ta-l popup-description">
                Сообщение будет отправлено с привязанного адреса email <strong><?= htmlspecialchars($user_email) ?></strong> и от вашего имени <strong><?= htmlspecialchars($user_name . ' ' . $user_surname) ?></strong>.
            </p>

           <div class="popup-textarea-wrapper">
                <textarea id="contactMessage"
                        class="ff-um popup-input"
                        placeholder="Ваше сообщение..."
                        rows="4"
                        minlength="10"
                        maxlength="1000"
                        required
                        aria-label="Текст сообщения"
                        style="resize: none;"></textarea>
                <span id="messageCounter" class="ff-usb ab-text char-counter">0/1000</span>
            </div>

        </form>

        <div class="popup-footer flex jc-fe">
            <button id="sendContactBtn" type="submit" class="ff-usb popup-btn bg-ab w-text popup-primary">Отправить</button>
            <button type="button" class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Отмена</button>
        </div>
    </div>
</section>

<aside id="contactFailurePopup" 
       class="popup-overlay flex jc-c ai-c"
       role="alertdialog"
       aria-modal="true"
       aria-labelledby="contactFailureTitle"
       aria-describedby="contactFailureDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="contactFailureTitle" class="ff-um dg3-text ta-l popup-title">Ошибка сервера</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="contactFailureDesc" class="ff-ur dg3-text ta-l popup-description">
                Произошла ошибка отправки письма. Пожалуйста, попробуйте позже
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder w-100">Закрыть</button>
        </div>
    </div>
</aside>


<aside id="contactSuccessPopup" 
       class="popup-overlay flex jc-c ai-c"
       role="alertdialog"
       aria-modal="true"
       aria-labelledby="contactSuccessTitle"
       aria-describedby="contactSuccessDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="contactSuccessTitle" class="ff-um dg3-text ta-l popup-title">Ваше сообщение отправлено!</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="contactSuccessDesc" class="ff-ur dg3-text ta-l popup-description">
                Сообщение успешно отправлено! Мы рады, что вы обратились к нам. Ожидайте ответа на ваш email в ближайшие дни.
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder w-100">Закрыть</button>
        </div>
    </div>
</aside>
