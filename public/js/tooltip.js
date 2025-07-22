function createTooltip(text, className) {
  const tooltip = document.createElement('span');
  tooltip.className = `tooltip-hint ff-ur ${className}`;
  tooltip.textContent = text;
  tooltip.style.position = 'absolute';
  tooltip.style.opacity = '0';
  tooltip.style.visibility = 'hidden';
  tooltip.style.pointerEvents = 'none';
  tooltip.style.transition = 'opacity 0.2s ease-in-out';
  tooltip.style.zIndex = '9999';
  return tooltip;
}

function showTooltip(tooltip) {
  tooltip.style.opacity = '1';
  tooltip.style.visibility = 'visible';
}

function hideTooltip(tooltip) {
  tooltip.style.opacity = '0';
  tooltip.style.visibility = 'hidden';
}

function initHeartTooltips() {
  document.querySelectorAll('[data-favourite-btn]').forEach(button => {
    const heart = button.querySelector('.heart-icon');
    if (!heart) return;

    let tooltip = button.querySelector('.tooltip-heart');
    if (!tooltip) {
      tooltip = createTooltip('', 'tooltip-heart');
      button.appendChild(tooltip);
    }

    button.addEventListener('mouseenter', () => {
      tooltip.textContent = heart.src.includes('filled') ? 'В избранном' : 'В избранное';
      showTooltip(tooltip);
    });

    button.addEventListener('mouseleave', () => {
      hideTooltip(tooltip);
    });
  });
}

function initSkuTooltips() {
  document.querySelectorAll('.copy-sku').forEach(span => {
    let tooltip = span.querySelector('.tooltip-sku');
    if (!tooltip) {
      tooltip = createTooltip('Скопировать', 'tooltip-sku');
      span.appendChild(tooltip);
    }

    const copyIcon = span.querySelector('.copy-icon');
    const checkIcon = span.querySelector('.check-icon');
    let copiedTimeout;

    span.addEventListener('mouseenter', () => {
      tooltip.textContent = checkIcon?.style.display === 'inline' ? 'Скопировано' : 'Скопировать';
      showTooltip(tooltip);
    });

    span.addEventListener('mouseleave', () => {
      hideTooltip(tooltip);
      clearTimeout(copiedTimeout);
    });

    span.addEventListener('click', () => {
      const sku = span.dataset.sku || span.textContent.trim();
      navigator.clipboard.writeText(sku).then(() => {
        if (copyIcon) copyIcon.style.display = 'none';
        if (checkIcon) checkIcon.style.display = 'inline';
        tooltip.textContent = 'Скопировано';
        showTooltip(tooltip);

        copiedTimeout = setTimeout(() => {
          if (copyIcon) copyIcon.style.display = 'inline';
          if (checkIcon) checkIcon.style.display = 'none';
          tooltip.textContent = 'Скопировать';
        }, 2000);
      });
    });
  });
}

function initCategoryButtonTooltip() {
  const catBtn = document.getElementById('catalogCategoriesBtn');
  if (!catBtn) return;

  let tooltip = document.querySelector('.tooltip-cat');
  if (!tooltip) {
    tooltip = createTooltip(catBtn.title || 'Все категории', 'tooltip-cat');
    document.body.appendChild(tooltip);
  }

  catBtn.addEventListener('mouseenter', () => {
    tooltip.textContent = catBtn.title || 'Все категории';

    requestAnimationFrame(() => {
      const rect = catBtn.getBoundingClientRect();
      const tipRect = tooltip.getBoundingClientRect();

      tooltip.style.left = `${rect.right + 10 + window.scrollX}px`;
      tooltip.style.top = `${rect.top + rect.height / 2 - tipRect.height / 2 + window.scrollY}px`;

      showTooltip(tooltip);
    });
  });

  catBtn.addEventListener('mouseleave', () => {
    hideTooltip(tooltip);
  });
}

function initCatalogTooltips() {
  initHeartTooltips();
  initSkuTooltips();
}

document.addEventListener('DOMContentLoaded', () => {
  initCatalogTooltips();
  initCategoryButtonTooltip();
});

$(document).on('ajaxComplete', () => {
  initCatalogTooltips();
});
