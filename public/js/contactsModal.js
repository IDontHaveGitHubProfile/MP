document.addEventListener("DOMContentLoaded", function () {
    const contactsPopupOverlay = document.getElementById("contactsPopup");
    if (!contactsPopupOverlay) return;

    const contactsPopupContent = contactsPopupOverlay.querySelector(".popup-content");
    const closeBtn = contactsPopupOverlay.querySelector(".popup-x");
    const openBtn = document.getElementById("contactsBtn");
    const messageInput = document.getElementById("contactMessage");
    const sendBtn = document.getElementById("sendContactBtn");

    let scrollPosition = 0;
    let previousActiveElement;
    let focusableElements;
    let firstFocusableElement;
    let lastFocusableElement;

    // 🔒 Scroll lock
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

    // 🔒 Focus lock
    function lockOutsideElements() {
        document.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])')
            .forEach(el => {
                if (!contactsPopupOverlay.contains(el)) {
                    el.setAttribute('data-locked-tabindex', el.tabIndex);
                    el.tabIndex = -1;
                }
            });
    }

    function unlockOutsideElements() {
        document.querySelectorAll('[data-locked-tabindex]').forEach(el => {
            const original = el.getAttribute('data-locked-tabindex');
            if (original !== null) {
                el.tabIndex = parseInt(original);
                el.removeAttribute('data-locked-tabindex');
            } else {
                el.removeAttribute('tabindex');
            }
        });
    }

    function initFocusableElements() {
        focusableElements = Array.from(contactsPopupOverlay.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        )).filter(el => el.offsetParent !== null);

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

    function showModal() {
        previousActiveElement = document.activeElement;
        lockScroll();
        lockOutsideElements();
        contactsPopupOverlay.classList.add('active');
        contactsPopupContent.classList.add('active');
        initFocusableElements();
        firstFocusableElement?.focus();
        document.addEventListener('keydown', trapFocus);
    }

    function hideModal() {
        document.removeEventListener('keydown', trapFocus);
        contactsPopupOverlay.classList.remove('active');
        contactsPopupContent.classList.remove('active');
        unlockOutsideElements();
        unlockScroll();
        previousActiveElement?.focus();
    }

    openBtn?.addEventListener("click", function (e) {
        e.preventDefault();
        openBtn.classList.add("active");
        setTimeout(() => openBtn.classList.remove("active"), 300);
        showModal();
    });

    closeBtn?.addEventListener("click", hideModal);
    closeBtn?.addEventListener("keydown", function (e) {
        if (["Enter", " ", "Spacebar"].includes(e.key)) {
            e.preventDefault();
            hideModal();
        }
    });
    contactsPopupOverlay.addEventListener("click", e => e.target === contactsPopupOverlay && hideModal());
    document.addEventListener("keydown", e => {
        if (e.key === "Escape" && contactsPopupOverlay.classList.contains("active")) {
            hideModal();
        }
    });

    // === 🔢 СЧЕТЧИК СИМВОЛОВ + БЛОКИРОВКА КНОПКИ ===
    if (messageInput && sendBtn) {
        const counter = document.createElement("span");
        counter.id = "messageCounter";
        counter.className = "char-counter ff-usb ab-text";
        counter.textContent = "0/1000";

        const wrapper = messageInput.closest(".popup-textarea-wrapper");
        if (wrapper) wrapper.appendChild(counter);

        const updateState = () => {
            const len = messageInput.value.trim().length;
            counter.textContent = `${len}/1000`;
            sendBtn.disabled = len === 0;
        };

        messageInput.addEventListener("input", updateState);
        updateState(); // Инициализация
    }

    console.log("Контактная модалка инициализирована");
});
