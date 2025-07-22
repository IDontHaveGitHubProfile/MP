document.addEventListener("DOMContentLoaded", function () {
    function checkStock(productId, button) {
        fetch("../database/stock-handler.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "product_id=" + encodeURIComponent(productId),
        })
        .then(response => response.json())
        .then(data => {
            if (data.stock <= 0) {
                button.textContent = "Нет в наличии";
                button.disabled = true;
                button.classList.add("out-of-stock");
            } else {
                button.disabled = false;
                button.classList.remove("out-of-stock");
                if (!button.classList.contains("cart-btn-added")) {
                    button.textContent = "Добавить в корзину";
                }
            }
        })
        .catch(error => console.error("Ошибка проверки наличия:", error));
    }

    // Для каталога
    document.querySelectorAll(".add-to-cart-btn").forEach(button => {
        const productId = button.dataset.productId;
        checkStock(productId, button);
        
        setInterval(() => checkStock(productId, button), 5000); // проверка через 5 секунд
    });

    // Для корзины (если понадобится)
    document.querySelectorAll(".update-cart-btn").forEach(button => {
        const productId = button.dataset.productId;
        checkStock(productId, button);
        
        setInterval(() => checkStock(productId, button), 5000); // проверка через 5 секунд
    });
});
