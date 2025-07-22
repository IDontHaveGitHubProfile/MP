document.addEventListener("DOMContentLoaded", function() {
    function updateCartCount() {
        fetch("../database/get-cart-count.php")
            .then(response => response.json())
            .then(data => {
                const counters = document.querySelectorAll("#cart-count");
                counters.forEach(counter => {
                    const count = data.cartCount || 0;
                    counter.textContent = count > 99 ? '99+' : count;
                    counter.style.display = count > 0 ? "block" : "none";
                });
            })
            .catch(error => console.error("Cart counter error:", error));
    }

    // Обновляем при изменениях из других вкладок
    window.addEventListener('storage', (e) => {
        if (e.key === 'cartUpdated') updateCartCount();
    });

    // Первичное обновление
    updateCartCount();
});