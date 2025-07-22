document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("regPopupSuccess");
    if (!modal) return;

    const closeBtns = modal.querySelectorAll(".popup-x, .popup-secondary");
    const loginBtn = document.getElementById("regLoginBtn");
    
    let scrollPosition = 0;
    let focusableElements = [];
    let firstFocusableElement = null;
    let lastFocusableElement = null;
    let previousActiveElement = null;

    function lockScroll() {
        scrollPosition = window.pageYOffset;
        document.documentElement.classList.add('popup-open');
    }

    function unlockScroll() {
        document.documentElement.classList.remove('popup-open');
        window.scrollTo(0, scrollPosition);
    }

    function initFocusableElements() {
        focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        firstFocusableElement = focusableElements[0] || null;
        lastFocusableElement = focusableElements[focusableElements.length - 1] || null;
    }

    function trapFocus(e) {
        if (e.key !== 'Tab' || !firstFocusableElement || !lastFocusableElement) return;

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

    function openModal() {
        previousActiveElement = document.activeElement;
        lockScroll();
        
        modal.classList.add('active');
        modal.querySelector('.popup-content')?.classList.add('active');
        
        initFocusableElements();
        firstFocusableElement?.focus();
        
        document.addEventListener('keydown', trapFocus);
    }

    function closeModal() {
        document.removeEventListener('keydown', trapFocus);
        modal.classList.remove('active');
        modal.querySelector('.popup-content')?.classList.remove('active');
        unlockScroll();
        
        previousActiveElement?.focus();
    }

    // Автоматическое открытие из sessionStorage
    if (sessionStorage.getItem('showRegSuccessModal') === '1') {
        sessionStorage.removeItem('showRegSuccessModal');
        openModal();
    }

    // Назначение обработчиков событий
    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    loginBtn?.addEventListener('click', () => {
        window.location.href = "index.php?page=login";
    });
    
    modal.addEventListener('click', (e) => e.target === modal && closeModal());

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
});