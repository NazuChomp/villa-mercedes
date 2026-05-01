<?php
session_start();
require_once 'utils/helper.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Mercedes</title>

    <link rel="stylesheet" href="./resources/css/header-footer.css">
    <link rel="stylesheet" href="./resources/css/landingpage.css">
</head>

<body>

<header>
    <div class="header-container">
        <a href="<?= e(url('./index.php')) ?>" class="brand-label">Villa Mercedes</a>

        <div class="header-nav">
            <a href="<?= e(url('./index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
            <a href="<?= e(url('./book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Book Now</a>
            <a href="<?= e(url('./login.php')) ?>" class="<?= $currentPage === 'login.php' ? 'is-active' : '' ?>">
                <?= isset($_SESSION['user']) ? 'Dashboard Management' : 'Login' ?>
            </a>
        </div>
    </div>
</header>

<main>

<!-- HERO -->
<div class="hero">
    <h1 style="font-size: 3.5rem; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">
        Relaxation Awaits
    </h1>

    <p style="font-size: 1.2rem; margin-top: 10px;">
        Escape the stress and enjoy a peaceful stay at Villa Mercedes Private Resort.
    </p>

    <a href="<?= e(url('./book.php')) ?>">
        <button style="
            width: auto;
            padding: 12px 35px;
            margin-top: 25px;
            background: var(--accent);
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
            border: none;
            cursor: pointer;
            color: white;
        ">
            Book Your Stay Now
        </button>
    </a>
</div>

<!-- FEATURES -->
<h2 style="text-align: center; margin: 60px 0 30px; color: var(--primary); font-size: 2rem;">
    Why Choose Villa Mercedes?
</h2>

<div class="grid">

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🏊</div>
        <h3>Private Pool</h3>
        <p>Enjoy exclusive access to a clean and spacious swimming pool.</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🌴</div>
        <h3>Relaxing Environment</h3>
        <p>Perfect place to unwind with nature and fresh air.</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🎉</div>
        <h3>Events Ready</h3>
        <p>Great for birthdays, reunions, and small celebrations.</p>
    </div>

</div>

<!-- REVIEWS -->
<div style="background: var(--primary); color: white; padding: 50px 30px; border-radius: 15px; margin-top: 60px; text-align: center;">

    <h2 style="margin-bottom: 30px;">Guest Reviews</h2>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">

        <div style="font-style: italic;">
            <p>"Super relaxing place! Clean pool and very peaceful."</p>
            <p style="margin-top: 15px; font-weight: bold; color: var(--accent);">- Maria</p>
        </div>

        <div style="font-style: italic;">
            <p>"Perfect for family gatherings. Will definitely come back!"</p>
            <p style="margin-top: 15px; font-weight: bold; color: var(--accent);">- John</p>
        </div>

    </div>

</div>

</main>

<!-- FOOTER -->
<footer style="background: var(--dark); color: #bdc3c7; padding: 40px 20px; text-align: center; margin-top: 80px; border-radius: 15px 15px 0 0;">

    <div style="max-width: 1200px; margin: auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: left;">

        <div>
            <h3 style="color: white; margin-bottom: 15px;">Villa Mercedes</h3>
            <p>Your destination for relaxation and events in San Ildefonso.</p>
        </div>

        <div>
            <h4 style="color: white; margin-bottom: 15px;">Contact</h4>
            <p>San Ildefonso, Bulacan</p>
            <p>0912-345-6789</p>
        </div>

    </div>

    <div style="margin-top: 40px; border-top: 1px solid #34495e; padding-top: 20px; font-size: 0.8rem;">
        &copy; 2026 Villa Mercedes Resort Management System. All Rights Reserved.
    </div>

</footer>

</body>
</html>
