document.addEventListener('DOMContentLoaded', function() {
    console.log('Email verification modal script loaded');
    
    // Константы
    const EMAIL_TOKEN_KEY = 'emailVerificationData';
    const EMAIL_LOCK_KEY = 'emailVerificationLock';
    const ANIMATION_DURATION = 300;
    const LOCK_EXPIRATION = 600000; // 10 минут
    
    // Основные элементы
    const emailModal = document.getElementById('emailAlertPopup');
    const failModal = document.getElementById('emailFailurePopup');
    const waitModal = document.getElementById('emailWaitPopup');
    const repeatModal = document.getElementById('emailRepeatPopup');
    
    if (!emailModal) {
        console.error('Email modal not found in DOM');
        return;
    }

    // Канал для межвкладкового взаимодействия
    const emailChannel = new BroadcastChannel('email_verification');
    
    // Управление модальными окнами
    const modalManager = {
        currentModal: null,
        
        lockScroll() {
            document.documentElement.classList.add('popup-open');
        },
        
        unlockScroll() {
            document.documentElement.classList.remove('popup-open');
        },
        
        open(modal) {
            if (!modal || this.currentModal === modal) return;
            
            console.log(`Opening modal: ${modal.id}`);
            
            if (this.currentModal) {
                this.close(this.currentModal);
            }
            
            this.lockScroll();
            modal.classList.add('active');
            const content = modal.querySelector('.popup-content');
            if (content) content.classList.add('active');
            
            this.currentModal = modal;
            
            const focusable = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            if (focusable.length > 0) focusable[0].focus();
        },
        
        close(modal) {
            if (!modal || !this.currentModal) return;
            
            console.log(`Closing modal: ${modal.id}`);
            
            const content = modal.querySelector('.popup-content');
            if (content) content.classList.remove('active');
            modal.classList.remove('active');
            
            this.unlockScroll();
            this.currentModal = null;
        }
    };

    // Проверка состояния токена в БД
    async function checkTokenStatus() {
        try {
            const response = await fetch('../database/check-token-status.php');
            if (!response.ok) throw new Error('Network response was not ok');
            return await response.json();
        } catch (error) {
            console.error('Token check error:', error);
            return { status: 'inactive' };
        }
    }

    // Проверка локальной блокировки
    function checkEmailLock() {
        const lockData = localStorage.getItem(EMAIL_LOCK_KEY);
        if (!lockData) return false;
        
        try {
            const { expires, email } = JSON.parse(lockData);
            if (Date.now() < expires) {
                return { locked: true, email };
            }
            localStorage.removeItem(EMAIL_LOCK_KEY);
            return false;
        } catch (e) {
            localStorage.removeItem(EMAIL_LOCK_KEY);
            return false;
        }
    }

    // Установка блокировки
    function setEmailLock(email) {
        const lockData = {
            email,
            expires: Date.now() + LOCK_EXPIRATION
        };
        localStorage.setItem(EMAIL_LOCK_KEY, JSON.stringify(lockData));
        
        emailChannel.postMessage({
            type: 'lock_set',
            data: lockData
        });
    }

    // Снятие блокировки
    function clearEmailLock() {
        localStorage.removeItem(EMAIL_LOCK_KEY);
        emailChannel.postMessage({ type: 'lock_cleared' });
    }

    // Обработчик сообщений от других вкладок
    emailChannel.onmessage = (event) => {
        const { type, data } = event.data;
        
        switch (type) {
            case 'lock_set':
                updateUIForLock(data.email);
                if (emailModal?.classList.contains('active')) {
                    modalManager.close(emailModal);
                    showRepeatModal(data.email);
                }
                break;
                
            case 'lock_cleared':
                resetUI();
                break;
                
            case 'verification_completed':
                clearEmailLock();
                break;
        }
    };

    function updateUIForLock(email) {
        const profileBtn = document.getElementById('profileMailBtn');
        if (profileBtn) {
            profileBtn.textContent = 'Ожидает подтверждения';
            profileBtn.disabled = true;
            profileBtn.classList.remove('underline');
        }
    }

    function showRepeatModal(email) {
        if (!repeatModal) return;
        
        const desc = repeatModal.querySelector('#emailRepeatDesc');
        if (desc) {
            desc.textContent = `Мы уже выслали вам письмо на почту ${email || 'вашу почту'}. Пожалуйста, проверьте папку "Спам".`;
        }
        modalManager.open(repeatModal);
    }

    function resetUI() {
        const profileBtn = document.getElementById('profileMailBtn');
        if (profileBtn) {
            profileBtn.textContent = 'Привязать email';
            profileBtn.disabled = false;
        }
    }

    // Инициализация модальных окон
    function initModals() {
        document.querySelectorAll('.popup-overlay').forEach(modal => {
            modal.classList.remove('active');
            const content = modal.querySelector('.popup-content');
            if (content) content.classList.remove('active');
        });
        
        document.querySelectorAll('.popup-x, .popup-secondary').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                modalManager.close(btn.closest('.popup-overlay'));
            });
        });
        
        document.querySelectorAll('.popup-overlay').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modalManager.close(modal);
            });
        });
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalManager.currentModal) {
                modalManager.close(modalManager.currentModal);
            }
        });
    }

    // Инициализация кнопок вызова
    function initTriggerButtons() {
        const buttons = [
            document.getElementById('contactsBtn'),
            document.getElementById('cartOrderBtn'),
            document.getElementById('profileMailBtn')
        ].filter(btn => btn !== null);
        
        buttons.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const lock = checkEmailLock();
                if (lock) {
                    showRepeatModal(lock.email);
                    return;
                }
                
                const tokenStatus = await checkTokenStatus();
                if (tokenStatus.status === 'active') {
                    showRepeatModal(tokenStatus.email);
                } else {
                    modalManager.open(emailModal);
                }
            });
        });
    }

    // Инициализация формы отправки
    function initEmailForm() {
        const emailInput = document.getElementById('emailInput');
        const sendBtn = document.getElementById('sendEmailBtn');
        const emailMessage = document.getElementById('emailMessage');
        const confirmNews = document.getElementById('confirmNews');
        
        if (!emailInput || !sendBtn) return;
        
        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        
        function showMessage(text, type) {
            if (!emailMessage) return;
            emailMessage.textContent = text;
            emailMessage.className = 'ff-ur ' + type;
            emailMessage.classList.add('show');
        }
        
        sendBtn.addEventListener('click', function() {
            const email = emailInput.value.trim();
            const subscribeNews = confirmNews?.checked;
            
            if (!email) {
                showMessage('Пожалуйста, введите email', 'error');
                return;
            }
            
            if (!validateEmail(email)) {
                showMessage('Введите корректный email', 'error');
                return;
            }
            
            const lock = checkEmailLock();
            if (lock) {
                showMessage(`Письмо уже отправлено на ${lock.email}`, 'error');
                return;
            }
            
            setEmailLock(email);
            
            $.ajax({
                url: '../database/verify-email.php',
                type: 'POST',
                dataType: 'json',
                data: { 
                    email: email, 
                    subscribe_news: subscribeNews ? 1 : 0 
                },
                success: function(response) {
                    if (response.status === 'success') {
                        localStorage.setItem(EMAIL_TOKEN_KEY, JSON.stringify({
                            email: email,
                            expires: Date.now() + LOCK_EXPIRATION
                        }));
                        
                        updateUIForLock(email);
                        modalManager.close(emailModal);
                        modalManager.open(waitModal);
                    } else {
                        showMessage(response.message, 'error');
                        clearEmailLock();
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('Ошибка соединения', 'error');
                    modalManager.open(failModal);
                    clearEmailLock();
                }
            });
        });
    }

    // Проверка состояния при загрузке
    function checkInitialState() {
        const lock = checkEmailLock();
        if (lock) updateUIForLock(lock.email);
    }

    // Основная инициализация
    function init() {
        initModals();
        initTriggerButtons();
        initEmailForm();
        checkInitialState();
        
        if (sessionStorage.getItem('showEmailModal') === '1') {
            sessionStorage.removeItem('showEmailModal');
            modalManager.open(emailModal);
        }
    }

    init();

    window.addEventListener('beforeunload', () => {
        emailChannel.close();
    });
});