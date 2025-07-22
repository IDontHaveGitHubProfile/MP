// public/js/adminValidate.js

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('login-form');
  const username = document.getElementById('admin-username');
  const password = document.getElementById('admin-password');
  const submitBtn = document.getElementById('btn-next');
  const errorMessage = document.getElementById('error-message');
  const btnText = submitBtn.querySelector('.btn-text');

  function validateForm() {
    const isValid = username.value.trim() !== '' && password.value.trim() !== '';
    submitBtn.disabled = !isValid;
    return isValid;
  }

  // Инициализация
  validateForm();
  btnText.textContent = 'Войти в панель управления';

  username.addEventListener('input', validateForm);
  password.addEventListener('input', validateForm);

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (!validateForm()) {
      showError('Пожалуйста, заполните оба поля');
      return;
    }

    hideError();
    submitBtn.disabled = true;
    btnText.textContent = 'Загрузка...';

    const formData = new FormData();
    formData.append('username', username.value.trim());
    formData.append('password', password.value.trim());

    fetch('../database/admin-login.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = 'index.php?page=admin_dashboard';
        } else {
          showError(data.message || 'Неверный логин или пароль');
          triggerShake();
          resetButton();
        }
      })
      .catch(() => {
        showError('Ошибка соединения с сервером');
        triggerShake();
        resetButton();
      });
  });

  function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.add('show');
  }

  function hideError() {
    errorMessage.classList.remove('show');
  }

  function resetButton() {
    submitBtn.disabled = false;
    btnText.textContent = 'Войти в панель управления';
  }

  function triggerShake() {
    submitBtn.classList.add('shake');
    setTimeout(() => submitBtn.classList.remove('shake'), 400);
  }
});
