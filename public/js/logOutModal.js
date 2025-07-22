document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("logOutPopup");

    const profileBtn = document.getElementById("profileLogOutBtn");
    const mobileHeaderBtn = document.getElementById("mobileLogOutBtn");
    const desktopHeaderBtn = document.getElementById("desktopLogOutBtn");

    const closeBtns = document.querySelectorAll(".popup-x, .popup-secondary");
    const confirmBtn = document.getElementById("logOutBtn");
    const profileBlocks = document.querySelectorAll('.profile-block');

    if (!modal || (!profileBtn && !mobileHeaderBtn && !desktopHeaderBtn)) return;

    let scrollPosition = 0;
    let previousActiveElement;
    let focusableElements;
    let firstFocusableElement;
    let lastFocusableElement;

    function lockScroll() {
        scrollPosition = window.pageYOffset;
        document.documentElement.classList.add('popup-open');
    }

    function unlockScroll() {
        document.documentElement.classList.remove('popup-open');
        document.body.classList.remove('popup-open');
        window.scrollTo(0, scrollPosition);
    }

    function lockOutsideElements() {
        profileBlocks.forEach(block => block.classList.add('locked'));

        const allFocusable = document.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );

        allFocusable.forEach(el => {
            if (!modal.contains(el)) {
                el.setAttribute('data-locked-tabindex', el.tabIndex);
                el.tabIndex = -1;
            }
        });
    }

    function unlockOutsideElements() {
        profileBlocks.forEach(block => block.classList.remove('locked'));

        const lockedElements = document.querySelectorAll('[data-locked-tabindex]');
        lockedElements.forEach(el => {
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
        focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
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

    function openModal() {
        previousActiveElement = document.activeElement;
        lockScroll();
        lockOutsideElements();
        modal.classList.add('active');
        modal.querySelector('.popup-content').classList.add('active');

        initFocusableElements();
        firstFocusableElement?.focus();

        document.addEventListener('keydown', trapFocus);
    }

    function closeModal() {
        document.removeEventListener('keydown', trapFocus);
        modal.classList.remove('active');
        modal.querySelector('.popup-content').classList.remove('active');
        unlockOutsideElements();
        unlockScroll();
        previousActiveElement?.focus();
    }

    profileBtn?.addEventListener('click', openModal);
    mobileHeaderBtn?.addEventListener('click', openModal);
    desktopHeaderBtn?.addEventListener('click', openModal);

    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    confirmBtn?.addEventListener('click', function (e) {
        e.preventDefault();

        $.ajax({
            url: "../database/logout.php",
            type: "POST",
            dataType: "json",
            data: { action: "logout" },
            success: function (data) {
                if (data.success) {
                    window.location.href = "index.php?page=login";
                } else {
                    alert("Ошибка выхода: " + (data.message || "Неизвестная ошибка"));
                }
            },
            error: function (xhr, status, error) {
                console.error("Ошибка выхода:", error);
                alert("Ошибка соединения");
            }
        });
    });
});
