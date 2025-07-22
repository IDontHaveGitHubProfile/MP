class EmailVerifyTokenHandler {
    constructor() {
        this.debugMode = new URLSearchParams(window.location.search).has('debug');
        this.token = new URLSearchParams(window.location.search).get('token');
        this.type = new URLSearchParams(window.location.search).get('type') || 'email';
        this.baseUrl = this.detectBaseUrl();
        this.emailChannel = new BroadcastChannel('email_verification');
        
        this.initDebugPanel();
        this.showLoading();
        this.startVerification();
    }

    detectBaseUrl() {
        const pathParts = window.location.pathname.split('/');
        if (pathParts.includes('waza')) return '/waza/';
        if (pathParts.includes('public')) return '/public/';
        return '/';
    }

    initDebugPanel() {
        if (!this.debugMode) return;
        
        this.debugPanel = document.createElement('div');
        this.debugPanel.id = 'debugPanel';
        this.debugPanel.style.cssText = `
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px;
            max-height: 200px;
            overflow: auto;
            z-index: 9999;
        `;
        document.body.appendChild(this.debugPanel);
    }

    log(message, data) {
        if (this.debugMode) {
            const now = new Date();
            const entry = document.createElement('div');
            entry.textContent = `[${now.toLocaleTimeString()}] ${message}`;
            this.debugPanel.appendChild(entry);
            
            if (data) {
                const dataEntry = document.createElement('div');
                dataEntry.style.color = '#aaa';
                dataEntry.textContent = JSON.stringify(data, null, 2);
                this.debugPanel.appendChild(dataEntry);
            }
            
            this.debugPanel.scrollTop = this.debugPanel.scrollHeight;
        }
        console.log(`[EmailVerifyToken] ${message}`, data || '');
    }

    showLoading() {
        document.body.classList.add('loading');
    }

    async startVerification() {
        this.log('Начало процесса верификации', {
            token: this.token ? `${this.token.substring(0, 5)}...` : null,
            type: this.type
        });

        if (!this.token) {
            this.handleError(new Error('Токен не найден'));
            return;
        }

        try {
            const response = await this.sendVerificationRequest();
            
            if (response.status === 'success') {
                this.handleSuccess(response);
            } else {
                this.handleError(new Error(response.message));
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    async sendVerificationRequest() {
        const url = `${this.baseUrl}database/verify-token.php?token=${this.token}`;
        this.log('Отправка запроса на сервер', { url });

        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }

    handleSuccess(response) {
        this.log('Успешная верификация', response);
        
        // Очищаем блокировку
        localStorage.removeItem('emailVerificationLock');
        
        // Уведомляем другие вкладки
        this.emailChannel.postMessage({
            type: 'verification_completed',
            email: response.email
        });
        
        // Перенаправляем
        const redirectUrl = `${this.baseUrl}public/index.php?page=profile&email_verified=1`;
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 1500);
    }

    handleError(error) {
        this.log('Ошибка верификации', error.message);
        
        const errorRedirectUrl = `${this.baseUrl}public/index.php?page=home`;
        setTimeout(() => {
            alert(`Ошибка: ${error.message}`);
            window.location.href = errorRedirectUrl;
        }, 1500);
    }
}

// Инициализация
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new EmailVerifyTokenHandler();
    });
} else {
    new EmailVerifyTokenHandler();
}