<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';
require_once '../utils/booking-functions.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$monthly_revenue = 0; //placeholder for backend
$active_booking = 0;
$pending_approval = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - admin</title>
    <link rel="stylesheet" href="../resources/css/header-footer.css">
    <link rel="stylesheet" href="../resources/css/admin-nav.css">
    <link rel="stylesheet" href="../resources/css/dashboard.css">
</head>

<body>

    <header>
        <div class="header-container">
            <a href="<?= e(url('index.php')) ?>" class="brand-label">Villa Mercedes</a>
            <div class="header-nav">
                <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
                <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Boook</a>
                <a href="<?= e(url('login.php')) ?>" class="<?= $currentPage === 'dashboard.php' ? 'is-active' : '' ?>">
                    <?= isset($_SESSION['user']) ? 'Dashboard Management' : 'Login' ?>
                </a>
            </div>
        </div>
    </header>

    <nav class="admin-subnav">
        <div class="admin-subnav-inner">
            <a href="<?= e(url('admin/dashboard.php')) ?>" class="<?= $currentPage === 'dashboard.php' ? 'is-active' : '' ?>">Dashboard</a>
            <a href="<?= e(url('admin/facilities.php')) ?>" class="<?= $currentPage === 'facilities.php' ? 'is-active' : '' ?>">Facilities</a>
            <a href="<?= e(url('admin/bookings.php')) ?>" class="<?= $currentPage === 'bookings.php' ? 'is-active' : '' ?>">Bookings</a>
            <a href="<?= e(url('admin/reports.php')) ?>" class="<?= $currentPage === 'reports.php' ? 'is-active' : '' ?>">Reports</a>
            <a href="<?= e(url('index.php')) ?>" target="_blank" >View site</a>
        </div>
    </nav>

    <main class="main-dashboard">
        <div class="page-head">
            <div class="dashboard-title-block">
                <div class="h1-row">
                    <h1>Admin Management System</h1>
                    <a href="<?= e(url('logout.php')) ?>" class="logout-btn">Logout</a>
                </div>
                <p>Welcome back, <?= e($_SESSION['user']['full_name']) ?>.</p>                    
            </div>
        </div>

        <div class="summary-tracker">
            <div class="summary-card monthly">
                <p>MONTHLY REVENUE</p>
                <h1><?= e('₱' . number_format($monthly_revenue, 2)) ?></h1>
            </div>
            <div class="summary-card active-booking">
                <p>Active Booking</p>
                <h1><?= $active_booking ?></h1>
            </div>
            <div class="summary-card pending">
                <p>Pendig Approval</p>
                <h1><?= $pending_approval ?></h1>
            </div>
        
        </div>
    </main>
</body>
</html>
