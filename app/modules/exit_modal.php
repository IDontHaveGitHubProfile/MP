<section id="logOutPopup"
         class="popup-overlay flex jc-c ai-c"
         role="dialog"
         aria-modal="true"
         aria-labelledby="logOutTitle"
         aria-describedby="logOutDesc">

    <div class="popup-content">
        <div class="popup-header flex ai-c jc-sb">
            <p id="logOutTitle" class="ff-um dg3-text ta-l popup-title">Завершение сеанса</p>
            <button class="popup-x flex ai-c jc-c" tabindex="0" aria-label="Закрыть окно">
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="popup-body flex fd-c">
            <p id="logOutDesc" class="ff-ur dg3-text ta-l popup-description">
                Вы уверены, что хотите выйти из своей учётной записи?
            </p>
        </div>

        <div class="popup-footer flex jc-fe">
            <button id="logOutBtn" class="ff-usb popup-btn bg-ab w-text popup-primary">Выйти</button>
            <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Не сейчас</button>
        </div>
    </div>
</section>
