$(document).ready(function () {
    function updateStockStatus(button) {
        const productId = $(button).data("product-id");

        $.ajax({
            url: "../database/stock-handler.php",
            method: "GET",
            data: { product_id: productId },
            dataType: "json",
            success: function (data) {
                if (data.error) {
                    console.error("Ошибка в данных:", data.error);
                    return;
                }
                if (data.product_quantity === 0) {
                    $(button)
                        .removeClass("button cart-btn-added") // удаляем классы активной кнопки
                        .addClass("cart-btn-quantity ff-um")   // добавляем стили "Нет в наличии"
                        .attr("disabled", "disabled")
                        .text("Не в наличии");
                } else {
                    $(button)
                        .removeClass("cart-btn-quantity") // убираем стили "Нет в наличии"
                        .addClass("button ff-um")          // обязательно ff-um + стиль кнопки
                        .removeAttr("disabled");

                    if (!$(button).hasClass("cart-btn-added")) {
                        $(button).text("В корзину");
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("Ошибка AJAX:", error);
            }
        });
    }

    function checkAllButtons() {
        $(".cart-btn").each(function () {
            updateStockStatus(this);
        });
    }

    // 🔥 Проверка один раз при загрузке страницы
    checkAllButtons();

    // 🔥 Проверка при клике по кнопке
    $(document).on('click', '.cart-btn', function () {
        updateStockStatus(this);
    });
});
