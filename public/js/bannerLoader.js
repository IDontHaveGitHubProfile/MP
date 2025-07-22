document.addEventListener("DOMContentLoaded", function () {
    function loadSwiperScript(callback) {
        if (typeof Swiper === "undefined") {
            console.warn("Первая попытка загрузки Swiper не удалась. Пробуем снова...");

            let script = document.createElement("script");
            script.src = "https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js";
            script.onload = callback;
            script.onerror = function () {
                console.error("Не удалось загрузить Swiper.");
                document.querySelector(".slider-wrapper")?.remove(); // Удаляем слайдер, если он есть
            };

            document.head.appendChild(script);
        } else {
            callback();
        }
    }

    loadSwiperScript(() => {
        console.log("Swiper загружен!");
    });
});
