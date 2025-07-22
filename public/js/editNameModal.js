$(document).ready(function () {
    const $popup = $('#editNamePopup');
    const $popupContent = $popup.find('.popup-content');
    const $editBtn = $('#editNameBtn');
    const $nameInput = $('#editNameInput');
    const $surnameInput = $('#editSurnameInput');
    const $saveBtn = $('#editSaveBtn');

    const $successPopup = $('#editNamePopupSuccess');
    const $waitPopup = $('#editNamePopupWait');
    const $failPopup = $('#editNamePopupFailure');

    const $surnameError = $('#surnameError');
    const $nameError = $('#nameError');

    function formatSurname(surname) {
        return surname.replace(/[^A-Za-zА-Яа-яЁё-]/g, '')
            .replace(/\s+/g, '')
            .replace(/-{2,}/g, '-')
            .toLowerCase()
            .replace(/(^|\s|-)[a-zа-яё]/g, match => match.toUpperCase());
    }

    function formatName(name) {
        return name.replace(/[^A-Za-zА-Яа-яЁё]/g, '')
            .toLowerCase()
            .replace(/^[a-zа-яё]/, match => match.toUpperCase());
    }

    function validateSurname(surname) {
        return surname.length >= 2 && surname.length <= 36 && !/^-/.test(surname) && !/\s/.test(surname);
    }

    function validateName(name) {
        return name.length >= 2 && name.length <= 15 && /^[A-Za-zА-Яа-яЁё]+$/.test(name);
    }

    function showError($el, msg) {
        $el.text(msg).show();
    }

    function hideError($el) {
        $el.text('').hide();
    }

    function closeAllModals() {
        $('.popup-overlay').removeClass('active');
        $('.popup-content').removeClass('active');
    }

    function formatFullDateRussian(dateStr) {
        const months = [
            'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
            'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
        ];
        const date = new Date(dateStr);
        if (isNaN(date)) return "неизвестная дата";
    
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
    
        return `${day} ${month} ${year} года в ${hours}:${minutes}`;
    }
    

    $editBtn.on('click', function () {
        $.ajax({
            url: "../database/get-username.php",
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $nameInput.val(data.user_name);
                    $surnameInput.val(data.user_surname);
                    closeAllModals();
                    $popup.addClass('active');
                    $popupContent.addClass('active');
                }
            },
            error: function () {
                closeAllModals();
                $failPopup.addClass('active').find('.popup-content').addClass('active');
            }
        });
    });

    $surnameInput.on('input', function () {
        const val = formatSurname($(this).val());
        $(this).val(val);
        const valid = validateSurname(val);
        valid ? hideError($surnameError) : showError($surnameError, 'Фамилия должна содержать от 2 до 36 символов, без пробелов и дефиса в начале');
        $(this).toggleClass('error', !valid).toggleClass('valid', valid);
    });

    $nameInput.on('input', function () {
        const val = formatName($(this).val());
        $(this).val(val);
        const valid = validateName(val);
        valid ? hideError($nameError) : showError($nameError, 'Имя должно содержать от 2 до 15 букв, без пробелов');
        $(this).toggleClass('error', !valid).toggleClass('valid', valid);
    });

    $saveBtn.on('click', function () {
        const newName = $nameInput.val().trim();
        const newSurname = $surnameInput.val().trim();

        let isValid = true;
        if (!validateSurname(newSurname)) {
            showError($surnameError, 'Фамилия должна содержать от 2 до 36 символов, без пробелов и дефиса в начале');
            $surnameInput.addClass('error');
            isValid = false;
        } else hideError($surnameError);

        if (!validateName(newName)) {
            showError($nameError, 'Имя должно содержать от 2 до 15 букв, без пробелов');
            $nameInput.addClass('error');
            isValid = false;
        } else hideError($nameError);

        if (!isValid) return;

        $saveBtn.prop('disabled', true);
        $nameInput.prop('disabled', true);
        $surnameInput.prop('disabled', true);

        $.ajax({
            url: "../database/edit-username.php",
            method: 'POST',
            dataType: 'json',
            data: {
                user_name: newName,
                user_surname: newSurname
            },
            success: function (data) {
                closeAllModals();

                if (data.status === 'success' || data.status === 'wait') {
                    const dateText = data.next_change
                        ? `Изменения можно вносить не чаще чем раз в месяц. Следующее изменение персональных данных можно совершить не раньше чем ${formatFullDateRussian(data.next_change)}`
                        : "Не удалось получить дату следующего изменения.";

                    $('#editNameWaitDateText').text(dateText);

                    const $targetPopup = data.status === 'success' ? $successPopup : $waitPopup;
                    $targetPopup.addClass('active').find('.popup-content').addClass('active');

                    if (data.status === 'success') {
                        $successPopup.find('button').off('click').on('click', function () {
                            location.reload();
                        });
                    }
                } else if (data.status === 'no_changes') {
                    closeAllModals();
                } else {
                    $failPopup.addClass('active').find('.popup-content').addClass('active');
                }
            },
            error: function () {
                closeAllModals();
                $failPopup.addClass('active').find('.popup-content').addClass('active');
            },
            complete: function () {
                $saveBtn.prop('disabled', false);
                $nameInput.prop('disabled', false);
                $surnameInput.prop('disabled', false);
            }
        });
    });

    $('.popup-secondary, .popup-x, .popup-overlay').on('click', function (e) {
        if ($(e.target).hasClass('popup-overlay') || $(this).hasClass('popup-secondary') || $(this).hasClass('popup-x')) {
            closeAllModals();
        }
    });
});
