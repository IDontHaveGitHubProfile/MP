<div class="flex fd-c jc-c ta-c form-container">
    <p class="form-title section-title ff-usb dg3-text">Авторизация</p>
    <p class="form-description ff-ur dg3-text">Введите данные чтобы войти</p>
    <form id="login-form" class="flex fd-c form form-rg" method="POST">

        <div class="flex fd-c input-rg">
            <label for="user_phone" class="input-subtext ff-ur dg3-text ta-l">Телефон</label>
            <div class="phone-container">
                <input id="user_phone" class="form-input g2-text ff-ur" type="tel" name="user_phone" autocomplete="off">
                <span class="phone-clear" id="clear-phone">
                    <i class="fa fa-times"></i>
                </span>
            </div>
            <span class="error-message ff-ur ar-text ta-l" id="phone-error"></span>
        </div>

        <div class="flex fd-c input-rg">
            <label for="user_password" class="input-subtext ff-ur dg3-text ta-l">Пароль</label>
            <div class="password-container">
                <input id="user_password" class="form-input g2-text ff-ur" type="password" name="user_password" autocomplete="new-password" autocorrect="off" autocapitalize="off" oncopy="return false" onpaste="return false">
                <span class="password-toggle" id="toggle-password">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <div class="flex jc-fs">
                <a href="index.php?page=password_reset" class=" ff-usb ab-text underline ta-l">Забыли пароль?</a>
            </div>
            <span class="error-message ff-ur ar-text ta-l" id="password-error"></span>
        </div>

        <div class="flex ai-c form-box">
            <input type="checkbox" name="agree_terms" id="agree_terms" class="box-v">
            <p class="ff-ur dg3-text">Запомнить меня</p>
        </div>

        <button type="submit" class="auth-btn form-button ff-usb">Войти</button>
        <p class="dg3-text ff-ur btn-subscription">Ещё нет аккаунта? <a href="index.php?page=signup" class="ff-usb ab-text underline">Создайте!</a></p>
    </form>
</div>
