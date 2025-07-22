$(document).ready(function() {
    // Функция для проверки, есть ли текст в поле
    function checkPhoneInput() {
        const phoneInput = $('#user_phone');
        const clearButton = $('#clear-phone');
        
        if (phoneInput.val() && phoneInput.val() !== '+7') {
            clearButton.show();
        } else {
            clearButton.hide();
        }
    }

    // Обработчик для очистки поля
    $('#clear-phone').on('click', function() {
        $('#user_phone').val('+7').trigger('input');
        $(this).hide();
    });

    // Проверяем поле при вводе
    $('#user_phone').on('input', function() {
        checkPhoneInput();
    });

    // Проверяем поле при загрузке страницы
    checkPhoneInput();
}); 