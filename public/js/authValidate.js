$(document).ready(function () {
    if (sessionStorage.getItem('showEmailModal') === '1') {
        showEmailModal();
        sessionStorage.removeItem('showEmailModal');
    }

    function formatPhone(input) {
        let numbers = input.replace(/\D/g, '');
        if (numbers.startsWith('8')) numbers = '7' + numbers.substring(1);
        if (numbers.length > 11) numbers = numbers.substring(0, 11);
        let formattedPhone = '+7';
        if (numbers.length > 1) formattedPhone += ' ' + numbers.substring(1, 4);
        if (numbers.length > 4) formattedPhone += ' ' + numbers.substring(4, 7);
        if (numbers.length > 7) formattedPhone += ' ' + numbers.substring(7, 9);
        if (numbers.length > 9) formattedPhone += ' ' + numbers.substring(9, 11);
        return formattedPhone;
    }

    function showError(element, message) {
        element.text(message).addClass('show');
    }

    function hideError(element) {
        element.removeClass('show').text('');
    }

    function resetErrors() {
        $('.error-message').each(function () {
            hideError($(this));
        });
        $('.form-input').removeClass('error valid');
    }

    function showEmailModal() {
        const modal = $('#emailAlertPopup');
        if (modal.length === 0) return;
        $('html').addClass('popup-open');
        modal.addClass('active');
        modal.find('.popup-content').addClass('active');
        modal.find('.popup-x, .popup-secondary').on('click', closeEmailModal);
        modal.on('click', function(e) {
            if (e.target === this) closeEmailModal();
        });
        $('#sendEmailBtn').on('click', function() {
            alert('Функция отправки кода подтверждения будет реализована позже');
        });
    }

    function closeEmailModal() {
        $('#emailAlertPopup').removeClass('active');
        $('#emailAlertPopup .popup-content').removeClass('active');
        $('html').removeClass('popup-open');
    }

    $('#user_phone').on('input', function () {
        const input = $(this).val();
        let phone = formatPhone(input);
        const phoneWithoutFormatting = phone.replace(/\D/g, '');
        let error = phoneWithoutFormatting.length !== 11 ? 'Некорректный номер' : '';
        $(this).val(phone);
        error ? showError($('#phone-error'), error) : hideError($('#phone-error'));
        $(this).toggleClass('error', !!error).toggleClass('valid', !error);
    });

    $('#login-form').submit(function (event) {
        event.preventDefault();
        resetErrors();
        let isValid = true;
        const phone = formatPhone($('#user_phone').val());
        const phoneWithoutFormatting = phone.replace(/\D/g, '');
        if (phoneWithoutFormatting.length !== 11) {
            isValid = false;
            showError($('#phone-error'), 'Введите 11-значный номер');
            $('#user_phone').addClass('error');
        }
        const password = $('#user_password').val().trim();
        if (!password) {
            isValid = false;
            showError($('#password-error'), 'Введите пароль');
            $('#user_password').addClass('error');
        }
        if (isValid) {
            $.ajax({
                url: "../database/authoriz.php",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        if (data.require_email_verification) {
                            sessionStorage.setItem('showEmailModal', '1');
                            window.location.href = "index.php?page=profile";
                        } else {
                            window.location.href = data.redirect ? data.redirect : "index.php?page=profile";
                        }
                    } else {
                        showError($('#password-error'), data.message || 'Ошибка авторизации');
                    }
                },
                error: function() {
                    showError($('#password-error'), 'Ошибка соединения с сервером');
                }
            });
        }
    });
    $('#user_phone').val('+7');
});