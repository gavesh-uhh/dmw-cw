if (!localStorage.getItem('cart')) {
    localStorage.setItem('cart', JSON.stringify({}));
    console.log("Cart Initialized");
}

function getCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    console.log('Getting cart items:', cart);
    return cart;
}

function getCartItemCount() {
    const cart = getCartItems();
    const count = Object.values(cart).reduce((total, quantity) => total + quantity, 0);
    console.log('Cart item count:', count);
    return count;
}

function clearCart() {
    localStorage.removeItem('cart');
    console.log('Cart cleared');
}

function updateCartItemCount() {
    const cartItemCount = getCartItemCount();
    const cartCountElement = document.getElementById('cart-item-count');
    if (cartCountElement) {
        cartCountElement.textContent = cartItemCount;
    }
}

function updateQuantity(button, change) {
    const input = button.parentElement.querySelector('.quantity-input');
    const newValue = parseInt(input.value) + change;
    const max = parseInt(input.max);
    
    if (newValue >= 1 && newValue <= max) {
        input.value = newValue;
    }
}

function validateQuantity(input, max) {
    let value = parseInt(input.value);
    if (isNaN(value) || value < 1) {
        value = 1;
    } else if (value > max) {
        value = max;
    }
    input.value = value;
}

function addToCart(productId, quantity) {
    console.log('Adding to cart:', productId, quantity);
    let cart = getCartItems();
    if (cart[productId]) {
        cart[productId] += parseInt(quantity);
    } else {
        cart[productId] = parseInt(quantity);
    }
    console.log('Updated cart:', cart);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartItemCount();
}

// Update cart count immediately and then every second
updateCartItemCount();
setInterval(updateCartItemCount, 1000);

