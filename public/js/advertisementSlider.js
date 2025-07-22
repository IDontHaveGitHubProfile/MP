document.addEventListener("DOMContentLoaded", () => {
    function initSwiper() {
        if (typeof Swiper !== "undefined") {
            console.log("Swiper загружен!");
            const swiper = new Swiper(".mySwiper", {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false
                },
                navigation: {
                    nextEl: ".next",
                    prevEl: ".prev"
                },
                pagination: {
                    el: ".swiper-pagination"
                },
                grabCursor: window.innerWidth <= 768,
                allowTouchMove: window.innerWidth <= 768,
                mousewheel: false,
                effect: "slide",
            });

            window.addEventListener("resize", () => {
                const isMobile = window.innerWidth <= 768;
                swiper.allowTouchMove = isMobile;
                swiper.grabCursor = isMobile;
            });

            // Сдвиг стрелки при нажатии
            const prevArrow = document.querySelector(".prev");
            const nextArrow = document.querySelector(".next");

            const handleArrowClick = (arrowType) => {
                const arrow = arrowType === "prev" ? prevArrow : nextArrow;

                if (arrowType === "prev") {
                    arrow.classList.add("shift-left");
                    setTimeout(() => {
                        arrow.classList.remove("shift-left");
                    }, 300); // Класс удаляется через 300 миллисекунд
                } else {
                    arrow.classList.add("shift-right");
                    setTimeout(() => {
                        arrow.classList.remove("shift-right");
                    }, 300); // Класс удаляется через 300 миллисекунд
                }
            };

            prevArrow.addEventListener("click", () => handleArrowClick("prev"));
            nextArrow.addEventListener("click", () => handleArrowClick("next"));
        } else {
            console.warn("Swiper.js не загрузился. Удаляем слайдер.");
            document.querySelector(".slider-wrapper")?.remove();
        }
    }

    setTimeout(() => {
        if (typeof Swiper === "undefined") {
            console.warn("Первая попытка загрузки Swiper не удалась. Пробуем снова...");
            let script = document.createElement("script");
            script.src = "https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js";
            script.onload = () => {
                console.log("Swiper загружен повторно.");
                initSwiper();
            };
            script.onerror = () => console.error("Не удалось загрузить Swiper.");
            document.head.appendChild(script);
        } else {
            initSwiper();
        }
    }, 2000);
});
