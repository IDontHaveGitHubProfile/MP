$(document).ready(function() {
    const passwordToggle = $('#passwordToggle'); // Селектор для картинки
    const passwordInput = $('#passwordInput');
    const eyeIcon = passwordToggle; // Теперь eyeIcon это сама картинка

    passwordToggle.on('click', function() {
        const status = $(this).closest('.popup-sideform-icon').data('status'); // Получаем статус от родительского span

        if (status === 'closed') {
            // Теперь показываем пароль
            passwordInput.attr('type', 'text');
            $(this).closest('.popup-sideform-icon').data('status', 'open');
            eyeIcon.attr('src', '../public/assets/eye-closed.svg');
        } else {
            // Теперь скрываем пароль
            passwordInput.attr('type', 'password');
            $(this).closest('.popup-sideform-icon').data('status', 'closed');
            eyeIcon.attr('src', '../public/assets/eye.svg');
        }
    });
});
