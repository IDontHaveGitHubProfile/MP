const headerWrapper = document.querySelector('.header-wrapper');
const headerToggleBtn = document.getElementById('headerToggleBtn');
let lastScrollTop = 0;
let isHeaderVisible = true;
let isMobileView = window.innerWidth <= 375;

function updateHeaderHeight() {
  const headerHeight = headerWrapper.offsetHeight;
  document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
  isMobileView = window.innerWidth <= 375;
}

window.addEventListener('load', updateHeaderHeight);
window.addEventListener('resize', updateHeaderHeight);

headerToggleBtn.addEventListener('click', () => {
  if (window.scrollY > 10) { // Только если не в самом верху
    isHeaderVisible = !isHeaderVisible;
    updateHeaderVisibility();
    rotateArrow();
  }
});

function updateHeaderVisibility() {
  if (isHeaderVisible) {
    headerWrapper.style.top = '0';
  } else {
    headerWrapper.style.top = `-${headerWrapper.offsetHeight}px`;
  }
  updateButtonState();
}

function rotateArrow() {
  const arrow = headerToggleBtn.querySelector('img');
  if (isHeaderVisible) {
    arrow.style.transform = 'rotate(0deg)';
  } else {
    arrow.style.transform = 'rotate(180deg)';
  }
}

function updateButtonState() {
  // Отключаем кнопку, если мы вверху страницы и шапка видима
  const shouldDisable = window.scrollY <= 10 && isHeaderVisible;
  headerToggleBtn.disabled = shouldDisable;
  
  // Меняем стиль для визуального отключения
  if (shouldDisable) {
    headerToggleBtn.style.opacity = '0';
    headerToggleBtn.style.cursor = 'default';
  } else {
    headerToggleBtn.style.opacity = '1';
    headerToggleBtn.style.cursor = 'pointer';
  }
}

function updateHeaderVisibility() {
  if (isHeaderVisible) {
    headerWrapper.style.top = '0';
    document.body.classList.remove('hide-profile-dropdown');
  } else {
    headerWrapper.style.top = `-${headerWrapper.offsetHeight}px`;
    document.body.classList.add('hide-profile-dropdown');
  }
  updateButtonState();
}


window.addEventListener('scroll', () => {
  const scrollTop = window.scrollY || document.documentElement.scrollTop;
  
  if (isMobileView) {
    if (scrollTop <= 10) {
      isHeaderVisible = true;
      updateHeaderVisibility();
      rotateArrow();
    }
    updateButtonState(); // Обновляем состояние кнопки при скролле
  } else {
    if (scrollTop > lastScrollTop && scrollTop > 50) {
      headerWrapper.style.top = `-${headerWrapper.offsetHeight}px`;
    } else {
      headerWrapper.style.top = '0';
    }
  }
  
  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});

// Инициализация состояния кнопки при загрузке
updateButtonState();