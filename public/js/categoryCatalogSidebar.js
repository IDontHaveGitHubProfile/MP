document.addEventListener("DOMContentLoaded", function () {
  const btn = document.getElementById("headerCategoriesBtn");
  const sidebar = document.getElementById("categorySidebar");
  const overlay = document.getElementById("categorySidebarOverlay");
  const closeBtn = document.getElementById("closeSidebarBtn");

  function openSidebar() {
    sidebar.classList.add("active");
    overlay.classList.add("active");
    if (btn) btn.classList.add("catalog-open");
    document.documentElement.classList.add("popup-open");

    trimCategoryText();
    initCategories();
    trapFocus(sidebar);
  }

  function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    if (btn) btn.classList.remove("catalog-open");
    document.documentElement.classList.remove("popup-open");
  }

  if (btn) {
    btn.addEventListener("click", () => {
      sidebar.classList.contains("active") ? closeSidebar() : openSidebar();
    });
  }

  if (overlay) overlay.addEventListener("click", closeSidebar);
  if (closeBtn) closeBtn.addEventListener("click", closeSidebar);

  // === Очистка пробелов ===
  function trimCategoryText() {
    document.querySelectorAll(".category-label").forEach(el => {
      el.textContent = el.textContent.trim();
    });
  }

  // === Ловушка фокуса ===
  function trapFocus(container) {
    const focusableSelectors = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';
    const focusableElements = container.querySelectorAll(focusableSelectors);
    const firstEl = focusableElements[0];
    const lastEl = focusableElements[focusableElements.length - 1];

    document.addEventListener("keydown", function (e) {
      if (!sidebar.classList.contains("active")) return;
      if (e.key === "Tab") {
        if (e.shiftKey) {
          if (document.activeElement === firstEl) {
            e.preventDefault();
            lastEl.focus();
          }
        } else {
          if (document.activeElement === lastEl) {
            e.preventDefault();
            firstEl.focus();
          }
        }
      } else if (e.key === "Escape") {
        closeSidebar();
      }
    });

    setTimeout(() => firstEl && firstEl.focus(), 100);
  }

  // === Категории ===
  function initCategories() {
    const leftItems = document.querySelectorAll(".category-item");
    const subLinks = document.querySelectorAll(".subcategory-link, .subcategory-simple-link, .sub-sub-list a");
    const sidebarRight = document.querySelector(".category-sidebar-right");
    const isCatalogPage = new URL(window.location.href).searchParams.get('page') === 'catalog';

    // Обработчик для главных категорий
    leftItems.forEach((item) => {
      item.addEventListener("click", function(e) {
        e.preventDefault();
        const categoryId = item.dataset.id;
        const categoryName = item.dataset.name;
        
        if (isCatalogPage) {
          selectCategory(categoryId, categoryName);
        } else {
          window.location.href = `index.php?page=catalog&category_id=${categoryId}`;
        }
      });
    });

    // Обработчик для подкатегорий и подподкатегорий
    subLinks.forEach((link) => {
      link.addEventListener("click", function(e) {
        if (isCatalogPage) {
          e.preventDefault();
          const parentItem = link.closest('[data-parent]');
          const parentId = parentItem ? parentItem.dataset.parent : null;
          
          if (parentId) {
            const parentCategory = document.querySelector(`.category-item[data-id="${parentId}"]`);
            if (parentCategory) {
              selectCategory(parentId, parentCategory.dataset.name);
            }
          }
        }
      });
    });

    function selectCategory(categoryId, categoryName) {
      // Закрываем сайдбар
      closeSidebar();
      
      // Отправляем событие о выборе категории
      document.dispatchEvent(new CustomEvent('categorySelected', {
        detail: { categoryId, categoryName }
      }));
    }

    // Показываем подкатегории при наведении (только на десктопе)
    if (window.innerWidth > 768) {
      leftItems.forEach((item) => {
        item.addEventListener("mouseenter", function() {
          const categoryId = item.dataset.id;
          showSubcategories(categoryId);
          leftItems.forEach((i) => i.classList.remove("active"));
          item.classList.add("active");
        });
      });

      // Показываем подкатегории первой категории при открытии
      const first = leftItems[0];
      if (first) {
        showSubcategories(first.dataset.id);
        first.classList.add("active");
      }
    }

    function showSubcategories(parentId) {
      document.querySelectorAll(".subcategories-container").forEach((container) => {
        container.classList.remove("visible");
      });

      const targetContainer = document.querySelector(`.subcategories-container[data-parent='${parentId}']`);
      if (targetContainer) {
        targetContainer.classList.add("visible");
        if (sidebarRight) sidebarRight.scrollTo(0, 0);
      }
    }
  }
});