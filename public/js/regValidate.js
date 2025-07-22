$(document).ready(function () {
    console.log('Registration form validation initialized');

    const OPERATOR_CONFIG = {
        main: {
            mts: [981, 982, 983, 984, 985, 986, 987, 989, 913, 914, 916, 910, 917],
            megafon: [920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 933, 936, 937, 938, 939, 912],
            beeline: [903, 905, 906, 909, 961, 962, 963, 964, 965, 966, 967, 968, 969],
            tele2: [900, 904, 901, 902, 950, 951, 952, 953, 954, 955, 956]
        },
        virtual: {
            yota: [996, 999],
            sber: [958, 959],
            tinkoff: [958, 959],
            rostelecom: [495, 499, 800, 977],
            skylink: [993, 994],
            wifire: [958, 959],
            other: [931, 932, 934, 935, 940, 941, 942, 943, 944, 945, 946, 947, 948, 949]
        },
        blocked: {
            emergency: [101, 102, 103, 104, 112],
            government: [498, 499],
            corporate: [800, 804, 808]
        }
    };

    // Вспомогательные функции
    function showError(element, message) {
        console.log('Showing error:', message);
        element.text(message).addClass('show');
    }

    function hideError(element) {
        element.removeClass('show').text('');
    }

    function resetErrors() {
        console.log('Resetting all errors');
        $('.error-message').each(function () {
            hideError($(this));
        });
        $('.form-input').removeClass('error valid');
    }

    // Функции валидации имени и фамилии
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

    function validateName(name) {
        return !/\s/.test(name) && /^[A-Za-zА-Яа-яЁё]+$/.test(name);
    }

    // Обработчики для имени и фамилии
    $('#user_name').on('input', function () {
        let input = $(this).val();
        const formatted = formatName(input);
        $(this).val(formatted);

        let error = '';
        if (formatted.length < 2) error = 'Имя должно содержать минимум 2 символа';
        else if (formatted.length > 15) error = 'Имя не может быть длиннее 15 символов';
        else if (!validateName(formatted)) error = 'Имя должно состоять из одного слова (только буквы)';

        if (error) {
            showError($('#name-error'), error);
            $(this).addClass('error').removeClass('valid');
        } else {
            hideError($('#name-error'));
            $(this).removeClass('error').addClass('valid');
        }
    });

    $('#user_surname').on('input', function () {
        let input = $(this).val();
        const formatted = formatSurname(input);
        $(this).val(formatted);

        let error = '';
        if (formatted.length < 2) error = 'Фамилия должна содержать минимум 2 символа';
        else if (formatted.length > 36) error = 'Фамилия не может быть длиннее 36 символов';
        else if (/^-/.test(formatted)) error = 'Дефис не может быть в начале фамилии';
        else if (/\s/.test(formatted)) error = 'Пробелы в фамилии не разрешены';

        if (error) {
            showError($('#surname-error'), error);
            $(this).addClass('error').removeClass('valid');
        } else {
            hideError($('#surname-error'));
            $(this).removeClass('error').addClass('valid');
        }
    });

    function formatPhone(inputValue) {
        const digits = [];
        for (let i = 0; i < inputValue.length; i++) {
            const ch = inputValue[i];
            if (/\d/.test(ch)) digits.push(ch);
        }
    
        // Удалим первую цифру, если она не 7 (для +7 уже есть в начале)
        if (digits[0] === '8') digits[0] = '7';
        if (digits[0] !== '7') digits.unshift('7');
    
        // Ограничим до 11 цифр, из них после +7 — только 10
        digits.splice(11);
    
        let formatted = '+7';
        if (digits.length > 1) formatted += ' ' + digits.slice(1, 4).join('');
        if (digits.length > 4) formatted += ' ' + digits.slice(4, 7).join('');
        if (digits.length > 7) formatted += ' ' + digits.slice(7, 9).join('');
        if (digits.length > 9) formatted += ' ' + digits.slice(9, 11).join('');
    
        return formatted;
    }
    
    
    

    function getCleanPhone(phone) {
        const digits = [];
        for (let i = 0; i < phone.length; i++) {
            const ch = phone[i];
            if (/\d/.test(ch)) digits.push(ch);
        }
        return digits.slice(0, 11).join('');
    }
    

    function validateOperator(phone) {
        const cleanPhone = getCleanPhone(phone);
        const code = cleanPhone.substring(1, 4);
        let valid = false;
        let reason = '';
    
        for (const operator in OPERATOR_CONFIG.main) {
            if (OPERATOR_CONFIG.main[operator].includes(Number(code))) {
                valid = true;
                break;
            }
        }
    
        if (!valid) {
            reason = 'Номер не принадлежит ни одному из известных операторов';
        }
    
        return { valid, reason };
    }

    $('#user_phone').on('input', function () {
        const inputVal = this.value;
        const formatted = formatPhone(inputVal);
        this.value = formatted;
    
        const cleanPhone = getCleanPhone(formatted);
        let error = '';
    
        if (cleanPhone.length < 11) {
            error = 'Номер должен содержать 10 цифр после +7';
        } else {
            const operatorValidation = validateOperator(cleanPhone);
            if (!operatorValidation.valid) {
                error = operatorValidation.reason;
            }
        }
    
        if (error) {
            showError($('#phone-error'), error);
            $(this).addClass('error').removeClass('valid');
        } else {
            hideError($('#phone-error'));
            $(this).removeClass('error').addClass('valid');
        }
    });
    

    // Функции валидации пароля
    function validatePasswordStrength(password) {
        let strength = 0;
        let error = '';

        if (password.length < 8) {
            error = 'Пароль должен содержать минимум 8 символов';
        } else if (password.length > 16) {
            error = 'Пароль не может быть длиннее 16 символов';
        } else {
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            if (/[^a-zA-Z0-9!@#$%^&*()_+={}|\[\]\\:";'<>?,./`~]/.test(password)) {
                error = 'Пароль должен содержать только латинские буквы, цифры и специальные символы';
            }
        }

        if (error) return { strength: 0, error };
        
        return {
            strength,
            error: strength === 1 ? 'Ваш пароль слишком слабый' :
                  strength === 2 ? 'Средний пароль' :
                  strength === 3 ? 'Хороший пароль' :
                  strength === 4 ? 'Сильный пароль' :
                  'Пароль не соответствует требованиям'
        };
    }

    // Обработчик пароля
    $('#user_password').on('input', function () {
        const password = $(this).val();
        const result = validatePasswordStrength(password);
        const passwordToggle = $('#toggle-password');
        const errorElement = $('#password-error');

        $(this).removeClass('error medium strong');
        passwordToggle.removeClass('error password-toggle-medium password-toggle-strong');
        errorElement.removeClass('error medium strong');

        if (result.strength === 1) {
            $(this).addClass('error');
            passwordToggle.addClass('error');
            errorElement.addClass('error');
        } else if (result.strength === 2) {
            $(this).addClass('medium');
            passwordToggle.addClass('password-toggle-medium');
            errorElement.addClass('medium');
        } else if (result.strength >= 3) {
            $(this).addClass('strong');
            passwordToggle.addClass('password-toggle-strong');
            errorElement.addClass('strong');
        }

        showError(errorElement, result.error);
        $(this).toggleClass('valid', !result.error && password.length > 0);
    });

    // Подтверждение пароля
    $('#user_password_confirm').on('input', function () {
        let confirmPassword = $(this).val();
        let password = $('#user_password').val();
        let error = '';
        
        if (confirmPassword.length === 0) {
            error = 'Вы пропустили это поле';
        } else if (confirmPassword !== password) {
            error = 'Пароли не совпадают';
        }
        
        if (error) {
            showError($('#confirm-password-error'), error);
            $(this).addClass('error').removeClass('valid');
        } else {
            hideError($('#confirm-password-error'));
            $(this).removeClass('error').addClass('valid');
        }
    });

    // Отправка формы
    $('#registration-form').submit(function (event) {
        event.preventDefault();
        console.log('Form submission started');
        resetErrors();
        let isValid = true;

        // Проверка всех полей
        const fields = [
            { id: 'user_surname', errorId: 'surname-error', message: 'Вы пропустили это поле' },
            { id: 'user_name', errorId: 'name-error', message: 'Вы пропустили это поле' },
            { id: 'user_phone', errorId: 'phone-error', message: 'Вы пропустили это поле', 
              validator: val => formatPhone(val).length >= 2 },
            { id: 'user_password', errorId: 'password-error', message: 'Вы пропустили это поле' },
            { id: 'user_password_confirm', errorId: 'confirm-password-error', message: 'Вы пропустили это поле' }
        ];

        fields.forEach(field => {
            const value = $(`#${field.id}`).val().trim();
            if (!value || (field.validator && !field.validator(value))) {
                isValid = false;
                showError($(`#${field.errorId}`), field.message);
                $(`#${field.id}`).addClass('error');
                console.log(`Validation failed for ${field.id}`);
            }
        });

        if (!$('#user_terms').prop('checked')) {
            isValid = false;
            showError($('#terms-error'), 'Вы должны согласиться с условиями использования');
            $('#user_terms').addClass('error');
            console.log('Terms not accepted');
        }

        if (isValid) {
            console.log('All fields valid, sending AJAX request');
            $.ajax({
                url: "../database/register.php",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json', // Убедитесь, что указан тип данных
                success: function(response) {
                    console.log('Server response:', response);
                    if (response.success) {
                        console.log('Registration successful, redirecting...');
                        
                        if (response.show_reg_success) {
                            sessionStorage.setItem('showRegSuccessModal', '1');
                        }
                        
                        window.location.href = response.redirect || "index.php?page=login";
                    } else if (response.errors) {
                        console.log('Server validation errors:', response.errors);
                        Object.entries(response.errors).forEach(([field, msg]) => {
                            const errorField = field === 'user_phone' ? 'phone' : 
                                             field === 'user_password_confirm' ? 'confirm-password' : 
                                             field.split('_')[1];
                            showError($(`#${errorField}-error`), msg);
                            $(`#${field}`).addClass('error');
                        });
                    } else if (response.message) {
                        // Показываем общее сообщение об ошибке, если нет специфических ошибок полей
                        alert(response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    alert('Ошибка сервера: ' + textStatus);
                }
            });
        } else {
            console.log('Form validation failed');
        }
    });

    $('#user_phone').val('+7');
});