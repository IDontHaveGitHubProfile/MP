document.addEventListener("DOMContentLoaded", () => {
  const burgerBtn = document.querySelector("#profileBurger");
  const mobileMenu = document.querySelector(".mobile-menu");
  const body = document.body;

  if (!burgerBtn || !mobileMenu) return;

  let focusableElements = [];
  let firstFocusableElement = null;
  let lastFocusableElement = null;

  // Обновляем фокусируемые элементы внутри меню
  function updateFocusableElements() {
    focusableElements = mobileMenu.querySelectorAll("a, button, input, textarea, select, [tabindex]:not([tabindex='-1'])");
    firstFocusableElement = focusableElements[0];
    lastFocusableElement = focusableElements[focusableElements.length - 1];
  }

  // Открытие/закрытие меню
  function toggleMenu() {
    const isActive = mobileMenu.classList.toggle("active");
    burgerBtn.classList.toggle("active", isActive);

    if (isActive) {
      body.style.overflow = "hidden";
      updateFocusableElements();
      firstFocusableElement?.focus();
    } else {
      body.style.overflow = "";
      burgerBtn.focus();
    }
  }

  burgerBtn.addEventListener("click", toggleMenu);

  // Ловушка фокуса + ESC
  mobileMenu.addEventListener("keydown", (e) => {
    if (!mobileMenu.classList.contains("active")) return;

    if (e.key === "Tab") {
      updateFocusableElements(); // на случай динамического контента

      if (e.shiftKey && document.activeElement === firstFocusableElement) {
        e.preventDefault();
        lastFocusableElement.focus();
      } else if (!e.shiftKey && document.activeElement === lastFocusableElement) {
        e.preventDefault();
        firstFocusableElement.focus();
      }
    }

    if (e.key === "Escape") {
      mobileMenu.classList.remove("active");
      burgerBtn.classList.remove("active");
      body.style.overflow = "";
      burgerBtn.focus();
    }
  });

  // Удаляем "залипание" на тач-устройствах
  mobileMenu.addEventListener("touchstart", () => {}, { passive: true });
});
