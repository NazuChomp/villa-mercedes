<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$conn =connection();

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = null;

if ($editId) {
    $editStmt = $conn->prepare("SELECT * FROM facilities WHERE id = ?");
    $editStmt->bind_param("i", $editId);
    $editStmt->execute();
    $editRow = $editStmt->get_result()->fetch_assoc();
}

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
            <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Book</a>
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

<main class="facility-main">
    <div class="page-head">
        <div>
            <h2>Facilities</h2>
            <p>Manage listings, photos, and rates.</p>
        </div>
        <?php if ($editRow): ?>
            <a class="cancel-btn btn" href="<?= e(url('admin/bookings.php')) ?>">Cancel edit</a>
        <?php endif; ?>
    </div>
    
    <div class="form-facilities-wrapper">
        <div class="facility-form">
            <h2><?= !$editRow ? 'Add Facility' : 'Edit Facility' ?></h2>
            <form action="" method="post">
                <?php if ($editRow): ?>
                    <input type="hidden" name="booking_id" value="<?= $editRow['id'] ?>">
                <?php endif; ?>
                <div class="form-row">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                </div>
                <div class="form-row">
                    <label for="description">Description</label>
                    <textarea name="description" id="description"></textarea>
                </div>
                <div class="form-row">
                    <label for="facility_photo" class="btn photo-btn">Select Photo</label>
                    <input type="file" name="facilit_photo" id="facility_photo">
                    <p>JPG, PNG, GIF, or WebP · max 2 MB</p>
                </div>
                <div class="form-row">
                    <label for="capacity">Capacity</label>
                    <input type="number" name="capacity" id="capacity">
                </div>
                <div class="form-row">
                    <label for="rate">Rate</label>
                    <input type="number" name="rate" id="rate">
                </div>
                <div class="form-row">
                    <label for="rate_unit">Rate unit</label>
                    <select name="rate_unit" id="rate_unit">
                        <option value="Per Day">Per Day</option>
                        <option value="Per Session">Per Session</option>
                    </select>
                </div>
                <button type="submit">Add Facility</button>
            </form>
        </div>
    </div>
</main>

</body>
</html>
