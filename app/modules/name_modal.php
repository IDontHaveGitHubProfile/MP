<section id="editNamePopup"
         class="popup-overlay flex jc-c ai-c"
         role="dialog"
         aria-modal="true"
         aria-labelledby="editNameTitle"
         aria-describedby="editNameDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="editNameTitle" class="ff-um dg3-text ta-l popup-title">Изменение данных</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="editNameDesc" class="ff-ur dg3-text ta-l popup-description">
                Изменение имени и фамилии. Изменения можно вносить не чаще чем раз в месяц
            </p>
            <input id="editNameInput" class="ff-um popup-input" type="text" placeholder="Фамилия" aria-label="Фамилия">
            <input id="editSurnameInput" class="ff-um popup-input" type="text" placeholder="Имя" aria-label="Имя">
        </div>

        <div class="popup-footer flex jc-fe">
            <button id="editSaveBtn" class="ff-usb popup-btn bg-ab w-text popup-primary">Сохранить</button>
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</section>

<aside id="editNamePopupSuccess"
       class="popup-overlay flex jc-c ai-c"
       role="alertdialog"
       aria-modal="true"
       aria-labelledby="editNameSuccessTitle"
       aria-describedby="editNameSuccessDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="editNameSuccessTitle" class="ff-um dg3-text ta-l popup-title">Данные изменены</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="editNameSuccessDesc" class="ff-ur dg3-text ta-l popup-description">
                Имя и фамилия успешно изменены
            </p>
            <p class="ff-ur dg3-text ta-l popup-description">
                В следующий раз вы можете изменить имя и фамилию не раньше чем <span id="nextEditDate">[дата]</span>
            </p>
        </div>

        <div class="popup-footer">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</aside>

<aside id="editNamePopupWait"
       class="popup-overlay flex jc-c ai-c"
       role="alertdialog"
       aria-modal="true"
       aria-labelledby="editNameWaitTitle"
       aria-describedby="editNameWaitDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="editNameWaitTitle" class="ff-um dg3-text ta-l popup-title">Ошибка изменения</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="editNameWaitDesc" class="ff-ur dg3-text ta-l popup-description">
                Произошла ошибка изменения персональных данных
            </p>
            <p class="ff-ur dg3-text ta-l popup-description" id="editNameWaitDateText">...</p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</aside>

<aside id="editNamePopupFailure"
       class="popup-overlay flex jc-c ai-c"
       role="alertdialog"
       aria-modal="true"
       aria-labelledby="editNameFailureTitle"
       aria-describedby="editNameFailureDesc">
    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="editNameFailureTitle" class="ff-um dg3-text ta-l popup-title">Ошибка сервера</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body">
            <p id="editNameFailureDesc" class="ff-ur dg3-text ta-l popup-description">
                Произошла ошибка изменения персональных данных. Пожалуйста, попробуйте позже
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
        </div>
    </div>
</aside>
