document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('cart') || "{}");

    console.log(cart);

    
    if (!window.products) {
        console.error('Products data not found');
        return;
    }
    const productsArray = Object.values(window.products);
    let total = 0;
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const placeOrderBtn = document.getElementById('place-order-btn');

    function updateCartDisplay() {
        const currentCart = JSON.parse(localStorage.getItem('cart') || "{}");
        total = 0;
        
        if (Object.keys(currentCart).length === 0) {
            console.log('Cart is Empty!');
            cartItemsContainer.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
            placeOrderBtn.disabled = true;
        } else {
            let cartHTML = '';
            for (const [productId, quantity] of Object.entries(currentCart)) {
                const product = productsArray.find(p => p.id == productId);
                console.log('Found product:', product.name);
                if (product) {
                    const subtotal = product.price * quantity;
                    total += subtotal;
                    cartHTML += `
                        <div class="cart-item">
                            <div class="item-details">
                                <h3>${product.name}</h3>
                                <p>Quantity: ${quantity}</p>
                                <p>Price Per: Rs. ${Number.parseFloat(product.price)}</p>
                            </div>
                            <div class="item-actions">
                                <div class="item-subtotal">
                                    Rs. ${Number.parseFloat(subtotal)}
                                </div>
                                <button class="remove-btn" onclick="removeFromCart(${product.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }
            }
            cartItemsContainer.innerHTML = cartHTML;
            cartTotalElement.textContent = total;
            placeOrderBtn.disabled = false;
        }
    }

    // Initial cart display
    updateCartDisplay();

    // Make removeFromCart function available globally
    window.removeFromCart = function(productId) {
        const cart = JSON.parse(localStorage.getItem('cart') || "{}");
        delete cart[productId];
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
    };

    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Check if cart is empty before proceeding
        const currentCart = JSON.parse(localStorage.getItem('cart') || "{}");
        if (Object.keys(currentCart).length === 0) {
            const errorMessage = encodeURIComponent('Your cart is empty');
            window.location.href = `order_failed.php?message=${errorMessage}`;
            return;
        }
        
        const submitBtn = document.getElementById('place-order-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        try {
            const formData = new FormData(this);
            formData.append('cart', JSON.stringify(currentCart));
            
            const response = await fetch('process_order.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                localStorage.removeItem('cart');
                window.location.href = `order_success.php?order_id=${result.data.order_id}`;
            } else {
                const errorMessage = encodeURIComponent(result.message);
                window.location.href = `order_failed.php?message=${errorMessage}`;
            }
        } catch (error) {
            console.error('Error:', error);
            const errorMessage = encodeURIComponent('An unexpected error occurred. Please try again.');
            window.location.href = `order_failed.php?message=${errorMessage}`;
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Place Order';
        }
    });
}); 