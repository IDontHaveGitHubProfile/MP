$(document).ready(function() {
    // Элементы DOM
    const loginPopup = $('#loginPopup');
    const loginBtnModal = $('#loginBtnModal');
    const closeModalBtn = $('#popupCloseBtn');
    const overlay = $('.popup-overlay');
    const phoneInput = $('#phoneInput');
    const passwordInput = $('#passwordInput');
    const loginBtn = $('#loginBtn');
    const loginForm = $('#loginPopupForm');
    const phoneError = $('#phoneErrorPopup');
    const passwordError = $('#passwordErrorPopup');
    const switchToEmailBtn = $('#switchToEmail');
    const clearInputBtn = $('#popup-clear');
    
    // Состояние
    let isUsingPhone = true;
    let phoneTouched = false;
    let passwordTouched = false;

    // ========== ОСНОВНЫЕ ФУНКЦИИ ========== //

    function openModal() {
        $('html').addClass('popup-open');
        loginPopup.addClass('active');
        loginPopup.find('.popup-content').addClass('active');
        loginPopup.find('.popup-overlay').addClass('active');
        phoneInput.focus();
    }

    function closeModal() {
        loginPopup.removeClass('active');
        loginPopup.find('.popup-content').removeClass('active');
        loginPopup.find('.popup-overlay').removeClass('active');
        $('html').removeClass('popup-open');
    }

    function showError(element, message) {
        element.text(message).addClass('show');
    }

    function hideError(element) {
        element.removeClass('show').text(''); 
    }

    function resetErrors() {
        hideError(phoneError);
        hideError(passwordError);
        phoneInput.removeClass('error valid');
        passwordInput.removeClass('error valid');
    }

    function validatePhoneOrEmail(showErrorNow = false) {
        const value = phoneInput.val().trim();
        if (isUsingPhone) {
            const raw = value.replace(/\D/g, '');
            if (raw.length !== 11) {
                if (showErrorNow) showError(phoneError, 'Некорректный номер телефона');
                phoneInput.addClass('error').removeClass('valid');
                return false;
            } else {
                hideError(phoneError);
                phoneInput.removeClass('error').addClass('valid');
                return true;
            }
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                if (showErrorNow) showError(phoneError, 'Некорректный email');
                phoneInput.addClass('error').removeClass('valid');
                return false;
            } else {
                hideError(phoneError);
                phoneInput.removeClass('error').addClass('valid');
                return true;
            }
        }
    }

    function validatePassword(showErrorNow = false) {
        const password = passwordInput.val().trim();
        if (!password) {
            if (showErrorNow) showError(passwordError, 'Введите пароль');
            passwordInput.addClass('error').removeClass('valid');
            return false;
        } else {
            hideError(passwordError);
            passwordInput.removeClass('error').addClass('valid');
            return true;
        }
    }

    function updateLoginButtonState() {
        const isInputValid = validatePhoneOrEmail();
        const isPasswordValid = validatePassword();
        loginBtn.prop('disabled', !(isInputValid && isPasswordValid));
    }

    function clearPhoneInput() {
        phoneInput.val('');
        clearInputBtn.hide();
        resetErrors();
        updateLoginButtonState();
        phoneInput.focus();
    }

    // ========== ИНИЦИАЛИЗАЦИЯ МАСКИ ========== //

    function initPhoneMask() {
        Inputmask({
            mask: '+7 (999) 999-99-99',
            clearIncomplete: false,
            showMaskOnHover: false,
            greedy: false,
            placeholder: '_',
            definitions: {
                '9': {
                    validator: '[0-9]',
                    cardinality: 1
                }
            },
            onBeforeMask: function(value) {
                return value.replace(/\D/g, '');
            },
            onBeforePaste: function(pastedValue) {
                return pastedValue.replace(/\D/g, '');
            }
        }).mask(phoneInput);

        phoneInput.on('keydown', function(e) {
            const value = $(this).val();
            const selectionStart = this.selectionStart;
            const selectionEnd = this.selectionEnd;

            // Если курсор на одном месте, и это клавиша удаления, то корректируем
            if (e.key === 'Backspace' || e.key === 'Delete') {
                const char = value.charAt(selectionStart);
                if (/\d/.test(char)) {
                    e.preventDefault();
                    const newValue = value.slice(0, selectionStart) + '_' + value.slice(selectionStart + 1);
                    $(this).val(newValue);
                    this.setSelectionRange(selectionStart, selectionStart); // Сохраняем курсор на месте
                }
            }
        });
    }

    // ========== ОБРАБОТЧИКИ СОБЫТИЙ ========== //

    function bindEvents() {
        // Открытие/закрытие модалки
        loginBtnModal.on('click', openModal);
        closeModalBtn.on('click', closeModal);
        overlay.on('click', function(e) {
            if ($(e.target).is(overlay)) closeModal();
        });

        // Валидация при вводе
        phoneInput.on('input blur', function() {
            phoneTouched = true;
            validatePhoneOrEmail();
            updateLoginButtonState();
            clearInputBtn.toggle(!!$(this).val().trim());
        });

        passwordInput.on('input blur', function() {
            passwordTouched = true;
            validatePassword();
            updateLoginButtonState();
        });

        // Очистка поля
        clearInputBtn.on('click', clearPhoneInput);

        // Переключение телефон/email
        switchToEmailBtn.on('click', function(e) {
            e.preventDefault();
            isUsingPhone = !isUsingPhone;

            phoneInput.attr('type', isUsingPhone ? 'tel' : 'email')
                     .attr('placeholder', isUsingPhone ? 'Телефон' : 'Email')
                     .val('');

            if (isUsingPhone && typeof Inputmask !== 'undefined') {
                initPhoneMask();
            } else {
                phoneInput.inputmask('remove');
            }

            clearInputBtn.hide();
            resetErrors();
            updateLoginButtonState();
        });

        // Отправка формы
        loginForm.on('submit', function(e) {
            e.preventDefault();
            
            const isInputValid = validatePhoneOrEmail(true);
            const isPasswordValid = validatePassword(true);
            
            if (isInputValid && isPasswordValid) {
                submitLoginForm();
            }
        });
    }

    // ========== ОТПРАВКА ФОРМЫ ========== //

    function submitLoginForm() {
        loginBtn.prop('disabled', true).text('Вход...');

        $.ajax({
            url: '../database/authoriz.php',
            type: 'POST',
            data: loginForm.serialize(),
            dataType: 'json',
            success: function(response) {
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        if (data.require_email_verification) {
                            sessionStorage.setItem('showEmailModal', '1');
                            window.location.href = "index.php?page=profile";
                        } else {
                            window.location.href = data.redirect || "index.php?page=profile";
                        }
                    } else {
                        showError(passwordError, data.message || 'Ошибка авторизации');
                    }
                } catch (e) {
                    console.error('Ошибка парсинга ответа:', e);
                    showError(passwordError, 'Ошибка сервера');
                }
            },
            error: function(xhr) {
                showError(passwordError, xhr.statusText || 'Ошибка соединения');
            },
            complete: function() {
                loginBtn.prop('disabled', false).text('Войти');
            }
        });
    }

    // ========== ИНИЦИАЛИЗАЦИЯ ========== //

    if (typeof Inputmask !== 'undefined') {
        initPhoneMask();
    } else {
        console.error('Inputmask не загружен');
        phoneInput.attr('placeholder', 'Телефон (без маски)');
    }

    bindEvents();
    updateLoginButtonState();
    clearInputBtn.hide();
});
