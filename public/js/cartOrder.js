document.addEventListener('DOMContentLoaded', function() {
    // Основные элементы
    const cartProductsWrapper = document.getElementById('cartProducts');
    const selectAllCheckbox = document.getElementById('select-all');
    const cartOrderBtn = document.getElementById('cartOrderBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const totalPriceEl = document.querySelector('.corder-total-value');
    const itemCountEl = document.getElementById('selectedItemCount');
    const discountEl = document.querySelector('.corder-price.discount');
    const emailVerified = cartOrderBtn.dataset.verified === '1';

    // Функция форматирования цены
    function formatPrice(price) {
        const num = Math.abs(parseFloat(price) || 0);
        return num.toFixed(2)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

function updateSummary() {
    let total = 0;
    let selectedCount = 0;
    let totalDiscount = 0;
    let hasDiscountItems = false;

    document.querySelectorAll('.cart-product').forEach(card => {
        const checkbox = card.querySelector('.photo-checkbox');
        if (!checkbox?.checked) return;

        const quantity = parseInt(card.querySelector('.quantity-value').value) || 1;
        const finalPrice = parseFloat(card.dataset.finalPrice) || 0;
        const originalPrice = parseFloat(card.dataset.originalPrice) || 0;
        
        selectedCount += quantity;
        total += finalPrice * quantity;

        if (card.dataset.discount === '1') {
            const itemDiscount = (originalPrice - finalPrice) * quantity;
            if (itemDiscount > 0) {
                totalDiscount += itemDiscount;
                hasDiscountItems = true;
            }
        }
    });

    // Обновляем отображение
    if (totalPriceEl) {
        totalPriceEl.textContent = formatPrice(total) + ' р.';
    }

    if (discountEl) {
        discountEl.textContent = hasDiscountItems 
            ? '−' + formatPrice(totalDiscount) + ' р.'
            : '−0,00 р.';
        
        discountEl.classList.toggle('has-discount', hasDiscountItems);
    }

    if (itemCountEl) {
        itemCountEl.textContent = selectedCount;
    }

    // Обновляем состояние кнопок
    cartOrderBtn.disabled = selectedCount === 0;
    deleteSelectedBtn.disabled = selectedCount === 0; // Делаем кнопку неактивной, если ничего не выбрано

    // Обновляем чекбокс "Выбрать все"
    updateSelectAllCheckbox();
}

    // Функция обновления чекбокса "Выбрать все"
    function updateSelectAllCheckbox() {
        if (!selectAllCheckbox) return;
        
        const checkboxes = document.querySelectorAll('.cart-product .photo-checkbox');
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        
        selectAllCheckbox.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
    }

    // Инициализация обработчиков количества (исправленная версия)
    const initQuantityControls = (item) => {
        const plusBtn = item.querySelector('.quantity-controller.plus');
        const minusBtn = item.querySelector('.quantity-controller.minus');
        const quantityInput = item.querySelector('.quantity-value');
        const max = parseInt(quantityInput.getAttribute('max')) || 9999;
        const min = parseInt(quantityInput.getAttribute('min')) || 1;

        // Функция обновления состояния кнопок
        const updateButtonsState = () => {
            const value = parseInt(quantityInput.value) || min;
            minusBtn.disabled = (value <= min);
            plusBtn.disabled = (value >= max);
        };

        // Обработчик увеличения количества
        plusBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            let value = parseInt(quantityInput.value) || min;
            if (value < max) {
                quantityInput.value = value + 1;
                updateButtonsState();
                updateSummary();
            }
        });

        // Обработчик уменьшения количества
        minusBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            let value = parseInt(quantityInput.value) || min;
            if (value > min) {
                quantityInput.value = value - 1;
                updateButtonsState();
                updateSummary();
            }
        });

        // Обработчик ручного ввода
        quantityInput?.addEventListener('change', () => {
            let value = parseInt(quantityInput.value) || min;
            if (isNaN(value)) value = min;
            quantityInput.value = Math.max(min, Math.min(value, max));
            updateButtonsState();
            updateSummary();
        });

        // Обработчик клавиатуры
        quantityInput?.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                plusBtn.click();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                minusBtn.click();
            }
        });

        // Инициализация состояния кнопок
        updateButtonsState();
    };

    // Показать уведомление
    const showAlert = (message, type = 'success') => {
        const alert = document.createElement('div');
        alert.className = `cart-alert ${type}`;
        alert.innerHTML = `
            <div class="alert-icon">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
            </div>
            <div class="alert-message">${message}</div>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        }, 10);
    };

    // Оформить заказ
    const processOrder = async () => {
        if (cartOrderBtn.disabled) return;

        if (!emailVerified) {
            const emailModal = document.getElementById('emailAlertPopup');
            if (emailModal) emailModal.classList.add('active');
            return;
        }

        const selectedItems = Array.from(document.querySelectorAll('.photo-checkbox:checked'))
            .map(checkbox => {
                const card = checkbox.closest('.cart-product');
                const quantity = parseInt(card.querySelector('.quantity-value').value) || 1;
                return {
                    productId: card.dataset.productId,
                    quantity: Math.max(1, Math.min(quantity, 9999))
                };
            });

        if (selectedItems.length === 0) {
            showAlert('Пожалуйста, выберите товары для заказа', 'error');
            return;
        }

        cartOrderBtn.disabled = true;
        const originalText = cartOrderBtn.innerHTML;
        cartOrderBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Оформляем...`;

        try {
            const response = await fetch('../database/order-handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ items: selectedItems })
            });

            const result = await response.json();

            if (result.status === 'success') {
                showAlert('Заказ успешно оформлен!');
                setTimeout(() => {
                    window.location.href = result.redirect + '&t=' + Date.now();
                }, 1500);
            } else {
                showAlert(result.message || 'Ошибка при оформлении заказа', 'error');
            }
        } catch (error) {
            console.error('Order error:', error);
            showAlert('Ошибка соединения с сервером', 'error');
        } finally {
            cartOrderBtn.disabled = false;
            cartOrderBtn.innerHTML = originalText;
        }
    };

const deleteSelected = () => {
    const selected = Array.from(document.querySelectorAll('.photo-checkbox:checked'))
        .map(checkbox => checkbox.closest('.cart-product'));

    if (selected.length === 0) {
        showAlert('Выберите товары для удаления', 'error');
        return;
    }

    // Находим кнопки удаления в выбранных товарах и кликаем по ним
    selected.forEach(item => {
        const deleteBtn = item.querySelector('.remove-from-cart');
        if (deleteBtn) deleteBtn.click(); // Запускаем стандартное удаление
    });

    // showAlert(`Удалено ${selected.length} товаров`);
};

    // Инициализация
    document.querySelectorAll('.cart-product').forEach(item => {
        const checkbox = item.querySelector('.photo-checkbox');
        checkbox?.addEventListener('change', updateSummary);
        initQuantityControls(item);
    });

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.cart-product .photo-checkbox')
                .forEach(checkbox => checkbox.checked = this.checked);
            updateSummary();
        });
    }

    cartOrderBtn.addEventListener('click', processOrder);
    if (deleteSelectedBtn) deleteSelectedBtn.addEventListener('click', deleteSelected);

updateSummary();

// Реакция на клик по надписи "Выбрать всё"
const selectAllText = document.querySelector('.select-all-text');

if (selectAllText && selectAllCheckbox) {
    selectAllText.addEventListener('click', function () {
        selectAllCheckbox.checked = !selectAllCheckbox.checked;
        selectAllCheckbox.dispatchEvent(new Event('change'));
    });
}

});