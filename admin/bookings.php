
<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';
require_once '../utils/booking-functions.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$editRow = null;
$conn = connection();
$stmt = $conn->prepare("SELECT * FROM facilities");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking-admin|Villa Mercedes</title>
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

<main class="admin-booking">
    <div class="page-head">
        <div>
            <h1>Bookings</h1>
            <p>Search, filter, and maintain reservations.</p>
        </div>
        <?php if ($editRow): ?>
            <a class="cancel-btn" href="<?= e(url('admin/bookings.php')) ?>">Cancel edit</a>
        <?php endif; ?>
    </div>

    <div class="filters">
        <form action="" method="get">
            <div class="form-row">
                <label for="facility">Facility</label>
                <select name="facility" id="facility">
                    <option value="">All</option>
                    <?php
                    while($row = $result->fetch_assoc()):
                    ?>
                    <option value="<?= e($row['id']) ?>"><?= e($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-row">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancel">Cancel</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-row">
                <label for="from-date">From</label>
                <input type="date" id="from-date" name="from-date">
            </div>
            <div class="form-row">
                <label for="to-date">To</label>
                <input type="date" name="to-date" id="to-date">
            </div>
            <div class="form-row">
                <label for="search-input">Search</label>
                <input type="text" name="search-input" id="search-input">
            </div>
            <button class="apply-btn">Apply</button>
        </form>
    </div>
</main>

</body>
</html>
