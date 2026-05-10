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
    <link rel="stylesheet" href="../reservation/css/booking.css">
    <link rel="stylesheet" href="../resources/css/admin-facilities.css">
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
            <a class="cancel-btn btn" href="<?= e(url('admin/facilities.php')) ?>">Cancel edit</a>
        <?php endif; ?>
    </div>
    
    <div class="form-facilities-wrapper">
        <div class="facility-form">
            <h2><?= !$editRow ? 'Add Facility' : 'Edit Facility' ?></h2>
            <form action="../repositories/facilityRepository.php" method="post" enctype="multipart/form-data">
                <?php if ($editRow): ?>
                    <input type="hidden" name="facility_id" value="<?= $editRow['id'] ?>">
                <?php endif; ?>
                <div class="form-row">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?= e($editRow['name'] ?? '')?>">
                </div>
                <div class="form-row">
                    <label for="description">Description</label>
                    <textarea name="description" id="description"><?= e($editRow['description'] ?? '') ?></textarea>
                </div>
                <div class="form-row">
                    <label for="facility_photo" class="btn photo-btn">Select Photo</label>
                    <input type="file" name="facility_photo" id="facility_photo">
                    <p>JPG, PNG, GIF, or WebP · max 2 MB</p>
                </div>
                <div class="form-row">
                    <label for="capacity">Capacity</label>
                    <input type="number" name="capacity" id="capacity" value="<?= e($editRow['capacity']?? '') ?>">
                </div>
                <div class="form-row">
                    <label for="rate">Rate</label>
                    <input type="number" name="rate" id="rate" value="<?= e($editRow['rate_amount'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <label for="rate_unit">Rate unit</label>
                    <select name="rate_unit" id="rate_unit">
                        <option value="Per Day">Per Day</option>
                        <option value="Per Session">Per Session</option>
                    </select>
                </div>
                <button type="submit"><?= !$editRow ? 'Add Facility' : 'Save Changes' ?></button>
            </form>
        </div>

        <div class="facility-table">
            <h2>Facilities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Rate</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($table_row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="<?= e(url('resources/img/') . $table_row['image_filename']) ?>"
                                    alt="<?=e($table_row['image_filename']) ?>">
                                </td>
                                <td><?= e($table_row['name']) ?></td>
                                <td><?= e($table_row['rate_amount']) ?></td>
                                <td><?= e($table_row['capacity']) ?></td>
                                <td>
                                    <div class="action-cell">
                                        <a href="<?= e(url('admin/facilities.php?edit=' . (int) $table_row['id'])) ?>" class="edit-btn">Edit</a>
                                        <form action="../repositories/facilityRepository.php" method="POST" onsubmit="return confirm('Delete this Facility?')">
                                            <input type="hidden" name="facility_id" value="<?= (int) $table_row['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">
                                    No Bookings Yet
                                </td>
                            </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>
