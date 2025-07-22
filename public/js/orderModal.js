document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('orderPopup');
    if (!popup) return;

    const closePopup = () => {
        popup.classList.remove('active');
        setTimeout(() => popup.remove(), 300);
    };

    // Обработчики закрытия
    popup.querySelectorAll('.popup-x, .popup-secondary').forEach(btn => {
        btn.addEventListener('click', closePopup);
    });

    // Закрытие по клику на оверлей
    popup.addEventListener('click', function(e) {
        if (e.target === this) closePopup();
    });

    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePopup();
    });
});