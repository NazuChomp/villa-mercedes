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
    <h1>Relaxation Awaits</h1>
    <p>Escape the stress and enjoy a peaceful stay at Villa Mercedes Private Resort.</p>
    <a href="<?= e(url('/book.php')) ?>">
        <button class="hero-btn">Book Your Stay Now</button>
    </a>
</div>

<!-- FEATURES -->
<h2 class="features-heading">Why Choose Villa Mercedes?</h2>

<div class="grid">

    <div class="card feature-card">
        <div class="feature-icon">🏊</div>
        <h3>Private Pool</h3>
        <p>Enjoy exclusive access to a clean and spacious swimming pool.</p>
    </div>

    <div class="card feature-card">
        <div class="feature-icon">🌴</div>
        <h3>Relaxing Environment</h3>
        <p>Perfect place to unwind with nature and fresh air.</p>
    </div>

    <div class="card feature-card">
        <div class="feature-icon">🎉</div>
        <h3>Events Ready</h3>
        <p>Great for birthdays, reunions, and small celebrations.</p>
    </div>

</div>

<!-- REVIEWS -->
<div class="reviews">
    <h2>Guest Reviews</h2>

    <div class="grid">
        <div class="review-item">
            <p>"Super relaxing place! Clean pool and very peaceful."</p>
            <p class="review-author">- Maria</p>
        </div>

        <div class="review-item">
            <p>"Perfect for family gatherings. Will definitely come back!"</p>
            <p class="review-author">- John</p>
        </div>

    </div>

</div>

</main>

<!-- FOOTER -->
<footer>

    <div class="footer-grid">

        <div class="footer-col">
            <h3>Villa Mercedes</h3>
            <p>Your destination for relaxation and events in San Ildefonso.</p>
        </div>

        <div class="footer-col">
            <h4>Contact</h4>
            <p>San Ildefonso, Bulacan</p>
            <p>0912-345-6789</p>
        </div>

    </div>

    <div class="footer-bottom">
        &copy; 2026 Villa Mercedes Resort Management System. All Rights Reserved.
    </div>

</footer>

</body>
</html>
