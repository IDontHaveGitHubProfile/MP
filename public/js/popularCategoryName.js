document.querySelectorAll('.category-name').forEach(el => {
  const length = el.dataset.length; // Берём значение из data-length
  el.style.setProperty('--text-length', length);
});