<aside id="passwordEditPopup" 
        class="popup-overlay flex jc-c ai-c" 
        role="dialog" 
        aria-modal="true" 
        aria-labelledby="passwordEditPopupTitle" 
        aria-describedby="passwordEditPopupDescription" 
        aria-hidden="true">
        
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="passwordEditPopupTitle" class="ff-um dg3-text ta-l popup-title">Изменение пароля</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="passwordEditPopupDescription" class="ff-ur dg3-text ta-l popup-description">
                Пожалуйста, введите текущий пароль и новый пароль для обновления.
            </p>
            <input id="currentPassword" class="ff-um popup-input" type="password" placeholder="Текущий пароль" aria-label="Текущий пароль">
            <input id="newPassword" class="ff-um popup-input" type="password" placeholder="Новый пароль" aria-label="Новый пароль">
            <input id="confirmNewPassword" class="ff-um popup-input" type="password" placeholder="Подтвердите пароль" aria-label="Подтверждение пароля">
        </div>

        <div class="popup-footer flex jc-fe">
            <button id="editPasswordBtn" class="ff-usb popup-btn bg-ab w-text popup-primary" aria-label="Изменить пароль">Изменить</button> 
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder" aria-label="Отмена">Отмена</button>
        </div>
    </div>
</aside>

<section id="passwordFailurePopup" 
        class="popup-overlay flex jc-c ai-c" 
        role="dialog" 
        aria-modal="true" 
        aria-labelledby="passwordFailurePopupTitle" 
        aria-describedby="passwordFailurePopupDescription" 
        aria-hidden="true">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="passwordFailurePopupTitle" class="ff-um dg3-text ta-l popup-title">Ошибка сервера</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="passwordFailurePopupDescription" class="ff-ur dg3-text ta-l popup-description">
                Произошла ошибка изменения пароля. Пожалуйста, попробуйте позже
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder" aria-label="Отмена">Отмена</button>
        </div>
    </div>
</section>

<section id="passwordSuccessPopup" 
        class="popup-overlay flex jc-c ai-c" 
        role="dialog" 
        aria-modal="true" 
        aria-labelledby="passwordSuccessPopupTitle"
        aria-describedby="passwordSuccessPopupDescription" 
        aria-hidden="true">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="passwordSuccessPopupTitle" class="ff-um dg3-text ta-l popup-title">Пароль изменён</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="passwordSuccessPopupDescription" class="ff-ur dg3-text ta-l popup-description">
                Ваш пароль успешно изменён.
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder" aria-label="Отмена">Отмена</button>
        </div>
    </div>
</section>

<section id="passwordWaitPopup" 
        class="popup-overlay flex jc-c ai-c" 
        role="dialog" 
        aria-modal="true" 
        aria-labelledby="passwordWaitPopupTitle" 
        aria-describedby="passwordWaitPopupDescription" 
        aria-hidden="true">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="passwordWaitPopupTitle" class="ff-um dg3-text ta-l popup-title">Ошибка изменения</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="passwordWaitPopupDescription" class="ff-ur dg3-text ta-l popup-description">
                Произошла ошибка изменения пароля. Изменения можно вносить не чаще чем раз в 2 недели.
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder" aria-label="Отмена">Отмена</button>
        </div>
    </div>
</section>
