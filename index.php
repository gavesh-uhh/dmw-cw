<?php
session_start();
$page_title = 'Fried Frenzy • Home';
$page_css = 'home.css';
include 'includes/header.php';
include 'includes/db_connect.php'
    ?>

<body>
    <script src="js/home.js" defer></script>
    <header class="header">
        <img src="assets/logo-header.png" id="nav_logo">
        <div class="header-right">
            <?php
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
            }
            ?>
            <nav class="option-btns">
                <a href="products"><i class="bi bi-search"></i> Browse</a>
                <a href="contact"><i class="bi bi-person-lines-fill"></i>Contact Us</a>
            </nav>

            <nav class="nav-btns">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="orders">My Orders</a>
                    <a class="icon" href="auth/logout"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="auth/login">Login</a>
                    <a href="auth/register">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <marquee scrollamount="10" class="marquee1">The Ultimate Junk Foods at Ultimate Price! </marquee>
    <section class="main-logo-slogan">
        <h1 class="ff">Fried Frenzy</h1>
        <h2 class="slogan">The Ultimate Junk Food Paradise!</h2>
    </section>

    <!-- HOME ITEMS-->
    <div class="home-content">
        <div class="pic-carousel">
            <div class="pics-list">
                <div class="pics">
                    <img src="./assets/Home-Pics/1.jpg">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/2.webp">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/3.jpg">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/4.webp">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/5.webp">
                </div>
            </div>
            <!--Buttons < & > -->
            <div class="buttons">
                <button id="previous"><- </button>
                        <button id="next">-></button>
            </div>
            <!-- PicCounter -->
            <ul class="dots">
                <li class="acting"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>

    <div class="featured-dishes">
        <h1>Featured</h1>
        <div class="card-set">
            <?php
            $stmt = $conn->prepare("SELECT * FROM products LIMIT 4");
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                echo '<p class="error">Unable to load products. Please try again later.</p>';
            } else {
                $stmt->execute();
                $result = $stmt->get_result();

                while ($product = $result->fetch_assoc()): ?>
                    <div class="Card">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-details">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <a href="<?php echo getRootPath('products'); ?>" class="card-btn">Order</a>
                        </div>
                    </div>
                <?php endwhile;
                $stmt->close();
            }
            ?>
        </div>
    </div>



    <div class="wordings-jf">
        <div class="fun-fact1">
            <h1> Fun Fact </h1>
            <h2>The Invention of Potato Chips Was an Accident </h2>
            <p>In 1853, a chef named George Crum sliced potatoes extra thin to annoy a complaining customer. After that
                the customer Surprised. The customer loved it. and thats how chips were born.</p>
        </div>
        <div class="home-data">
            <h1 class="title-our-story">Our Story</h1>
            <p>Founded in 2018, FRIED FRENZY started as a late-night food blog celebrating the most iconic and
                indulgent eats. What began as a passion project quickly grew into a global community of food
                lovers addicted to the crunch, the melt, and the flavor explosions that only junk food can deliver.<br>
                <b>Today, we're more than just a website - we're a celebration of fast food culture,
                    a hub for foodies, and a virtual menu of cravings waiting to be satisfied.</b>
            </p>
        </div>
        <div class="fun-fact2">
            <h1> Satisfy Your Cravings </h1>
            <h2>One Bite at a Time! </h2>
            <p>Junk food isn't just a snack! <br>it's an experience. That moment when you unwrap a hot burger, pop open
                a soda, and grab a side of golden fries… it's like emotion.<b> Whether you're a pizza purist or a
                    dessert devourer, FRIED FRENZY has something that speaks your flavor language.</b></p>
        </div>
    </div>
    <footer>
        <h3 class="copy-right">&copy; Made By Gavesh | Timesh | Siluna | Yashan • All Rights Recei</h3>
        <hr style="opacity: 50%; margin: 1rem 0;">
        <div class="footer-links">
            <a href="<?php echo getRootPath('admin'); ?>">Admin Login</a>
        </div>
    </footer>
</body>

</html>