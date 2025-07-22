class FavouritesManager {
  static init() {
    this.setupEventListeners();
    this.setupStorageListener();
    this.syncAllHearts();
    this.updateCounter();
  }

  static setupEventListeners() {
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-favourite-btn]');
      if (!btn) return;

      e.preventDefault();
      const heart = btn.querySelector('.heart-icon');
      if (heart) this.handleHeartClick(btn, heart);
    });
  }

  static async handleHeartClick(btn, heart) {
    const productId = btn.dataset.productId;
    if (!productId) return;

    const currentState = heart.src.includes('filled');
    const newState = !currentState;
    this.updateHeartIcon(heart, newState);

    try {
      const response = await fetch("../database/favourites-handler.php", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          product_id: productId,
          action: 'toggle'
        })
      });

      const data = await response.json();
      if (!data.success) {
        this.updateHeartIcon(heart, currentState);
        console.error('Ошибка сервера:', data.message);
        return;
      }

      this.syncAllHearts();
      this.updateCounter();
      localStorage.setItem('favoritesUpdated', Date.now());

      // Обновленная логика для пустого состояния
      const card = btn.closest('.product-card');
      if (data.action === 'removed' && card) {
        card.remove();
        
        // Проверяем остались ли товары в контейнере
        const container = document.getElementById('favouriteContainer');
        const hasProducts = container && container.querySelector('.product-card');
        
        // Вызываем функцию переключения состояния
        if (typeof toggleEmptyState === 'function') {
          toggleEmptyState(!hasProducts);
        }
      }

    } catch (error) {
      this.updateHeartIcon(heart, currentState);
      console.error('Ошибка сети:', error);
    }
  }

  static setupStorageListener() {
    window.addEventListener('storage', (event) => {
      if (event.key === 'favoritesUpdated') {
        this.syncAllHearts();
        this.updateCounter();
      }
    });
  }

  static async syncAllHearts() {
    try {
      const response = await fetch("../database/get-favourites-list.php");
      const favourites = await response.json();
      if (!Array.isArray(favourites)) return;

      document.querySelectorAll('[data-favourite-btn]').forEach(btn => {
        const productId = parseInt(btn.dataset.productId);
        const heart = btn.querySelector('.heart-icon');
        const tooltip = btn.querySelector('.tooltip-hint');
        const isFavourite = favourites.includes(productId);

        this.updateHeartIcon(heart, isFavourite);
        if (tooltip) {
          tooltip.textContent = isFavourite ? 'В избранном' : 'В избранное';
        }
      });
    } catch (error) {
      console.error('Ошибка sync избранного:', error);
    }
  }

  static updateHeartIcon(heart, isFavourite) {
    if (!heart) return;
    const isCart = heart.classList.contains('cart-heart');
    heart.src = `../public/assets/${isCart ? 'cart' : 'catalog'}-heart-${isFavourite ? 'filled' : 'empty'}.svg`;
    heart.alt = isFavourite ? '♥' : '🤍';

    const tooltip = heart.closest('[data-favourite-btn]')?.querySelector('.tooltip-hint');
    if (tooltip) {
      tooltip.textContent = isFavourite ? 'В избранном' : 'В избранное';
    }
  }

  static async updateCounter() {
    try {
      const response = await fetch("../database/get-favourites-count.php");
      const data = await response.json();

      const counter = document.getElementById('favourites-count');
      if (counter) {
        const count = data.favCount || 0;
        counter.textContent = count > 99 ? '99+' : count;
        counter.style.display = count > 0 ? 'block' : 'none';
      }
    } catch (error) {
      console.error('Ошибка счётчика избранного:', error);
    }
  }
}

document.addEventListener('DOMContentLoaded', () => FavouritesManager.init());