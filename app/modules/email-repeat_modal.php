<aside id="emailRepeatPopup"
       class="popup-overlay flex jc-c ai-c"
       role="dialog"
       aria-modal="true"
       aria-labelledby="emailRepeatTitle"
       aria-describedby="emailRepeatDesc">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="emailRepeatTitle" class="ff-um dg3-text ta-l popup-title">Проверьте почту</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" role="button" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="emailRepeatDesc" class="ff-ur dg3-text ta-l popup-description">
                Мы уже выслали вам письмо на почту <span class="email-placeholder">[email]</span>. Электронные письма с подтверждением могут приходить с задержкой до 15 минут, либо могут попасть в папку "Спам". Если прошло более 15 минут, а письмо так и не пришло, то попробуйте ещё раз или свяжитесь с нами.
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</aside>
