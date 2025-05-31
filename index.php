<?php
session_start();
$page_title = 'Fried Frenzy • Home';
$page_css = 'home.css';
include 'includes/header.php';
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
                <a href="products"> <i style="margin-right: 0.5rem;" class="bi bi-search"></i> Browse</a>
                <a href=""> <i style="margin-right: 0.5rem;" class="bi bi-person-lines-fill"></i>Contact Us</a>
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
<!--
        <div class="home-data">
            <h1 class="title-our-story">Our Story</h1>
            <p>Founded in 2018, FRIED FRENZY started as a late-night food blog celebrating the most iconic and
                indulgent eats. What began as a passion project quickly grew into a global community of food
                lovers addicted to the crunch, the melt, and the flavor explosions that only junk food can deliver.<br>
                <b>Today, we're more than just a website – we're a celebration of fast food culture,
                    a hub for foodies, and a virtual menu of cravings waiting to be satisfied.</b>
            </p>
        </div>
    </div>
-->

</body>

</html>