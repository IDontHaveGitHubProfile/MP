class EmailRepeatModal {
    constructor(email) {
        this.modal = document.getElementById('emailRepeatPopup');
        if (!this.modal) return;

        // Устанавливаем email в текст
        const placeholder = this.modal.querySelector('.email-placeholder');
        if (placeholder) placeholder.textContent = email;

        this.setupEventListeners();
        this.open();
    }

    setupEventListeners() {
        // Закрытие модалки
        this.modal.querySelectorAll('.popup-x, .popup-secondary').forEach(btn => {
            btn.addEventListener('click', () => this.close());
        });

        // Клик вне модалки
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.close();
        });

        // Escape для закрытия
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                this.close();
            }
        });
    }

    open() {
        this.modal.classList.add('active');
        document.documentElement.classList.add('popup-open');
    }

    close() {
        this.modal.classList.remove('active');
        document.documentElement.classList.remove('popup-open');
    }
}