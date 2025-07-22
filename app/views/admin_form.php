<div class="admin-login-container">
  <div class="admin-wrapper">
    <h2 class="ff-ossb">Админ-панель</h2>
    <p class="ff-osr subtitle">Введите данные для доступа</p>

    <form class="form" id="login-form">
      <div class="fields-group">
        <div class="input-container">
          <input
            type="text"
            placeholder="Логин"
            name="admin-username"
            class="ff-osr"
            autocomplete="username"
            id="admin-username"
            required
          />
        </div>

        <div class="input-container">
          <input
            type="password"
            placeholder="Пароль"
            name="admin-password"
            class="ff-osr"
            autocomplete="current-password"
            id="admin-password"
            required
          />
          <div class="ff-osr error-message" id="error-message">
            Неверный логин или пароль. Попробуйте еще раз.
          </div>
        </div>
      </div>

      <button class="ff-osb" type="submit" id="btn-next" disabled>
        <span class="btn-text">Войти в панель управления</span>
      </button>
    </form>

    <a href="index.php?page=home" class="back-to-store ff-osr">← Вернуться в магазин</a>
  </div>
</div>