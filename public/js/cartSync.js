class CartManager {
    static init() {
        this.setupEventListeners();
        this.setupStorageListener();
        this.updateInitialState();
    }

    static setupStorageListener() {
        window.addEventListener('storage', async (event) => {
            if (event.key === 'cartUpdated') {
                await this.updateCartCount();
                await this.updateCartStates();
            }
        });
    }

    static setupEventListeners() {
        document.addEventListener('click', async (e) => {
            const addBtn = e.target.closest('.add-to-cart-btn');
            const goToCartBtn = e.target.closest('.go-to-cart-btn');

            if (addBtn) await this.addToCart(addBtn);
            if (goToCartBtn) window.location.href = "index.php?page=cart";
        });
    }

    static async addToCart(button) {
        if (button.disabled) return;
        const productId = button.dataset.productId;

        button.disabled = true;
        try {
            const productCard = button.closest('.product-card');
            const productName = productCard?.querySelector('.card-name')?.textContent || 'Товар';

            const response = await this.makeRequest("../database/cart-handler.php", {
                action: 'add',
                product_id: productId
            });

            if (response.success) {
                this.updateCartState(productId, true);
                await this.updateCartCount();
                this.notifyOtherTabs();

                if (this.notificationManager) {
                    this.notificationManager.show(productName, productId);
                }
            }
        } catch (error) {
            console.error('Ошибка при добавлении товара:', error);
            this.showError('Ошибка при добавлении');
        } finally {
            button.disabled = false;
        }
    }

    static async updateCartStates() {
        try {
            const response = await this.makeRequest("../database/cart-handler.php", {
                action: 'check'
            });

            if (response.in_cart) {
                document.querySelectorAll('.product-card').forEach(card => {
                    const productId = card.dataset.productId;
                    if (productId) {
                        this.updateCartState(
                            productId,
                            response.in_cart.includes(parseInt(productId))
                        );
                    }
                });
            }
        } catch (error) {
            console.error('Ошибка обновления состояний корзины:', error);
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

            return cartCount;
        } catch (error) {
            console.error('Ошибка получения количества товаров в корзине:', error);
            return 0;
        }
    }

    static updateCartState(productId, isInCart) {
        const card = document.querySelector(`.product-card[data-product-id="${productId}"]`);
        if (!card) return;

        const addBtn = card.querySelector('.add-to-cart-btn');
        const goBtn = card.querySelector('.go-to-cart-btn');

        if (addBtn) addBtn.style.display = isInCart ? 'none' : 'block';
        if (goBtn) goBtn.style.display = isInCart ? 'block' : 'none';
    }

    static notifyOtherTabs() {
        localStorage.setItem('cartUpdated', Date.now());
    }

    static showError(message) {
        const el = document.createElement('div');
        el.className = 'cart-error-message';
        el.textContent = message;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }

    static async updateInitialState() {
        await Promise.all([
            this.updateCartCount(),
            this.updateCartStates()
        ]);
    }

    static makeRequest(url, data = {}) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                type: 'POST',
                data,
                dataType: 'json',
                success: res => res.error ? reject(res.error) : resolve(res),
                error: (xhr, status, err) => reject(err)
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => CartManager.init());


// class CartManager {
//     static init() {

//         this.setupEventListeners();
//         this.setupStorageListener();
//         this.updateInitialState();
        
//         if (this.isCartPage()) {
//             this.bindCartEvents();
//         }
//     }

//     static isCartPage() {
//         return window.location.pathname.includes('cart') || document.querySelector('.cart-section') !== null;
//     }

//     static setupStorageListener() {
//         window.addEventListener('storage', async (event) => {
//             if (event.key === 'cartUpdated') {
//                 await this.updateCartCount();
//                 if (this.isCartPage()) {
//                     await this.updateCartContent();
//                 } else {
//                     await this.updateCartStates();
//                 }
//             }
//         });
//     }

//     static setupEventListeners() {
//         document.addEventListener('click', async (e) => {
//             const addBtn = e.target.closest('.add-to-cart-btn');
//             const deleteBtn = e.target.closest('.delete-product');
//             const removeBtn = e.target.closest('.remove-from-cart');
//             const quantityPlus = e.target.closest('.quantity-controller.plus');
//             const quantityMinus = e.target.closest('.quantity-controller.minus');
//             const goToCartBtn = e.target.closest('.go-to-cart-btn');

//             if (addBtn) {
//                 await this.addToCart(addBtn);
//             }
//             else if (deleteBtn) {
//                 await this.removeFromCatalog(deleteBtn);
//             }
//             else if (removeBtn) {
//                 e.preventDefault();
//                 await this.removeFromCart(removeBtn);
//             }
//             else if (quantityPlus || quantityMinus) {
//                 const quantityBtn = quantityPlus || quantityMinus;
//                 await this.handleQuantityChange(quantityBtn);
//             }
//             else if (goToCartBtn) {
//                 window.location.href = "index.php?page=cart";
//             }
//         });

//         document.addEventListener('change', async (e) => {
//             if (e.target.classList.contains('quantity-value')) {
//                 await this.handleQuantityInputChange(e.target);
//             }
//         });
//     }

//     static async addToCart(button) {
//         if (button.disabled) return;
//         button.disabled = true;
        
//         try {
//             const productId = button.dataset.productId;
//             const productCard = button.closest('.product-card');
//             const productName = productCard.querySelector('.card-name').textContent;
            
//             const response = await this.makeRequest("../database/cart-handler.php", {
//                 action: 'add',
//                 product_id: productId
//             });

//             if (response.success) {
//                 this.updateCartState(productId, true);
//                 await this.updateCartCount();
//                 this.notifyOtherTabs();
                
//                 this.notificationManager.show(productName, productId);
                
//                 if (this.isCartPage()) {
//                     await this.updateCartContent();
//                 }
//             }
//         } catch (error) {
//             console.error('Add to cart error:', error);
//             this.showError('Ошибка при добавлении товара');
//         } finally {
//             button.disabled = false;
//         }
//     }

//     static async removeFromCart(button) {
//         try {
//             const cartId = button.dataset.cartId;
//             const productId = button.dataset.productId;
//             const productElement = button.closest('.cart-product');

//             if (productElement) {
//                 productElement.classList.add('removing');
//                 button.disabled = true;
//             }

//             const response = await this.makeRequest("../database/cart-handler.php", {
//                 action: 'remove',
//                 cart_id: cartId
//             });

//             if (response.success) {
//                 await new Promise(resolve => setTimeout(resolve, 300));
                
//                 if (this.isCartPage()) {
//                     await this.updateCartContent();
//                 } else {
//                     this.updateCartState(productId, false);
//                 }
                
//                 await this.updateCartCount();
//                 this.notifyOtherTabs();
//             }
//         } catch (error) {
//             console.error('Remove from cart error:', error);
//         }
//     }

//     static async removeFromCatalog(button) {
//         try {
//             const productId = button.dataset.productId;
//             const response = await this.makeRequest("../database/cart-handler.php", {
//                 action: 'remove',
//                 product_id: productId
//             });

//             if (response.success) {
//                 this.updateCartState(productId, false);
//                 await this.updateCartCount();
//                 this.notifyOtherTabs();
                
//                 if (this.isCartPage()) {
//                     await this.updateCartContent();
//                 }
//             }
//         } catch (error) {
//             console.error('Remove from catalog error:', error);
//         }
//     }

//     static async updateCartContent() {
//         try {
//             const response = await this.makeRequest("index.php?page=cart", {
//                 action: 'get_cart_content'
//             });

//             if (response.success) {
//                 const cartCountElement = document.querySelector('.cart-count');
//                 if (cartCountElement) {
//                     cartCountElement.textContent = `Всего товаров в корзине — ${response.cartCount}`;
//                 }
                
//                 const productsContainer = document.querySelector('.cart-products-rg');
//                 if (productsContainer && response.productsHTML) {
//                     productsContainer.innerHTML = response.productsHTML;
//                 }
                
//                 const orderSummary = document.querySelector('.cart-order');
//                 if (orderSummary && response.summaryHTML) {
//                     orderSummary.innerHTML = response.summaryHTML;
//                 }
                
//                 this.bindCartEvents();
//             }
//         } catch (error) {
//             console.error('Update cart content error:', error);
//         }
//     }

//     static bindCartEvents() {
//         document.querySelectorAll('.remove-from-cart').forEach(btn => {
//             btn.addEventListener('click', async (e) => {
//                 e.preventDefault();
//                 await this.removeFromCart(btn);
//             });
//         });

//         document.querySelectorAll('.quantity-controller').forEach(btn => {
//             btn.addEventListener('click', async () => {
//                 await this.handleQuantityChange(btn);
//             });
//         });

//         document.querySelectorAll('.quantity-value').forEach(input => {
//             input.addEventListener('change', async () => {
//                 await this.handleQuantityInputChange(input);
//             });
//         });
//     }

//     static async updateCartStates() {
//         try {
//             const response = await this.makeRequest("../database/cart-handler.php", {
//                 action: 'check'
//             });

//             if (response.in_cart) {
//                 document.querySelectorAll('.product-card').forEach(card => {
//                     const productId = card.dataset.productId;
//                     if (productId) {
//                         this.updateCartState(
//                             productId, 
//                             response.in_cart.includes(parseInt(productId))
//                         );
//                     }
//                 });
//             }
//         } catch (error) {
//             console.error('Update cart states error:', error);
//         }
//     }

//     static async updateCartCount() {
//         try {
//             const response = await this.makeRequest("../database/get-cart-count.php");
//             const cartCount = response.cartCount || 0;
            
//             document.querySelectorAll('#cart-count').forEach(counter => {
//                 counter.textContent = cartCount > 99 ? '99+' : cartCount;
//                 counter.style.display = cartCount > 0 ? 'block' : 'none';
//             });
            
//             return cartCount;
//         } catch (error) {
//             console.error('Update cart count error:', error);
//             return 0;
//         }
//     }

//     static updateCartState(productId, isInCart) {
//         const productCard = document.querySelector(`.product-card[data-product-id="${productId}"]`);
//         if (!productCard) return;
        
//         const addBtn = productCard.querySelector('.add-to-cart-btn');
//         const goToCartBtn = productCard.querySelector('.go-to-cart-btn');
//         const deleteBtn = productCard.querySelector('.delete-product');
        
//         if (addBtn) addBtn.style.display = isInCart ? 'none' : 'block';
//         if (goToCartBtn) goToCartBtn.style.display = isInCart ? 'block' : 'none';
//         if (deleteBtn) deleteBtn.style.display = isInCart ? 'block' : 'none';
//     }

//     static async handleQuantityChange(button) {
//         const input = button.closest('.cart-quantity')?.querySelector('.quantity-value');
//         if (!input) return;
        
//         let quantity = parseInt(input.value) || 1;
        
//         if (button.classList.contains('plus')) {
//             quantity++;
//         } else if (button.classList.contains('minus')) {
//             quantity = Math.max(1, quantity - 1);
//         }
        
//         await this.updateQuantity(input.dataset.productId, quantity, input);
//     }

//     static async handleQuantityInputChange(input) {
//         const quantity = Math.max(1, parseInt(input.value) || 1);
//         await this.updateQuantity(input.dataset.productId, quantity, input);
//     }

//     static async updateQuantity(productId, quantity, inputElement = null) {
//         try {
//             const response = await this.makeRequest("../database/cart-handler.php", {
//                 action: 'update',
//                 product_id: productId,
//                 quantity: quantity
//             });

//             if (response.success) {
//                 if (this.isCartPage()) {
//                     await this.updateCartContent();
//                 }
//                 await this.updateCartCount();
//                 this.notifyOtherTabs();
//             }
//         } catch (error) {
//             console.error('Update quantity error:', error);
//             this.showError('Ошибка при изменении количества');
            
//             if (inputElement) {
//                 inputElement.value = inputElement.dataset.prevValue || 1;
//             }
//         }
//     }

//     static makeRequest(url, data) {
//         return new Promise((resolve, reject) => {
//             $.ajax({
//                 url: url,
//                 type: 'POST',
//                 data: data,
//                 dataType: 'json',
//                 success: (response) => {
//                     if (response.error) {
//                         reject(new Error(response.error));
//                     } else {
//                         resolve(response);
//                     }
//                 },
//                 error: (xhr, status, error) => {
//                     reject(new Error(error));
//                 }
//             });
//         });
//     }

//     static notifyOtherTabs() {
//         localStorage.setItem('cartUpdated', Date.now());
//     }

//     static showError(message) {
//         const errorElement = document.createElement('div');
//         errorElement.className = 'cart-error-message';
//         errorElement.textContent = message;
        
//         document.body.appendChild(errorElement);
//         setTimeout(() => errorElement.remove(), 3000);
//     }

//     static async updateInitialState() {
//         await Promise.all([
//             this.updateCartCount(),
//             this.updateCartStates()
//         ]);
//     }
// }

// document.addEventListener('DOMContentLoaded', () => CartManager.init());