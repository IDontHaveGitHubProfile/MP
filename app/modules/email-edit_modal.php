<div id="emailEditPopup" class="popup-overlay flex jc-c ai-c">
    <div class="popup-content">
        <div class="popup-header popup-container-y">
            <div class="popup-container-x flex ai-c jc-sb">
                <p class="ff-um dg3-text ta-l popup-title" id="popupTitle">Изменение email</p>
                <div class="popup-x flex ai-c jc-c" tabindex="0">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="popup-container-y">
            <div class="popup-container-x">
                <div class="popup-body flex fd-c">
                    <p class="ff-ur dg3-text ta-l popup-description" id="popupDescription">
                        Введите новый адрес электронной почты для подтверждения изменения.
                    </p>
                    <p class="ff-ur dg3-text ta-l popup-description" id="popupDescription">
                        <strong>Вы можете поменять свой email только раз в месяц</strong>
                    </p>

                  
                    
                    <input id="emailEditInput" class="ff-um popup-input" type="email" placeholder="Ваш email...">
                    <div id="emailEditMessage" class="ff-ur" style="display: none; margin-bottom: 10px;"></div>


                </div>

                <div class="popup-footer flex jc-fe">
                    <button id="editEmailBtn" class="ff-usb popup-btn bg-ab w-text popup-primary">Отправить код</button> 
                    <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Не сейчас</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="emailFailurePopup" class="popup-overlay flex jc-c ai-c">
    <div class="popup-content">
        <div class="popup-header popup-container-y">
            <div class="popup-container-x flex ai-c jc-sb">
                <p class="ff-um dg3-text ta-l popup-title" id="popupTitle">Ошибка сервера</p>
                <div class="popup-x flex ai-c jc-c" tabindex="0">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="popup-container-y">
            <div class="popup-container-x">
                <div class="popup-body flex fd-c">
                    <p class="ff-ur dg3-text ta-l popup-description" id="popupDescription">
                        Произошла ошибка привязки почты к учётной записи. Пожалуйста, попробуйте позже
                    </p>
                    

                  
                
                </div>

                <div class="popup-footer flex jc-fe">
                    <button id="sendEmailBtn" class="ff-usb popup-btn bg-ab w-text popup-primary">
                        <span class="popup-next">Отправить код</span>
                        <span class="popup-spinner">Отправить код</span>
                        
                    </button> 
                    <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="emailWaitPopup" class="popup-overlay flex jc-c ai-c">
    <div class="popup-content">
        <div class="popup-header popup-container-y">
            <div class="popup-container-x flex ai-c jc-sb">
                <p class="ff-um dg3-text ta-l popup-title" id="popupTitle">Проверьте почту</p>
                <div class="popup-x flex ai-c jc-c" tabindex="0">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="popup-container-y">
            <div class="popup-container-x">
                <div class="popup-body flex fd-c">
                    <p class="ff-ur dg3-text ta-l popup-description" id="popupDescription">
                        Мы выслали вам письмо с подтверждением. Не забудьте проверить папку "Спам"
                    </p>
                    
                </div>

                <div class="popup-footer flex jc-fe">
                    <button class="ff-usb popup-btn bg-w dg3-text popup-secondary dg3-bborder">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</div>