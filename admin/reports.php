
<?php
session_start();
require_once '../utils/helper.php';
require_once '../database/database.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$conn = connection();

$dateFrom = $_GET['date-from'] ?? '';
$dateTo   = $_GET['date-to'] ?? '';

$stmt = $conn->prepare("SELECT * FROM facilities");
$stmt->execute();
$facilities = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports-admin|Villa Mercedes</title>
    <link rel="stylesheet" href="../resources/css/header-footer.css">
    <link rel="stylesheet" href="../resources/css/admin-nav.css">
    <link rel="stylesheet" href="../resources/css/admin-reports.css">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="<?= e(url('index.php')) ?>" class="brand-label">Villa Mercedes</a>
            <div class="header-nav">
                <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' ? 'is-active' : '' ?>">Home</a>
                <a href="<?= e(url('book.php')) ?>" class="<?= $currentPage === 'book.php' ? 'is-active' : '' ?>">Book</a>
                <a href="<?= e(url('login.php')) ?>" class="is-active">
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
    <main class="reports-main">
        <div class="page-head">
            <div>
                <h1>Reports</h1>
                <p>Real-time summaries: revenue by facility and peak reservation days (search by date range).</p>
            </div>
            <button onclick="window.print()" class="btn print">Print Report</button>
        </div>
        <form action="" method="GET">
            <div>
                <label for="date-from">From</label>
                <input type="date" name="date-from" id="date-from">
            </div>
            <div>
                <label for="date-to">To</label>
                <input type="date" name="date-to" id="date-to">
            </div>
            <div class="action-row">
                <button type="submit" class="btn primary">Apply Range</button>
                <a href="<?= url('admin/reports.php') ?>" class="btn clear">Clear</a>
            </div>
        </form>
        <section class="reports-section">
            <h2>Income by facility</h2>
            <p class="reports-intro">
                Paid amounts for confirmed or completed bookings with payment 
                status <strong>paid</strong>, within the selected range (or all time if empty).
            </p>
            <table>
                <thead>
                    <th>Facilities</th>
                    <th>Paid revenue (PHP)</th>
                    <th>Paid booking count</th>
                </thead>
                <tbody>
                    <?php if ($facilities->num_rows > 0): ?>
                        <?php while ($row = $facilities->fetch_assoc()): ?>

                            <?php
                        $sql = "
                            SELECT 
                            COUNT(*) AS total_bookings,
                            COALESCE(SUM(payment_amount), 0) AS total_revenue
                            FROM bookings
                            WHERE facility_id = ?
                            AND status IN ('Confirmed', 'Completed')
                            AND payment_status = 'Paid'
                            ";

                    if ($dateFrom !== '' && $dateTo !== '') {
                        $sql .= " AND date_start BETWEEN ? AND ?";
                     }

                    $stmt2 = $conn->prepare($sql);

                    if ($dateFrom !== '' && $dateTo !== '') {
                        $stmt2->bind_param("iss", $row['id'], $dateFrom, $dateTo);
                    } else {
                        $stmt2->bind_param("i", $row['id']);
                    }

                    $stmt2->execute();
                        $data = $stmt2->get_result()->fetch_assoc();

                     $total_revenue = $data['total_revenue'] ?? 0;
                    $booking_count = $data['total_bookings'] ?? 0;
                    ?>

                <tr>
                    <td><?= e($row['name']) ?></td>
                    <td>₱<?= number_format($total_revenue, 2) ?></td>
                    <td><?= $booking_count ?></td>
                </tr>

                    <?php endwhile; ?>
            <?php else: ?>
                <tr>
                        <td colspan="3" class="no-data">No facilities found</td>
                </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
