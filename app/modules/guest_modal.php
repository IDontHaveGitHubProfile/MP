<aside id="guestAlertPopup"
       class="popup-overlay flex jc-c ai-c"
       role="dialog"
       aria-modal="true"
       aria-labelledby="guestAlertTitle"
       aria-describedby="guestAlertDesc guestHint">
       
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="guestAlertTitle" class="ff-um dg3-text ta-l popup-title">Необходимо войти!</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="guestAlertDesc" class="ff-ur dg3-text ta-l popup-description">
                <strong>Полный доступ ко всем функциям доступен после входа в аккаунт.</strong>
            </p>

            <p id="guestHint" class="ff-ur dg3-text ta-l popup-description"></p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-dgray3 w-text popup-tertiary">Зарегистрироваться</button>
            <button class="ff-usb popup-btn bg-ab w-text popup-primary">Войти</button> 
        </div>
    </div>
</aside>