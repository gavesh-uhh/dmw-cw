<?php
session_start();
$page_title = 'Fried Frenzy • Home';
$page_css = 'home.css';
include 'includes/header.php';
?>
<body>
    <script src="js/home.js" defer></script>
    <header class="header">
        <img src="assets/logo_bg.png" id="nav_logo">
        <div class="header-right">
            <nav class="option-btns">
                <a href="">Products</a>
                <a href="">Categories</a>
                <a href="">Contact Us</a>
            </nav>
            <nav class="nav-btns">
                <a href="auth/login">Login</a>
                <a href="auth/register">Sign in</a>
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
                    <img src="assets/Home-Pics/0172b81b-0ce7-8fd9-d08f-dfb1938fe068-1280x720.jpg">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/20240522004316-andy-20cooks-20-20kottu-20roti-20recipe.jpg">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/BA-Perfect-Pizza.webp">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/Brown_Sugar_Bacon_RECIPE_081722_38635.webp">
                </div>
                <div class="pics">
                    <img src="assets/Home-Pics/0172b81b-0ce7-8fd9-d08f-dfb1938fe068-1280x720.jpg">

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