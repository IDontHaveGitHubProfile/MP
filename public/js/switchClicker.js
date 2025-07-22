// Получаем все элементы с классом input-switch
const toggleWrappers = document.querySelectorAll('.toggle-wrapper');

toggleWrappers.forEach(wrapper => {
  const toggleInput = wrapper.querySelector('.input-switch'); // находим input внутри каждой обертки
  
  wrapper.addEventListener('click', function () {
    toggleInput.checked = !toggleInput.checked;  // Переключаем состояние чекбокса
  });
});
