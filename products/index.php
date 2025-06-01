<?php
session_start();
$page_title = 'Fried Frenzy • Discover';
$page_css = 'products.css';
include '../includes/header.php';
include '../includes/get_products.php';

$products = getProducts();
?>

<body>
    <script src="../js/products.js"></script>
    <header class="header">
        <img src="../assets/logo-header.png" id="nav_logo">
        <div class="header-right">
            <?php
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
            }
            ?>
            <nav class="option-btns">
                <a href="../index.php"> <i style="margin-right: 0.5rem;" class="bi bi-house"></i> Home</a>
                <a href=""> <i style="margin-right: 0.5rem;" class="bi bi-person-lines-fill"></i>Contact Us</a>
            </nav>

            <nav class="nav-btns">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?php echo getRootPath('orders'); ?>">My Orders</a>
                    <a class="icon" href="<?php echo getRootPath('auth/logout'); ?>"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="<?php echo getRootPath('auth/login'); ?>">Login</a>
                    <a href="<?php echo getRootPath('auth/register'); ?>">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="products-container">
        <div class="page-header">
            <h1 class="font-fancy title">Discover</h1>
            <div class="cart-display">
                <i class="bi bi-cart-fill"></i>
                <span id="cart-item-count">0</span>
                <a href="../checkout" class="checkout-btn">Checkout</a>
            </div>
        </div>
        <div class="products-grid">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h2 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-details">
                                <span class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></span>
                                <span class="product-portion"><?php echo $product['per_portion']; ?> portions</span>
                            </div>
                            <div class="product-stock">
                                <?php if ($product['stock'] > 0): ?>
                                    <span class="in-stock">In Stock: <?php echo $product['stock']; ?></span>
                                <?php else: ?>
                                    <span class="out-of-stock">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                            <div class="quantity-selector">
                                <button class="quantity-btn minus" onclick="updateQuantity(this, -1)">-</button>
                                <input type="number" class="quantity-input" value="1" min="1"
                                    max="<?php echo $product['stock']; ?>"
                                    onchange="validateQuantity(this, <?php echo $product['stock']; ?>)">
                                <button class="quantity-btn plus" onclick="updateQuantity(this, 1)">+</button>
                            </div>
                            <button class="add-to-cart" <?php echo $product['stock'] > 0 ? '' : 'disabled'; ?>
                                onclick="addToCart(<?php echo $product['id']; ?>, this.closest('.product-card').querySelector('.quantity-input').value)">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">No products available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>