<section id="loginPopup" 
         class="popup-overlay flex jc-c ai-c"
         role="dialog"
         aria-modal="true"
         aria-labelledby="loginTitle"
         aria-describedby="loginDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="loginTitle" class="ff-um dg3-text ta-l popup-title">Авторизация</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <form id="loginPopupForm" class="popup-body flex fd-c" method="POST">
            <p id="loginDesc" class="ff-ur dg3-text ta-l popup-description">
                Введите данные, чтобы войти
            </p>

            <div class="popup-inner flex fd-c">
                <div class="popup-input-wrapper">
                    <input id="phoneInput" class="ff-um popup-input" type="tel" placeholder="Телефон" aria-label="Номер телефона">
                    <button type="button" id="popup-clear" class="popup-sideform-icon" style="display: none;" aria-label="Очистить поле">
                        <img class="popup-side-icon" src="../public/assets/clear.svg" alt="Очистить"/>
                    </button>
                </div>
                <div class="flex jc-fs">
                    <button id="switchToEmail" type="button" class="ff-ur ab-text underline">Войти другим способом</button>
                </div>
                <div id="phoneErrorPopup" class="ff-ur ar-text error-popup" role="alert"></div>
            </div>

            <div class="popup-inner flex fd-c">
                <div class="popup-input-wrapper">
                    <input id="passwordInput" class="ff-um popup-input" type="password" placeholder="Пароль" aria-label="Пароль">
                    <button type="button" class="popup-sideform-icon" data-status="closed" aria-label="Показать пароль">
                        <img id="passwordToggle" class="popup-side-icon" src="../public/assets/eye.svg" alt="Показать пароль"/>
                    </button>
                </div>
                <div class="flex jc-fs">
                    <button type="button" class="ff-ur ab-text underline">Забыли пароль?</button>
                </div>
                <div id="passwordErrorPopup" class="ff-ur ar-text error-popup" role="alert"></div>
            </div>

            <div class="flex jc-fs">
                <div class="flex ai-c popup-cg">
                    <input id="agree_terms" type="checkbox" class="catalog-checkbox" checked aria-checked="true">
                    <label for="agree_terms" class="ff-ur dg3-text popup-description">Запомнить меня</label>
                </div>
            </div>
        </form>

        <div class="popup-footer flex jc-fe">
            <button id="goToSignupBtn" type="button" class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Регистрация</button>
            <button id="loginBtn" type="submit" class="ff-usb popup-btn bg-ab w-text popup-primary">Войти</button>
        </div>
    </div>
</section>