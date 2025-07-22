// УБИРАЕМ OUTLINE ПРИ КЛИКЕ МЫШИ
// НО ДОБАВЛЯЕМ ПРИ НАВИГАЦИЕЙ С ПОМОЩЬЮ TAB
document.addEventListener("mousedown", function () {
  document.body.classList.add("using-mouse");
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Tab") {
      document.body.classList.remove("using-mouse");
  }
});