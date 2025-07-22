document.addEventListener('DOMContentLoaded', function () {
  const productContainer = document.getElementById('favouriteContainer');
  const emptyState = document.getElementById('favouriteEmptyState');
  const mainBlock = document.getElementById('favouriteMainBlock');
  const inStockToggle = document.getElementById('fav_in_stock');
  const sortOptions = document.querySelectorAll('.sort-option');
  const sortBtn = document.getElementById('sortDropdownBtn');
  const sortDropdown = document.getElementById('sortDropdownList');

  let currentSort = 'recent';

  function renderSkeletons(count = 8) {
    productContainer.innerHTML = '';
    for (let i = 0; i < count; i++) {
      const skeleton = document.createElement('div');
      skeleton.className = 'skeleton-card';
      skeleton.innerHTML = `
        <div class="skeleton-img"></div>
        <div class="skeleton-text"></div>
        <div class="skeleton-text"></div>
        <div class="skeleton-price"></div>
      `;
      productContainer.appendChild(skeleton);
    }
  }

  function toggleEmptyState(isEmpty) {
    if (isEmpty) {
      emptyState.classList.remove('hidden');
      emptyState.style.display = 'flex';
      mainBlock.classList.add('hidden');
    } else {
      emptyState.classList.add('hidden');
      emptyState.style.display = 'none';
      mainBlock.classList.remove('hidden');
    }
  }

  // Загрузка товаров избранного
  function fetchFavouriteProducts() {
    renderSkeletons();

    $.ajax({
      url: '../database/favourites-view.php',
      type: 'GET',
      data: {
        in_stock: inStockToggle && inStockToggle.checked ? 1 : 0,
        sort: currentSort
      },
      success: function (data) {
        const trimmed = $.trim(data);
        if (!trimmed) {
          toggleEmptyState(true);
          productContainer.innerHTML = '';
        } else {
          toggleEmptyState(false);
          productContainer.innerHTML = trimmed;

          // Инициализация тултипов и синхронизация избранного, если нужно
          if (typeof initCatalogTooltips === 'function') initCatalogTooltips();
          if (window.FavouritesManager?.syncFavourites) {
            window.FavouritesManager.syncFavourites();
          }
        }
      },
      error: function (xhr, status, error) {
        console.error('Ошибка загрузки избранных товаров:', error);
      }
    });
  }

  // Обработка клика по кнопкам сортировки
  sortOptions.forEach(option => {
    option.addEventListener('click', function () {
      sortOptions.forEach(opt => opt.classList.remove('active'));
      this.classList.add('active');

      currentSort = this.dataset.sort;
      sortBtn.textContent = this.textContent;
      sortDropdown.classList.remove('active');

      fetchFavouriteProducts();
    });
  });

  sortBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    sortDropdown.classList.toggle('active');
  });

  document.addEventListener('click', (e) => {
    if (!sortDropdown.contains(e.target) && e.target !== sortBtn) {
      sortDropdown.classList.remove('active');
    }
  });

  if (inStockToggle) {
    inStockToggle.addEventListener('change', fetchFavouriteProducts);
  }

  // Обработка удаления товара из избранного
  productContainer.addEventListener('click', function(e) {
    // Предполагаем, что кнопка удаления имеет класс 'favourite-remove-button' и data-product-id
    if (e.target.classList.contains('favourite-remove-button')) {
      const productId = e.target.dataset.productId;
      if (!productId) return;

      $.ajax({
        url: '../database/favourites-handler.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          action: 'toggle',
          product_id: parseInt(productId, 10)
        }),
        success: function(response) {
          if (response.success && response.action === 'removed') {
            // Обновляем список избранного
            fetchFavouriteProducts();
          } else {
            alert('Не удалось удалить товар из избранного');
          }
        },
        error: function() {
          alert('Ошибка при удалении из избранного');
        }
      });
    }
  });

  // Первая загрузка товаров
  fetchFavouriteProducts();
});
