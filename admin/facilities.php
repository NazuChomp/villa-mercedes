<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$conn =connection();
$stmt = $conn->prepare("SELECT * FROM facilities");
$stmt->execute();
$result = $stmt->get_result();

$status = 'Occupied';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Facility | Villa Mercedes</title>
    <link rel="stylesheet" href="../resources/css/header-footer.css">
    <link rel="stylesheet" href="../resources/css/admin-nav.css">
</head>
<body>
    
<header>
    <div class="header-container">
        <a href="<?= e(url('index.php')) ?>" class="brand-label">Villa Mercedes</a>
        <div class="header-nav">
            <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
            <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Boook</a>
            <a href="<?= e(url('login.php')) ?>" class="<?= e('is-active') ?>">
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

</body>
</html>
