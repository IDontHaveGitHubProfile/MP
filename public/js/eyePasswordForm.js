// ГЛАЗИК В ФОРМАХ РЕГИСТРАЦИИ И АВТОРИЗАЦИИ
$(document).ready(function () {
    // Переключение видимости пароля
    $('#toggle-password').on('click', function () {
        let passwordField = $('#user_password');
        let passwordFieldType = passwordField.attr('type');

        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).html('<i class="fa fa-eye-slash"></i>'); // Иконка закрытого глаза
        } else {
            passwordField.attr('type', 'password');
            $(this).html('<i class="fa fa-eye"></i>'); // Иконка открытого глаза
        }
    });

    // Переключение видимости подтверждения пароля
    $('#toggle-password-confirm').on('click', function () {
        let confirmPasswordField = $('#user_password_confirm');
        let confirmPasswordFieldType = confirmPasswordField.attr('type');

        if (confirmPasswordFieldType === 'password') {
            confirmPasswordField.attr('type', 'text');
            $(this).html('<i class="fa fa-eye-slash"></i>'); // Иконка закрытого глаза
        } else {
            confirmPasswordField.attr('type', 'password');
            $(this).html('<i class="fa fa-eye"></i>'); // Иконка открытого глаза
        }
    });
});