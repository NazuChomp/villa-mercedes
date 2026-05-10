<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$conn =connection();
$stmt = $conn->prepare("SELECT * FROM facilities");
$stmt->execute();
$result = $stmt->get_result();


//------SEATCH AND RESERVATIONS OVERVIEW------
$search = $_GET['name'] ?? '';

if ($search !== '') {

    $recentStmt = $conn->prepare("
        SELECT b.*, f.name AS facility_name
        FROM bookings b
        LEFT JOIN facilities f ON b.facility_id = f.id
        WHERE b.guest_name LIKE ?
        ORDER BY b.id DESC
        LIMIT 5
    ");

    $likeSearch = "%$search%";
    $recentStmt->bind_param("s", $likeSearch);

} else {

    $recentStmt = $conn->prepare("
        SELECT b.*, f.name AS facility_name
        FROM bookings b
        LEFT JOIN facilities f ON b.facility_id = f.id
        ORDER BY b.id DESC
        LIMIT 5
    ");
}

$recentStmt->execute();
$recentResult = $recentStmt->get_result();

//------TOTAL REVENUE------//
$revenueStmt = $conn->prepare("
    SELECT SUM(payment_amount) AS total_revenue
    FROM bookings
    WHERE status = 'Completed'
    AND payment_status = 'Paid'
");

$revenueStmt->execute();
$revenueResult = $revenueStmt->get_result()->fetch_assoc();
$total_revenue = $revenueResult['total_revenue'] ?? 0;

//------ACTIVE BOOKINGS------//
$activeStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE status IN ('Pending', 'Confirmed')
");

$activeStmt->execute();
$activeResult = $activeStmt->get_result()->fetch_assoc();
$active_booking = $activeResult['total'];


//------PENDING BOOKINGS------//
$pendingStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE status = 'Pending'
");

$pendingStmt->execute();
$pendingResult = $pendingStmt->get_result()->fetch_assoc();
$pending_approval = $pendingResult['total'];


$bookingStmt = $conn->prepare("
    SELECT facility_id
    FROM bookings
    WHERE status IN ('Pending', 'Confirmed')
");

$bookingStmt->execute();
$bookingResult = $bookingStmt->get_result();

$occupiedFacilities = [];

while ($booking = $bookingResult->fetch_assoc()) {
    $occupiedFacilities[] = $booking['facility_id'];
}
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
                <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Book</a>
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
        <div class="container page-head">
            <div class="dashboard-title-block">
                <div class="h1-row">
                    <h1>Admin Management System</h1>
                    <a href="<?= e(url('logout.php')) ?>" class="logout-btn">Logout</a>
                </div>
                <p>Welcome back, <?= e($_SESSION['user']['full_name']) ?>.</p>                    
            </div>
        </div>

        <div class="container summary-container">
            <div class="summary-card monthly">
                <p>TOTAL REVENUE</p>
                <h1><?= e('₱' . number_format($total_revenue, 2)) ?></h1>
            </div>
            <div class="summary-card active-booking">
                <p>ACTIVE BOOKING</p>
                <h1><?= $active_booking ?></h1>
            </div>
            <div class="summary-card pending">
                <p>PENDING APPROVAL</p>
                <h1><?= $pending_approval ?></h1>
            </div>
        </div>

        <div class="container facility-container">
            <p>Villa Status Tracker</p>
            <div class="divider">
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="facility-card">
                            <h3><?= e($row['name']) ?></h3>
                            <?php
                                $status = in_array($row['id'], $occupiedFacilities)
                                    ? 'Occupied'
                                    : 'Available';
                            ?>

                                <p class="status <?= $status ?>">
                                    <?= $status ?>
                                </p>
                        </div>
                    <?php endwhile;?>
                <?php else: ?>
                    <p class="no-data">No Facilities Yet</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="container recent-reservation">
            <div class="reservation-row">
                <p>Reservations Overview</p>
                <div class="search-form">
                    <form action="" method="get">
                        <input type="text" name="name" id="name" placeholder="Search name">
                        <button type="submit">Search</button>
                        <a href="dashboard.php" class="clear-button">Clear</a>
                    </form>
                </div>
            </div>
            <div class="reservation-table">
               <?php if ($recentResult->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Facility</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $recentResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($row['guest_name']) ?></td>
                                <td><?= e($row['facility_name']) ?></td>
                                <td>
                                    <span class="status <?= $row['status'] ?>">
                                        <?= e($row['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                        <?php else: ?>
                            <p class="no-data">No Reservation Yet</p>
                        <?php endif; ?>
                </div>
        </div>
    </main>
</body>
</html>
