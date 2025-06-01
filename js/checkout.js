document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('cart') || "{}");
    if (!window.products) {
        console.error('Products data not found');
        return;
    }
    const productsArray = Object.values(window.products);
    let total = 0;
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const placeOrderBtn = document.getElementById('place-order-btn');

    if (Object.keys(cart).length === 0) {
        console.log('Cart is Empty!');
        cartItemsContainer.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
        placeOrderBtn.disabled = true;
    } else {
        let cartHTML = '';
        for (const [productId, quantity] of Object.entries(cart)) {
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
                            <p>Price: Rs. ${Number.parseFloat(product.price)}</p>
                        </div>
                        <div class="item-subtotal">
                            Rs. ${Number.parseFloat(subtotal)}
                        </div>
                    </div>
                `;
            }
        }
        cartItemsContainer.innerHTML = cartHTML;
        cartTotalElement.textContent = total;
        placeOrderBtn.disabled = false;
    }

    document.getElementById('checkout-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-order');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        try {
            const formData = new FormData(this);
            formData.append('cart', JSON.stringify(cart));
            
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