// СТИЛИЗАЦИЯ АНИМАЦИИ СТРЕЛКИ ДЛЯ SELECT
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".select").forEach(select => {
      const wrapper = select.closest(".select-wrapper");
  
      select.addEventListener("click", function () {
        wrapper.classList.toggle("active");
      });
  
      select.addEventListener("blur", function () {
        setTimeout(() => wrapper.classList.remove("active"), 200);
      });
    });
  });