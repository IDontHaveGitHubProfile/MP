document.addEventListener("DOMContentLoaded", function () {
    const cartFavouritePopupOverlay = document.getElementById("guestAlertPopup");
    if (!cartFavouritePopupOverlay) {
        console.log("Модалка для гостей не найдена");
        return;
    }

    const cartFavouritePopupContent = cartFavouritePopupOverlay.querySelector(".popup-content");
    const closePopupX = cartFavouritePopupOverlay.querySelector(".popup-x");
    const loginBtn = cartFavouritePopupOverlay.querySelector(".popup-primary");
    const registerBtn = cartFavouritePopupOverlay.querySelector(".popup-tertiary");
    const guestHint = document.getElementById("guestHint");

    let scrollPosition = 0;
    let previousActiveElement;
    let focusableElements;
    let firstFocusableElement;
    let lastFocusableElement;

    function lockScroll() {
        scrollPosition = window.pageYOffset;
        document.documentElement.classList.add('popup-open');
        document.body.style.top = `-${scrollPosition}px`;
    }

    function unlockScroll() {
        document.documentElement.classList.remove('popup-open');
        window.scrollTo(0, scrollPosition);
        document.body.style.top = '';
    }

    function lockOutsideElements() {
        document.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        ).forEach(el => {
            if (!cartFavouritePopupOverlay.contains(el)) {
                el.setAttribute('data-locked-tabindex', el.tabIndex);
                el.tabIndex = -1;
            }
        });
    }

    function unlockOutsideElements() {
        document.querySelectorAll('[data-locked-tabindex]').forEach(el => {
            const originalTabIndex = el.getAttribute('data-locked-tabindex');
            if (originalTabIndex !== null) {
                el.tabIndex = parseInt(originalTabIndex);
                el.removeAttribute('data-locked-tabindex');
            } else {
                el.removeAttribute('tabindex');
            }
        });
    }

    function initFocusableElements() {
        focusableElements = cartFavouritePopupOverlay.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        focusableElements = Array.from(focusableElements).filter(el => el.offsetParent !== null); // skip hidden
        firstFocusableElement = focusableElements[0];
        lastFocusableElement = focusableElements[focusableElements.length - 1];
    }

    function trapFocus(e) {
        if (e.key !== 'Tab') return;

        if (e.shiftKey) {
            if (document.activeElement === firstFocusableElement) {
                e.preventDefault();
                lastFocusableElement.focus();
            }
        } else {
            if (document.activeElement === lastFocusableElement) {
                e.preventDefault();
                firstFocusableElement.focus();
            }
        }
    }

function showModal(context = '') {
    if (document.body.classList.contains('logged-in')) return;

    previousActiveElement = document.activeElement;
    lockScroll();
    lockOutsideElements();
    cartFavouritePopupOverlay.classList.add('active');
    cartFavouritePopupContent.classList.add('active');

    // 👇 Устанавливаем фокусное сообщение
    if (guestHint) {
        switch (context) {
            case 'contacts':
                guestHint.textContent = 'Например, чтобы написать нам напрямую через форму обратной связи.';
                break;
            case 'catalog':
                guestHint.textContent = 'Например, чтобы добавлять товары в корзину или в избранное.';
                break;
            default:
                guestHint.textContent = 'Вы пытаетесь воспользоваться функцией, доступной только авторизованным пользователям.';
        }
    }

    // 👉 Убираем фокус из iframe (например, Яндекс.Карты)
    document.querySelectorAll('iframe').forEach(iframe => {
        iframe.setAttribute('data-prev-tabindex', iframe.getAttribute('tabindex') || '');
        iframe.setAttribute('tabindex', '-1');
    });

    initFocusableElements();
    firstFocusableElement?.focus();
    document.addEventListener('keydown', trapFocus);
}

    

    function hideModal() {
        document.removeEventListener('keydown', trapFocus);
        cartFavouritePopupOverlay.classList.remove('active');
        cartFavouritePopupContent.classList.remove('active');
        unlockOutsideElements();
        unlockScroll();

        if (previousActiveElement) {
            previousActiveElement.focus();
        }
    }

    document.addEventListener('click', function(event) {
        if (document.body.classList.contains('logged-in')) return;
    
        const contactsBtn = event.target.closest('#contactsBtn');
        const cartBtn = event.target.closest('.add-to-cart-btn');
        const favBtn = event.target.closest('.heart-btn');
    
        if (contactsBtn) {
            event.preventDefault();
            event.stopPropagation();
            showModal('contacts');
            return;
        }
    
        if (cartBtn || favBtn) {
            event.preventDefault();
            event.stopPropagation();
            showModal('catalog');
            return;
        }
    });
    

    // Закрытие по крестику кликом
    closePopupX?.addEventListener("click", hideModal);

    // Закрытие по клавиатуре на крестику
    closePopupX?.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " " || e.key === "Spacebar") {
            e.preventDefault();
            hideModal();
        }
    });

    // Кнопки входа и регистрации
    loginBtn?.addEventListener("click", () => {
        window.location.href = "index.php?page=login";
    });

    registerBtn?.addEventListener("click", () => {
        window.location.href = "index.php?page=signup";
    });

    // Клик вне модалки
    cartFavouritePopupOverlay.addEventListener("click", (event) => {
        if (event.target === cartFavouritePopupOverlay) hideModal();
    });

    // Escape
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && cartFavouritePopupOverlay.classList.contains("active")) {
            hideModal();
        }
    });

    console.log("Модалка для гостей инициализирована");
});
