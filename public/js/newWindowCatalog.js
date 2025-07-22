document.addEventListener("DOMContentLoaded", function() {
    const productContainer = document.getElementById("productContainer");
    if (!productContainer) return;

    // Элементы, клики по которым нужно игнорировать
    const ignoreSelectors = [
        'button',
        '.catalog-heart',
        '.delete-product',
        '.cart-btn',
        '.copy-sku',
        '.copy-icon',
        '.inactive-icon',
        '.heart-btn',
        '.heart-icon'
    ].join(',');

    const shouldIgnoreClick = (target) => {
        return target.closest(ignoreSelectors);
    };

    const openProductPage = (productId) => {
        const url = `index.php?page=product&product_id=${productId}`;

        const newWindow = window.open('', '_blank');
        if (newWindow) {
            newWindow.opener = null;
            newWindow.location.href = url;
        } else {
            window.location.href = url;
        }
    };
    

    const handleProductClick = (event) => {
        const card = event.target.closest('.product-card');
        if (!card) return;
        
        if (shouldIgnoreClick(event.target)) return;
        
        const productId = card.dataset.productId;
        if (!productId) return;
        
        openProductPage(productId);
        event.preventDefault();
        event.stopPropagation();
    };

    // Основной обработчик кликов
    productContainer.addEventListener('click', function(event) {
        // Открываем только по левой кнопке мыши
        if (event.button === 0) {
            handleProductClick(event);
        }
    });

    // Обработчик для средней кнопки мыши
    productContainer.addEventListener('auxclick', function(event) {
        if (event.button === 1) { // Средняя кнопка
            handleProductClick(event);
        }
    });

    // Удаляем возможные конфликтующие обработчики
    const cards = productContainer.querySelectorAll('.product-card');
    cards.forEach(card => {
        card.onclick = null;
        card.onauxclick = null;
    });

    console.log('Product catalog script initialized');
});