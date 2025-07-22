<section id="emailAlertPopup"
         class="popup-overlay flex jc-c ai-c"
         role="dialog"
         aria-modal="true"
         aria-labelledby="emailAlertTitle"
         aria-describedby="emailAlertDesc emailAlertNote">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="emailAlertTitle" class="ff-um dg3-text ta-l popup-title">Привяжите email</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="emailAlertDesc" class="ff-ur dg3-text ta-l popup-description">
                Пожалуйста, введите ваш адрес электронной почты для получения электронных чеков, рассылок и восстановления учётной записи.
            </p>

            <p id="emailAlertNote" class="ff-ur dg3-text ta-l popup-description">
                <strong>Мы вам вышлем письмо с подтверждением. Срок письма - 10 минут.</strong>
            </p>

            <input id="emailInput" class="ff-um popup-input" type="email" placeholder="Ваш email...">
            <div id="emailMessage" class="ff-ur dg3-text popup-description">Введите ваш email</div>

            <div class="flex jc-fs popup-checkbox">
                <div class="flex ai-c jc-sb popup-cg">
                    <input id="confirmNews" type="checkbox" class="catalog-checkbox" checked>
                    <label for="confirmNews" class="ff-ur dg3-text popup-description">
                        Я хочу получать рассылку о самых горячих новинках, акциях и специальных предложениях
                    </label>
                </div>
            </div>
        </div>

        <div class="popup-footer flex jc-fe">
            <button id="sendEmailBtn" class="ff-usb popup-btn bg-ab w-text popup-primary">Отправить код</button>
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Не сейчас</button>
        </div>
    </div>
</section>


<aside id="emailFailurePopup"
       class="popup-overlay flex jc-c ai-c"
       role="dialog"
       aria-modal="true"
       aria-labelledby="emailFailureTitle"
       aria-describedby="emailFailureDesc">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="emailFailureTitle" class="ff-um dg3-text ta-l popup-title">Ошибка сервера</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="emailFailureDesc" class="ff-ur dg3-text ta-l popup-description">
                Произошла ошибка отправки письма. Пожалуйста, попробуйте позже или обратитесь в службу поддержки.
            </p>
        </div>

        <div class="popup-footer flex jc-fe">

            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</aside>


<aside id="emailWaitPopup"
       class="popup-overlay flex jc-c ai-c"
       role="dialog"
       aria-modal="true"
       aria-labelledby="emailWaitTitle"
       aria-describedby="emailWaitDesc">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="emailWaitTitle" class="ff-um dg3-text ta-l popup-title">Проверьте почту</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="emailWaitDesc" class="ff-ur dg3-text ta-l popup-description">
                Мы выслали вам письмо с подтверждением. Не забудьте проверить папку "Спам".
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</aside>