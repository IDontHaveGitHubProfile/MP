document.addEventListener('DOMContentLoaded', function () {
  const productContainer = document.getElementById('productContainer');
  const minRange = document.getElementById('min-range');
  const maxRange = document.getElementById('max-range');
  const minPriceInput = document.getElementById('min-price');
  const maxPriceInput = document.getElementById('max-price');
  const saleToggle = document.getElementById('sale-toggle');
  const stockToggle = document.querySelector('input[name="in_stock"]');
  const clearBtn = document.querySelector('.filter-btn');
  const toggleBtn = document.getElementById("toggleCategoriesBtn");
  const wrap = document.getElementById("toggleCategoriesWrap");

  // Подкатегории (чекбоксы)
  const subcategoriesContainer = document.getElementById('subcategoriesContainer');

  // Скелетоны для загрузки
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

  // Обновление текстовых полей цены при изменении range-слайдеров
  function updatePriceInputs() {
    minPriceInput.value = parseInt(minRange.value).toLocaleString('ru-RU');
    maxPriceInput.value = parseInt(maxRange.value).toLocaleString('ru-RU');
  }

  // Загрузка отфильтрованных товаров
  function fetchFilteredProducts() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category_id');

    // Собираем выбранные подкатегории
    const selectedSubcategories = [];
    if (subcategoriesContainer) {
      subcategoriesContainer.querySelectorAll('.catalog-checkbox:checked').forEach(checkbox => {
        selectedSubcategories.push(checkbox.id.replace('subcategory_', ''));
      });
    }

    const data = {
      min_price: minRange.value,
      max_price: maxRange.value,
      sale: saleToggle.checked ? 1 : 0,
      in_stock: stockToggle.checked ? 1 : 0,
      category_id: categoryId,
      subcategories: selectedSubcategories
    };

    renderSkeletons();

    $.ajax({
      url: '../database/filter-handler.php',
      method: 'GET',
      data: data,
      success: function (html) {
        productContainer.innerHTML = html;

        // Инициализация тултипов после загрузки товаров
        if (typeof initCatalogTooltips === 'function') {
          initCatalogTooltips();
        }

        // Обновление состояния корзины (если есть CartManager)
        if (typeof CartManager !== 'undefined') {
          CartManager.updateCartStates();
          CartManager.updateCartCount();
        }
      },
      error: function (xhr) {
        productContainer.innerHTML = '<p>Ошибка загрузки товаров.</p>';
        console.error(xhr);
      }
    });
  }

  // Обработчик выбора категории из сайдбара
  document.addEventListener('categorySelected', function (e) {
    const { categoryId, categoryName } = e.detail;

    // Обновляем кнопку категории
    const categoryBtn = document.getElementById('catalogCategoriesBtn');
    if (categoryBtn) {
      categoryBtn.textContent = categoryName;
      categoryBtn.title = categoryName;
    }

    // Обновляем URL без перезагрузки страницы
    const url = new URL(window.location.href);
    url.searchParams.set('category_id', categoryId);
    window.history.pushState({}, '', url);

    // Загружаем подкатегории для выбранной категории
    if (subcategoriesContainer) {
      $.ajax({
        url: '../database/load-subcategories.php',
        method: 'GET',
        data: { category_id: categoryId },
        success: function (html) {
          subcategoriesContainer.innerHTML = html;

          // Добавляем класс ff-um только для подкатегорий первого уровня
          subcategoriesContainer.querySelectorAll('.catalog-checkbox-wrapper.level-1 label').forEach(label => {
            label.classList.remove('ff-ur');
            label.classList.add('ff-um');
          });

          // Добавляем обработчики чекбоксов подкатегорий
          subcategoriesContainer.querySelectorAll('.catalog-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', fetchFilteredProducts);
          });

          fetchFilteredProducts();

          // Инициализируем кнопку "Показать все" после загрузки подкатегорий
          if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSubcategories);
          }
        },
        error: function (xhr) {
          console.error('Ошибка загрузки подкатегорий:', xhr);
        }
      });
    }
  });

  // Функция для переключения подкатегорий (Показать все / Свернуть)
  function toggleSubcategories() {
    const hiddenCheckboxes = document.querySelectorAll('.catalog-checkbox-wrapper.hidden');
    const isExpanding = toggleBtn.textContent.includes('Показать');

    hiddenCheckboxes.forEach(el => {
      el.classList.toggle('hidden', !isExpanding);
      el.style.display = isExpanding ? 'flex' : 'none'; // Принудительно устанавливаем display
    });

    toggleBtn.textContent = isExpanding ? 'Свернуть' : 'Показать все';
  }

  // Обработчик кнопки "Очистить"
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      // Сбрасываем категорию
      const categoryBtn = document.getElementById('catalogCategoriesBtn');
      if (categoryBtn) {
        categoryBtn.textContent = 'Все категории';
        categoryBtn.title = 'Все категории';
      }

      // Удаляем параметр категории из URL
      const url = new URL(window.location.href);
      url.searchParams.delete('category_id');
      url.searchParams.delete('subcategories');
      window.history.pushState({}, '', url);

      // Сбрасываем значения фильтров
      minRange.value = minRange.min;
      maxRange.value = maxRange.max;
      saleToggle.checked = false;
      stockToggle.checked = false;

      // Обновляем текстовые поля цены
      updatePriceInputs();

      // Очищаем подкатегории
      if (subcategoriesContainer) {
        subcategoriesContainer.innerHTML = '';
      }

      fetchFilteredProducts();
    });
  }

  // Слушатели изменений фильтров
  [minRange, maxRange].forEach(el => {
    el.addEventListener('input', function() {
      updatePriceInputs();
      fetchFilteredProducts();
    });
  });

  [saleToggle, stockToggle].forEach(el => {
    el.addEventListener('change', fetchFilteredProducts);
  });

  // Обработчики для текстовых полей цены
  minPriceInput.addEventListener('change', function() {
    const value = parseInt(this.value.replace(/\s/g, '')) || minRange.min;
    minRange.value = Math.max(minRange.min, Math.min(value, maxRange.value));
    updatePriceInputs();
    fetchFilteredProducts();
  });

  maxPriceInput.addEventListener('change', function() {
    const value = parseInt(this.value.replace(/\s/g, '')) || maxRange.max;
    maxRange.value = Math.min(maxRange.max, Math.max(value, minRange.value));
    updatePriceInputs();
    fetchFilteredProducts();
  });

  // Первый запуск
  updatePriceInputs();
  fetchFilteredProducts();
});
