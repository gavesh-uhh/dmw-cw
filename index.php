<?php
session_start();
$page_title = 'Fried Frenzy • Home';
$page_css = 'home.css';
include 'includes/header.php';
?>

<body>
    <header class="header">
        <img src="assets/logo_bg.png" id="nav_logo">

        <nav class="option-btns">
            <a href="">Products</a>
            <a href="">Categories</a>
            <a href="">Contact Us</a>
        </nav>
        <nav class="nav-btns">
            <a href="auth/login">Login</a>
            <a href="auth/register">Sign in</a>
        </nav>
    </header>
    <marquee scrollamount="10" class="marquee1">The Ultimate Junk Foods at Ultimate Price! </marquee>
    <section class="main-logo-slogan">
        <h1 class="ff">Fried Frenzy</h1>
        <h2 class="slogan">The Ultimate Junk Food Paradise!</h2>
    </section>


    <div class="home-content">
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
</body>

</html>