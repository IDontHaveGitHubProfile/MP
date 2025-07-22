<div class="flex fd-c jc-c ta-c form-container">
    <p class="form-title section-title ff-usb dg3-text">Регистрация</p>
    <p class="form-description ff-ur dg3-text">Создайте аккаунт, чтобы потом авторизоваться и <br>получить доступ к покупкам и заказам</p>
    <form id="registration-form" class="flex fd-c form form-rg" method="POST">

        <div class="flex fd-c input-rg">
            <label for="user_surname" class="input-subtext ff-ur dg3-text ta-l">Фамилия</label>
            <input id="user_surname" class="form-input g2-text ff-ur" type="text" name="user_surname" maxlength="64" autocomplete="family-name">
            <span class="error-message ff-ur ar-text ta-l" id="surname-error"></span>
        </div>

        <div class="flex fd-c input-rg">
            <label for="user_name" class="input-subtext ff-ur dg3-text ta-l">Имя</label>
            <input id="user_name" class="form-input g2-text ff-ur" type="text" name="user_name" autocomplete="given-name">
            <span class="error-message ff-ur ar-text ta-l" id="name-error"></span>
        </div>

        <div class="flex fd-c input-rg">
            <label for="user_phone" class="input-subtext ff-ur dg3-text ta-l">Телефон</label>
            <div class="phone-container">
                <input id="user_phone" class="form-input g2-text ff-ur" type="tel" name="user_phone" inputmode="tel" placeholder="Введите номер телефона" autocomplete="tel" autocorrect="off" autocapitalize="off">
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
            <span class="error-message ff-ur ar-text ta-l" id="password-error"></span>
        </div>

        <div class="flex fd-c input-rg">
            <label for="user_password-confirm" class="input-subtext ff-ur dg3-text ta-l">Подтвердите пароль</label>
            <div class="password-container">
            <input id="user_password_confirm" class="form-input g2-text ff-ur" type="password" name="user_password_confirm" autocomplete="new-password" autocorrect="off" autocapitalize="off" oncopy="return false" onpaste="return false">
                <span class="password-toggle" id="toggle-password-confirm">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <span class="error-message ff-ur ar-text ta-l" id="confirm-password-error"></span>
        </div>

        <div class="flex fd-c input-rg">
            <div class="flex ai-c ta-l form-box">
                <input type="checkbox" name="user_terms" id="user_terms" class="box-v">
                <p class="ff-ur dg3-text">Я согласен(на) с <a href="#" class="ff-usb dg3-text underline">условиями пользования</a></p>
            </div>
            <span class="error-message ff-ur ar-text ta-l" id="terms-error"></span>
        </div>

        <button type="submit" class="reg-btn form-button ff-usb">Зарегистрироваться</button>
        <p class="dg3-text ff-ur btn-subscription">Уже есть аккаунт? <a href="index.php?page=login" class="ff-usb ab-text underline">Войдите!</a></p>
    </form>
</div>

