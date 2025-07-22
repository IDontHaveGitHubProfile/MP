class CartManager {
    static init() {
        this.setupEventListeners();
        this.setupStorageListener();
        this.updateInitialState();
    }

    static setupEventListeners() {
        document.addEventListener('click', async (e) => {
            const addBtn = e.target.closest('.add-to-cart-btn');
            const goToCartBtn = e.target.closest('.go-to-cart-btn');
            const removeBtn = e.target.closest('.remove-from-cart');

            if (addBtn) await this.addToCart(addBtn);
            if (goToCartBtn) window.location.href = "index.php?page=cart";
            if (removeBtn) await this.removeFromCart(removeBtn);
        });
    }

    static setupStorageListener() {
        window.addEventListener('storage', async (event) => {
            if (event.key === 'cartUpdated') {
                await this.updateCartCount();
                await this.updateCartStates();
            }
        });
    }

    static async addToCart(button) {
        if (button.disabled) return;
        const productId = button.dataset.productId;
        button.disabled = true;

        try {
            const response = await this.makeRequest('../database/cart-handler.php', {
                action: 'add',
                product_id: productId
            });

            if (response.success) {
                this.updateCartState(productId, true);
                this.notifyOtherTabs();
                await this.updateCartCount();
            } else {
                console.error(response.message || 'Ошибка добавления');
            }
        } catch (error) {
            console.error('Ошибка добавления товара:', error);
        } finally {
            button.disabled = false;
        }
    }

    static async removeFromCart(button) {
        const productId = button.dataset.id;
        button.disabled = true;

        try {
            const response = await this.makeRequest('../database/cart-handler.php', {
                action: 'remove',
                product_id: productId
            });

            if (response.success) {
                this.removeCartProductElement(productId);
                this.notifyOtherTabs();
                await this.updateCartCount();
                this.updateCartState(productId, false); // обновляем только один товар
                this.updateCartSummary();
                this.checkIfCartEmpty();

                if (typeof updateCartOrderSummary === 'function') {
                    updateCartOrderSummary(); // пересчёт итогов
                }
            } else {
                console.error(response.message || 'Ошибка удаления');
            }
        } catch (error) {
            console.error('Ошибка удаления товара:', error);
        } finally {
            button.disabled = false;
        }
    }

    static removeCartProductElement(productId) {
        const productEl = document.querySelector(`.remove-from-cart[data-id="${productId}"]`)?.closest('.cart-product');
        if (productEl) {
            const checkbox = productEl.querySelector('.photo-checkbox');
            if (checkbox && checkbox.checked) checkbox.checked = false; // снимаем выбор
            productEl.remove();
        }
    }

    static checkIfCartEmpty() {
        const products = document.querySelectorAll('.cart-product');
        const emptyMessage = document.getElementById('emptyCartMessage');
        const cartSection = document.querySelector('.cart-section');

        const isEmpty = products.length === 0;
        if (emptyMessage) emptyMessage.style.display = isEmpty ? 'flex' : 'none';
        if (cartSection) cartSection.style.display = isEmpty ? 'none' : 'flex';
    }

    static updateCartSummary() {
        const summary = document.querySelector('.corder-total-value');
        if (!summary) return;

        let total = 0;
        document.querySelectorAll('.cart-product').forEach(product => {
            const checkbox = product.querySelector('.photo-checkbox');
            if (!checkbox || !checkbox.checked) return;

            const price = parseFloat(product.dataset.finalPrice || product.dataset.originalPrice || '0');
            const qty = parseInt(product.querySelector('.quantity-value')?.value || 1);
            total += price * qty;
        });

        summary.textContent = `${total.toFixed(2)} р.`;
    }

    static async updateCartStates() {
        try {
            const response = await this.makeRequest("../database/cart-handler.php", {
                action: 'check'
            });

            if (response.in_cart) {
                const productIds = response.in_cart.map(id => String(id));
                document.querySelectorAll('.product-card').forEach(card => {
                    const productId = card.dataset.productId;
                    if (productId) {
                        const isInCart = productIds.includes(productId);
                        this.updateCartState(productId, isInCart);
                    }
                });
            }
        } catch (error) {
            console.error('Ошибка синхронизации состояния корзины:', error);
        }
    }

    static async updateCartCount() {
        try {
            const response = await this.makeRequest("../database/get-cart-count.php");
            const cartCount = response.cartCount || 0;

            document.querySelectorAll('#cart-count').forEach(counter => {
                counter.textContent = cartCount > 99 ? '99+' : cartCount;
                counter.style.display = cartCount > 0 ? 'block' : 'none';
            });

        } catch (error) {
            console.error('Ошибка загрузки количества корзины:', error);
        }
    }

    static updateCartState(productId, isInCart) {
        const card = document.querySelector(`.product-card[data-product-id="${productId}"]`);
        if (!card) return;

        const addBtn = card.querySelector('.add-to-cart-btn');
        const goBtn = card.querySelector('.go-to-cart-btn');

        if (isInCart && addBtn) {
            const newGoBtn = document.createElement('a');
            newGoBtn.href = 'index.php?page=cart';
            newGoBtn.className = 'cart-btn go-to-cart-btn ff-um cart-btn-added ta-c';
            newGoBtn.dataset.productId = productId;
            newGoBtn.textContent = 'К корзине';
            addBtn.replaceWith(newGoBtn);
        } else if (!isInCart && goBtn) {
            const newAddBtn = document.createElement('button');
            newAddBtn.className = 'cart-btn add-to-cart-btn ff-um button';
            newAddBtn.dataset.productId = productId;
            newAddBtn.textContent = 'В корзину';
            goBtn.replaceWith(newAddBtn);
        }
    }

    static notifyOtherTabs() {
        localStorage.setItem('cartUpdated', Date.now());
    }

    static makeRequest(url, data = {}) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                type: 'POST',
                data,
                dataType: 'json',
                success: res => res.error ? reject(res.error) : resolve(res),
                error: (_, __, err) => reject(err)
            });
        });
    }

    static async updateInitialState() {
        await Promise.all([
            this.updateCartCount(),
            this.updateCartStates()
        ]);
    }
}

document.addEventListener('DOMContentLoaded', () => CartManager.init());
